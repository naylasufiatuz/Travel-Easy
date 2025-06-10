<?php
require('db_config.php');

// Handle status updates
if (isset($_POST['update_status'])) {
    $review_id = (int)$_POST['review_id'];
    $new_status = mysqli_real_escape_string($con, $_POST['status']);
    
    $update_query = "UPDATE reviews SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($con, $update_query);
    mysqli_stmt_bind_param($stmt, "si", $new_status, $review_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Handle delete
if (isset($_POST['delete_review'])) {
    $review_id = (int)$_POST['review_id'];
    
    $delete_query = "DELETE FROM reviews WHERE id = ?";
    $stmt = mysqli_prepare($con, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $review_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Get all reviews
$reviews_query = "SELECT * FROM reviews ORDER BY created_at DESC";
$reviews_result = mysqli_query($con, $reviews_query);

// Get statistics
$stats_query = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
    SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected,
    AVG(rating) as avg_rating
    FROM reviews";
$stats_result = mysqli_query($con, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Reviews</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar bisa ditambahkan di sini -->
        <div class="col-12">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Reviews Management</h1>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5>Total Reviews</h5>
                                    <h2><?php echo $stats['total']; ?></h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-chat-square-dots fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5>Pending</h5>
                                    <h2><?php echo $stats['pending']; ?></h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-clock fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5>Approved</h5>
                                    <h2><?php echo $stats['approved']; ?></h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-check-circle fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5>Avg Rating</h5>
                                    <h2><?php echo number_format($stats['avg_rating'], 1); ?></h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-star fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews Table -->
            <div class="card">
                <div class="card-header">
                    <h5>All Reviews</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Subject</th>
                                    <th>Message</th>
                                    <th>Rating</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($review = mysqli_fetch_assoc($reviews_result)): ?>
                                <tr>
                                    <td><?php echo $review['id']; ?></td>
                                    <td><?php echo htmlspecialchars($review['name']); ?></td>
                                    <td><?php echo htmlspecialchars($review['email']); ?></td>
                                    <td><?php echo htmlspecialchars($review['subject']); ?></td>
                                    <td>
                                        <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">
                                            <?php echo htmlspecialchars(substr($review['message'], 0, 100)) . (strlen($review['message']) > 100 ? '...' : ''); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($review['rating']): ?>
                                            <span class="text-warning">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <?php if ($i <= $review['rating']): ?>
                                                        <i class="bi bi-star-fill"></i>
                                                    <?php else: ?>
                                                        <i class="bi bi-star"></i>
                                                    <?php endif; ?>
                                                <?php endfor; ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">No rating</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $badge_class = '';
                                        switch ($review['status']) {
                                            case 'pending':
                                                $badge_class = 'bg-warning';
                                                break;
                                            case 'approved':
                                                $badge_class = 'bg-success';
                                                break;
                                            case 'rejected':
                                                $badge_class = 'bg-danger';
                                                break;
                                        }
                                        ?>
                                        <span class="badge <?php echo $badge_class; ?>">
                                            <?php echo ucfirst($review['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($review['created_at'])); ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <!-- Status Update Form -->
                                            <form method="POST" class="d-inline">
                                                <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                                    <option value="pending" <?php echo $review['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                    <option value="approved" <?php echo $review['status'] == 'approved' ? 'selected' : ''; ?>>Approved</option>
                                                    <option value="rejected" <?php echo $review['status'] == 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                                                </select>
                                                <input type="hidden" name="update_status" value="1">
                                            </form>
                                            
                                            <!-- Delete Form -->
                                            <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this review?')">
                                                <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                                                <button type="submit" name="delete_review" class="btn btn-danger btn-sm">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
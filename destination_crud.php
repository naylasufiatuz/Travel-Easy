<?php
require('db_config.php');

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Handle Add New Destination
    if (isset($_POST['add_destination'])) {
        $name = mysqli_real_escape_string($con, $_POST['name']);
        $location = mysqli_real_escape_string($con, $_POST['location']);
        $description = mysqli_real_escape_string($con, $_POST['description']);
        $price = (float)$_POST['price'];
        $rating = (float)$_POST['rating'];
        $reviews = (int)$_POST['reviews'];
        $image = mysqli_real_escape_string($con, $_POST['image']);
        $amenities = mysqli_real_escape_string($con, $_POST['amenities']);
        
        $insert_query = "INSERT INTO destination (name, location, description, price, rating, reviews, image, amenities, created_at, updated_at) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
        $stmt = mysqli_prepare($con, $insert_query);
        mysqli_stmt_bind_param($stmt, "sssdsdss", $name, $location, $description, $price, $rating, $reviews, $image, $amenities);
        
        if (mysqli_stmt_execute($stmt)) {
            $success_message = "Destination added successfully!";
        } else {
            $error_message = "Error adding destination: " . mysqli_error($con);
        }
        mysqli_stmt_close($stmt);
    }
    
    // Handle Update Destination
    if (isset($_POST['update_destination'])) {
        $id = (int)$_POST['id'];
        $name = mysqli_real_escape_string($con, $_POST['name']);
        $location = mysqli_real_escape_string($con, $_POST['location']);
        $description = mysqli_real_escape_string($con, $_POST['description']);
        $price = (float)$_POST['price'];
        $rating = (float)$_POST['rating'];
        $reviews = (int)$_POST['reviews'];
        $image = mysqli_real_escape_string($con, $_POST['image']);
        $amenities = mysqli_real_escape_string($con, $_POST['amenities']);
        
        $update_query = "UPDATE destination SET name = ?, location = ?, description = ?, price = ?, rating = ?, reviews = ?, image = ?, amenities = ?, updated_at = NOW() WHERE id = ?";
        $stmt = mysqli_prepare($con, $update_query);
        mysqli_stmt_bind_param($stmt, "sssdsdsis", $name, $location, $description, $price, $rating, $reviews, $image, $amenities, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            $success_message = "Destination updated successfully!";
        } else {
            $error_message = "Error updating destination: " . mysqli_error($con);
        }
        mysqli_stmt_close($stmt);
    }
    
    // Handle Delete Destination
    if (isset($_POST['delete_destination'])) {
        $id = (int)$_POST['id'];
        
        $delete_query = "DELETE FROM destination WHERE id = ?";
        $stmt = mysqli_prepare($con, $delete_query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if (mysqli_stmt_execute($stmt)) {
            $success_message = "Destination deleted successfully!";
        } else {
            $error_message = "Error deleting destination: " . mysqli_error($con);
        }
        mysqli_stmt_close($stmt);
    }
}

// Get filter parameters
$search = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';
$min_price = isset($_GET['min_price']) ? (float)$_GET['min_price'] : 0;
$max_price = isset($_GET['max_price']) ? (float)$_GET['max_price'] : 999999999;
$min_rating = isset($_GET['min_rating']) ? (float)$_GET['min_rating'] : 0;

// Build query with filters
$where_conditions = [];
$params = [];
$types = '';

if (!empty($search)) {
    $where_conditions[] = "(name LIKE ? OR location LIKE ? OR description LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= 'sss';
}

if ($min_price > 0) {
    $where_conditions[] = "price >= ?";
    $params[] = $min_price;
    $types .= 'd';
}

if ($max_price < 999999999) {
    $where_conditions[] = "price <= ?";
    $params[] = $max_price;
    $types .= 'd';
}

if ($min_rating > 0) {
    $where_conditions[] = "rating >= ?";
    $params[] = $min_rating;
    $types .= 'd';
}

$where_clause = '';
if (!empty($where_conditions)) {
    $where_clause = "WHERE " . implode(" AND ", $where_conditions);
}

// Get destinations with filters
$destinations_query = "SELECT * FROM destination $where_clause ORDER BY created_at DESC";
if (!empty($params)) {
    $stmt = mysqli_prepare($con, $destinations_query);
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);
    $destinations_result = mysqli_stmt_get_result($stmt);
} else {
    $destinations_result = mysqli_query($con, $destinations_query);
}

// Get statistics
$stats_query = "SELECT 
    COUNT(*) as total_destinations,
    AVG(price) as avg_price,
    MIN(price) as min_price,
    MAX(price) as max_price,
    AVG(rating) as avg_rating,
    SUM(reviews) as total_reviews
    FROM destination";
$stats_result = mysqli_query($con, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);

// Get destination for editing if edit_id is provided
$edit_destination = null;
if (isset($_GET['edit_id'])) {
    $edit_id = (int)$_GET['edit_id'];
    $edit_query = "SELECT * FROM destination WHERE id = ?";
    $stmt = mysqli_prepare($con, $edit_query);
    mysqli_stmt_bind_param($stmt, "i", $edit_id);
    mysqli_stmt_execute($stmt);
    $edit_result = mysqli_stmt_get_result($stmt);
    $edit_destination = mysqli_fetch_assoc($edit_result);
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Destinations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        .card-stats {
            transition: transform 0.2s;
        }
        .card-stats:hover {
            transform: translateY(-5px);
        }
        .table-responsive {
            max-height: 600px;
            overflow-y: auto;
        }
        .destination-image {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }
        .amenities-list {
            max-width: 200px;
            word-wrap: break-word;
        }
    </style>
</head>
<body class="bg-light">

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2"><i class="bi bi-geo-alt"></i> Destinations Management</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addDestinationModal">
                            <i class="bi bi-plus-circle"></i> Add New Destination
                        </button>
                    </div>
                </div>
            </div>

            <!-- Success/Error Messages -->
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $success_message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $error_message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card card-stats bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Total Destinations</h6>
                                    <h3 class="mb-0"><?php echo $stats['total_destinations']; ?></h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-geo-alt fs-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card card-stats bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Average Price</h6>
                                    <h4 class="mb-0">Rp <?php echo number_format($stats['avg_price'], 0, ',', '.'); ?></h4>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-currency-dollar fs-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card card-stats bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Average Rating</h6>
                                    <h3 class="mb-0"><?php echo number_format($stats['avg_rating'], 1); ?> <i class="bi bi-star-fill"></i></h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-star fs-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card card-stats bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Total Reviews</h6>
                                    <h3 class="mb-0"><?php echo number_format($stats['total_reviews']); ?></h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-chat-dots fs-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Form -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Filter Destinations</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="search" class="form-label">Search</label>
                                <input type="text" class="form-control" id="search" name="search" 
                                       value="<?php echo htmlspecialchars($search); ?>" 
                                       placeholder="Search name, location, or description...">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="min_price" class="form-label">Min Price</label>
                                <input type="number" class="form-control" id="min_price" name="min_price" 
                                       value="<?php echo $min_price > 0 ? $min_price : ''; ?>" 
                                       placeholder="0">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="max_price" class="form-label">Max Price</label>
                                <input type="number" class="form-control" id="max_price" name="max_price" 
                                       value="<?php echo $max_price < 999999999 ? $max_price : ''; ?>" 
                                       placeholder="No limit">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="min_rating" class="form-label">Min Rating</label>
                                <input type="number" class="form-control" id="min_rating" name="min_rating" 
                                       value="<?php echo $min_rating > 0 ? $min_rating : ''; ?>" 
                                       step="0.1" min="0" max="5" placeholder="0">
                            </div>
                            <div class="col-md-3 mb-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="bi bi-search"></i> Filter
                                </button>
                                <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle"></i> Clear
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Destinations Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">All Destinations</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="table-dark sticky-top">
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Location</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Rating</th>
                                    <th>Reviews</th>
                                    <th>Amenities</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($destination = mysqli_fetch_assoc($destinations_result)): ?>
                                <tr>
                                    <td><?php echo $destination['id']; ?></td>
                                    <td>
                                        <?php if (!empty($destination['image'])): ?>
                                            <img src="<?php echo htmlspecialchars($destination['image']); ?>" 
                                                 alt="<?php echo htmlspecialchars($destination['name']); ?>" 
                                                 class="destination-image">
                                        <?php else: ?>
                                            <div class="destination-image bg-light d-flex align-items-center justify-content-center">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($destination['name']); ?></strong>
                                    </td>
                                    <td><?php echo htmlspecialchars($destination['location']); ?></td>
                                    <td>
                                        <div style="max-width: 200px;">
                                            <?php echo htmlspecialchars(substr($destination['description'], 0, 100)) . (strlen($destination['description']) > 100 ? '...' : ''); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>Rp <?php echo number_format($destination['price'], 0, ',', '.'); ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning text-dark">
                                            <?php echo number_format($destination['rating'], 1); ?> <i class="bi bi-star-fill"></i>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info"><?php echo number_format($destination['reviews']); ?></span>
                                    </td>
                                    <td>
                                        <div class="amenities-list">
                                            <?php 
                                            $amenities = json_decode($destination['amenities'], true);
                                            if (is_array($amenities)) {
                                                echo implode(', ', array_slice($amenities, 0, 3));
                                                if (count($amenities) > 3) {
                                                    echo '...';
                                                }
                                            } else {
                                                echo htmlspecialchars(substr($destination['amenities'], 0, 50));
                                            }
                                            ?>
                                        </div>
                                    </td>
                                    <td>
                                        <small><?php echo date('d/m/Y H:i', strtotime($destination['created_at'])); ?></small>
                                    </td>
                                    <td>
                                        <div class="btn-group-vertical" role="group">
                                            <a href="?edit_id=<?php echo $destination['id']; ?>" class="btn btn-outline-primary btn-sm mb-1">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <button type="button" class="btn btn-outline-info btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $destination['id']; ?>">
                                                <i class="bi bi-eye"></i> View
                                            </button>
                                            <form method="POST" onsubmit="return confirm('Are you sure you want to delete this destination?')">
                                                <input type="hidden" name="id" value="<?php echo $destination['id']; ?>">
                                                <button type="submit" name="delete_destination" class="btn btn-outline-danger btn-sm">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- View Modal -->
                                <div class="modal fade" id="viewModal<?php echo $destination['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Destination Details - <?php echo htmlspecialchars($destination['name']); ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <?php if (!empty($destination['image'])): ?>
                                                            <img src="<?php echo htmlspecialchars($destination['image']); ?>" 
                                                                 alt="<?php echo htmlspecialchars($destination['name']); ?>" 
                                                                 class="img-fluid rounded mb-3">
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6>Basic Information</h6>
                                                        <p><strong>Name:</strong> <?php echo htmlspecialchars($destination['name']); ?></p>
                                                        <p><strong>Location:</strong> <?php echo htmlspecialchars($destination['location']); ?></p>
                                                        <p><strong>Price:</strong> Rp <?php echo number_format($destination['price'], 0, ',', '.'); ?></p>
                                                        <p><strong>Rating:</strong> <?php echo number_format($destination['rating'], 1); ?> <i class="bi bi-star-fill text-warning"></i></p>
                                                        <p><strong>Reviews:</strong> <?php echo number_format($destination['reviews']); ?></p>
                                                    </div>
                                                </div>
                                                <hr>
                                                <h6>Description</h6>
                                                <p><?php echo nl2br(htmlspecialchars($destination['description'])); ?></p>
                                                <hr>
                                                <h6>Amenities</h6>
                                                <p><?php echo htmlspecialchars($destination['amenities']); ?></p>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p><strong>Created:</strong> <?php echo date('d F Y H:i:s', strtotime($destination['created_at'])); ?></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>Updated:</strong> <?php echo date('d F Y H:i:s', strtotime($destination['updated_at'])); ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Destination Modal -->
<div class="modal fade" id="addDestinationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Destination</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Destination Name *</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="location" class="form-label">Location *</label>
                            <input type="text" class="form-control" id="location" name="location" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description *</label>
                        <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="price" class="form-label">Price (Rp) *</label>
                            <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="rating" class="form-label">Rating *</label>
                            <input type="number" class="form-control" id="rating" name="rating" step="0.1" min="0" max="5" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="reviews" class="form-label">Reviews Count *</label>
                            <input type="number" class="form-control" id="reviews" name="reviews" min="0" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image URL</label>
                        <input type="url" class="form-control" id="image" name="image" placeholder="https://example.com/image.jpg">
                    </div>
                    <div class="mb-3">
                        <label for="amenities" class="form-label">Amenities</label>
                        <textarea class="form-control" id="amenities" name="amenities" rows="3" placeholder="Enter amenities separated by commas or as JSON array"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_destination" class="btn btn-primary">Add Destination</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Form (appears when edit_id is set) -->
<?php if ($edit_destination): ?>
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">Edit Destination: <?php echo htmlspecialchars($edit_destination['name']); ?></h5>
    </div>
    <div class="card-body">
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $edit_destination['id']; ?>">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="edit_name" class="form-label">Destination Name *</label>
                    <input type="text" class="form-control" id="edit_name" name="name" value="<?php echo htmlspecialchars($edit_destination['name']); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="edit_location" class="form-label">Location *</label>
                    <input type="text" class="form-control" id="edit_location" name="location" value="<?php echo htmlspecialchars($edit_destination['location']); ?>" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="edit_description" class="form-label">Description *</label>
                <textarea class="form-control" id="edit_description" name="description" rows="4" required><?php echo htmlspecialchars($edit_destination['description']); ?></textarea>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="edit_price" class="form-label">Price (Rp) *</label>
                    <input type="number" class="form-control" id="edit_price" name="price" step="0.01" min="0" value="<?php echo $edit_destination['price']; ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="edit_rating" class="form-label">Rating *</label>
                    <input type="number" class="form-control" id="edit_rating" name="rating" step="0.1" min="0" max="5" value="<?php echo $edit_destination['rating']; ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="edit_reviews" class="form-label">Reviews Count *</label>
                    <input type="number" class="form-control" id="edit_reviews" name="reviews" min="0" value="<?php echo $edit_destination['reviews']; ?>" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="edit_image" class="form-label">Image URL</label>
                <input type="url" class="form-control" id="edit_image" name="image" value="<?php echo htmlspecialchars($edit_destination['image']); ?>">
            </div>
            <div class="mb-3">
                <label for="edit_amenities" class="form-label">Amenities</label>
                <textarea class="form-control" id="edit_amenities" name="amenities" rows="3"><?php echo htmlspecialchars($edit_destination['amenities']); ?></textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" name="update_destination" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Update Destination
                </button>
                <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
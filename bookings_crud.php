<?php
require('db_config.php');

// Handle status updates
if (isset($_POST['update_status'])) {
    $booking_id = (int)$_POST['booking_id'];
    $new_status = mysqli_real_escape_string($con, $_POST['status']);
    
    $update_query = "UPDATE bookings SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($con, $update_query);
    mysqli_stmt_bind_param($stmt, "si", $new_status, $booking_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Handle delete
if (isset($_POST['delete_booking'])) {
    $booking_id = (int)$_POST['booking_id'];
    
    $delete_query = "DELETE FROM bookings WHERE id = ?";
    $stmt = mysqli_prepare($con, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $booking_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Get all bookings with customer and destination info
$bookings_query = "SELECT b.*, 
                   c.fullname as customer_name, c.email as customer_email,  c.password as password_phone, c.phone as customer_phone,
                   d.name as destination_name, d.location as destination_location
                   FROM bookings b 
                   LEFT JOIN customers c ON b.customer_id = c.id
                   LEFT JOIN destination d ON b.destination_id = d.id
                   ORDER BY b.created_at DESC";
$bookings_result = mysqli_query($con, $bookings_query);

// Get statistics
$stats_query = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
    SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled,
    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
    SUM(total_amount) as total_revenue,
    AVG(total_amount) as avg_booking_value
    FROM bookings";
$stats_result = mysqli_query($con, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);

// Get monthly revenue
$monthly_query = "SELECT DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total_amount) as revenue 
                  FROM bookings WHERE status = 'confirmed' OR status = 'completed'
                  GROUP BY DATE_FORMAT(created_at, '%Y-%m') 
                  ORDER BY month DESC LIMIT 6";
$monthly_result = mysqli_query($con, $monthly_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Bookings</title>
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
        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
    </style>
</head>
<body class="bg-light">

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2"><i class="bi bi-calendar-check"></i> Bookings Management</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-download"></i> Export
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-printer"></i> Print
                        </button>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <div class="card card-stats bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Total Bookings</h6>
                                    <h3 class="mb-0"><?php echo $stats['total']; ?></h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-calendar-check fs-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <div class="card card-stats bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Pending</h6>
                                    <h3 class="mb-0"><?php echo $stats['pending']; ?></h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-clock fs-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <div class="card card-stats bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Confirmed</h6>
                                    <h3 class="mb-0"><?php echo $stats['confirmed']; ?></h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-check-circle fs-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <div class="card card-stats bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Completed</h6>
                                    <h3 class="mb-0"><?php echo $stats['completed']; ?></h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-check-all fs-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <div class="card card-stats bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Cancelled</h6>
                                    <h3 class="mb-0"><?php echo $stats['cancelled']; ?></h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-x-circle fs-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <div class="card card-stats bg-dark text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Total Revenue</h6>
                                    <h4 class="mb-0">Rp <?php echo number_format($stats['total_revenue'], 0, ',', '.'); ?></h4>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-currency-dollar fs-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bookings Table -->
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">All Bookings</h5>
                        <div class="d-flex gap-2">
                            <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search bookings..." style="width: 200px;">
                            <select id="statusFilter" class="form-select form-select-sm" style="width: 150px;">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0" id="bookingsTable">
                            <thead class="table-dark sticky-top">
                                <tr>
                                    <th>ID</th>
                                    <th>Booking Code</th>
                                    <th>Customer</th>
                                    <th>Destination</th>
                                    <th>Visit Date</th>
                                    <th>Visitors</th>
                                    <th>Total Amount</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($booking = mysqli_fetch_assoc($bookings_result)): ?>
                                <tr>
                                    <td><?php echo $booking['id']; ?></td>
                                    <td>
                                        <span class="badge bg-secondary"><?php echo htmlspecialchars($booking['booking_code']); ?></span>
                                    </td>
                                    <td>
                                        <div>
                                            <strong><?php echo htmlspecialchars($booking['customer_name']); ?></strong><br>
                                            <small class="text-muted"><?php echo htmlspecialchars($booking['customer_email']); ?></small><br>
                                            <small class="text-muted"><?php echo htmlspecialchars($booking['customer_phone']); ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong><?php echo htmlspecialchars($booking['destination_name']); ?></strong><br>
                                            <small class="text-muted"><?php echo htmlspecialchars($booking['destination_location']); ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?php echo date('d/m/Y', strtotime($booking['visit_date'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary"><?php echo $booking['visitors_count']; ?> orang</span>
                                    </td>
                                    <td>
                                        <strong>Rp <?php echo number_format($booking['total_amount'], 0, ',', '.'); ?></strong><br>
                                        <small class="text-muted">
                                            Price: Rp <?php echo number_format($booking['price_per_person'], 0, ',', '.'); ?>/person<br>
                                            Admin: Rp <?php echo number_format($booking['admin_fee'], 0, ',', '.'); ?>
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo $booking['payment_method'] == 'bank' ? 'success' : 'warning'; ?>">
                                            <?php echo ucfirst($booking['payment_method']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php
                                        $badge_class = '';
                                        switch ($booking['status']) {
                                            case 'pending':
                                                $badge_class = 'bg-warning';
                                                break;
                                            case 'confirmed':
                                                $badge_class = 'bg-success';
                                                break;
                                            case 'completed':
                                                $badge_class = 'bg-info';
                                                break;
                                            case 'cancelled':
                                                $badge_class = 'bg-danger';
                                                break;
                                        }
                                        ?>
                                        <span class="badge status-badge <?php echo $badge_class; ?>">
                                            <?php echo ucfirst($booking['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small><?php echo date('d/m/Y H:i', strtotime($booking['created_at'])); ?></small>
                                    </td>
                                    <td>
                                        <div class="btn-group-vertical" role="group">
                                            <!-- Status Update Form -->
                                            <form method="POST" class="mb-1">
                                                <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                                    <option value="pending" <?php echo $booking['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                    <option value="confirmed" <?php echo $booking['status'] == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                                    <option value="completed" <?php echo $booking['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                                                    <option value="cancelled" <?php echo $booking['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                                </select>
                                                <input type="hidden" name="update_status" value="1">
                                            </form>
                                            
                                            <!-- Action Buttons -->
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal<?php echo $booking['id']; ?>">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this booking?')">
                                                    <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                                    <button type="submit" name="delete_booking" class="btn btn-outline-danger btn-sm">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Detail Modal -->
                                <div class="modal fade" id="detailModal<?php echo $booking['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Booking Details - <?php echo $booking['booking_code']; ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h6>Customer Information</h6>
                                                        <p><strong>Name:</strong> <?php echo htmlspecialchars($booking['customer_name']); ?></p>
                                                        <p><strong>Email:</strong> <?php echo htmlspecialchars($booking['customer_email']); ?></p>
                                                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($booking['customer_phone']); ?></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6>Booking Information</h6>
                                                        <p><strong>Destination:</strong> <?php echo htmlspecialchars($booking['destination_name']); ?></p>
                                                        <p><strong>Visit Date:</strong> <?php echo date('d F Y', strtotime($booking['visit_date'])); ?></p>
                                                        <p><strong>Visitors:</strong> <?php echo $booking['visitors_count']; ?> people</p>
                                                        <p><strong>Special Request:</strong> <?php echo $booking['special_request'] ? htmlspecialchars($booking['special_request']) : 'None'; ?></p>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h6>Payment Details</h6>
                                                        <p><strong>Method:</strong> <?php echo ucfirst($booking['payment_method']); ?></p>
                                                        <p><strong>Price per person:</strong> Rp <?php echo number_format($booking['price_per_person'], 0, ',', '.'); ?></p>
                                                        <p><strong>Subtotal:</strong> Rp <?php echo number_format($booking['subtotal'], 0, ',', '.'); ?></p>
                                                        <p><strong>Admin Fee:</strong> Rp <?php echo number_format($booking['admin_fee'], 0, ',', '.'); ?></p>
                                                        <p><strong>Total Amount:</strong> <span class="text-success fw-bold">Rp <?php echo number_format($booking['total_amount'], 0, ',', '.'); ?></span></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6>Timestamps</h6>
                                                        <p><strong>Created:</strong> <?php echo date('d F Y H:i:s', strtotime($booking['created_at'])); ?></p>
                                                        <p><strong>Updated:</strong> <?php echo date('d F Y H:i:s', strtotime($booking['updated_at'])); ?></p>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Search functionality
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
    const tableRows = document.querySelectorAll('#bookingsTable tbody tr');
    
    tableRows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const statusCell = row.cells[8].textContent.toLowerCase();
        
        const matchesSearch = text.includes(searchValue);
        const matchesStatus = statusFilter === '' || statusCell.includes(statusFilter);
        
        row.style.display = matchesSearch && matchesStatus ? '' : 'none';
    });
});

// Status filter functionality
document.getElementById('statusFilter').addEventListener('change', function() {
    const statusFilter = this.value.toLowerCase();
    const searchValue = document.getElementById('searchInput').value.toLowerCase();
    const tableRows = document.querySelectorAll('#bookingsTable tbody tr');
    
    tableRows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const statusCell = row.cells[8].textContent.toLowerCase();
        
        const matchesSearch = text.includes(searchValue);
        const matchesStatus = statusFilter === '' || statusCell.includes(statusFilter);
        
        row.style.display = matchesSearch && matchesStatus ? '' : 'none';
    });
});

// Auto-refresh page every 5 minutes
setInterval(function() {
    location.reload();
}, 300000);
</script>
</body>
</html>
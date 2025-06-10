<?php
require('db_config.php');

// Handle Create/Update
if (isset($_POST['save_payment'])) {
    $booking_id = (int)$_POST['booking_id'];
    $payment_method = mysqli_real_escape_string($con, $_POST['payment_method']);
    $payment_provider = mysqli_real_escape_string($con, $_POST['payment_provider']);
    $amount = (float)$_POST['amount'];
    $status = mysqli_real_escape_string($con, $_POST['status']);
    $transaction_id = mysqli_real_escape_string($con, $_POST['transaction_id']);
    $paid_at = !empty($_POST['paid_at']) ? $_POST['paid_at'] : NULL;
    
    if (isset($_POST['payment_id']) && !empty($_POST['payment_id'])) {
        // Update existing payment
        $payment_id = (int)$_POST['payment_id'];
        if ($paid_at) {
            $update_query = "UPDATE payments SET booking_id = ?, payment_method = ?, payment_provider = ?, amount = ?, status = ?, transaction_id = ?, paid_at = ? WHERE id = ?";
            $stmt = mysqli_prepare($con, $update_query);
            mysqli_stmt_bind_param($stmt, "issdsssi", $booking_id, $payment_method, $payment_provider, $amount, $status, $transaction_id, $paid_at, $payment_id);
        } else {
            $update_query = "UPDATE payments SET booking_id = ?, payment_method = ?, payment_provider = ?, amount = ?, status = ?, transaction_id = ? WHERE id = ?";
            $stmt = mysqli_prepare($con, $update_query);
            mysqli_stmt_bind_param($stmt, "issdssi", $booking_id, $payment_method, $payment_provider, $amount, $status, $transaction_id, $payment_id);
        }
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $message = "Payment updated successfully!";
        $message_type = "success";
    } else {
        // Create new payment
        if ($paid_at) {
            $insert_query = "INSERT INTO payments (booking_id, payment_method, payment_provider, amount, status, transaction_id, paid_at, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
            $stmt = mysqli_prepare($con, $insert_query);
            mysqli_stmt_bind_param($stmt, "issdsss", $booking_id, $payment_method, $payment_provider, $amount, $status, $transaction_id, $paid_at);
        } else {
            $insert_query = "INSERT INTO payments (booking_id, payment_method, payment_provider, amount, status, transaction_id, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())";
            $stmt = mysqli_prepare($con, $insert_query);
            mysqli_stmt_bind_param($stmt, "issdss", $booking_id, $payment_method, $payment_provider, $amount, $status, $transaction_id);
        }
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $message = "Payment created successfully!";
        $message_type = "success";
    }
}

// Handle Delete
if (isset($_POST['delete_payment'])) {
    $payment_id = (int)$_POST['payment_id'];
    
    $delete_query = "DELETE FROM payments WHERE id = ?";
    $stmt = mysqli_prepare($con, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $payment_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $message = "Payment deleted successfully!";
    $message_type = "danger";
}

// Handle status update
if (isset($_POST['update_status'])) {
    $payment_id = (int)$_POST['payment_id'];
    $new_status = mysqli_real_escape_string($con, $_POST['status']);
    
    $update_query = "UPDATE payments SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($con, $update_query);
    mysqli_stmt_bind_param($stmt, "si", $new_status, $payment_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $message = "Payment status updated successfully!";
    $message_type = "info";
}

// Get payment data for editing
$edit_payment = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $edit_query = "SELECT * FROM payments WHERE id = ?";
    $stmt = mysqli_prepare($con, $edit_query);
    mysqli_stmt_bind_param($stmt, "i", $edit_id);
    mysqli_stmt_execute($stmt);
    $edit_result = mysqli_stmt_get_result($stmt);
    $edit_payment = mysqli_fetch_assoc($edit_result);
    mysqli_stmt_close($stmt);
}

// Get all payments
$payments_query = "SELECT * FROM payments ORDER BY created_at DESC";
$payments_result = mysqli_query($con, $payments_query);

// Filter payments if search is performed
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($con, $_GET['search']);
    $payments_query = "SELECT * FROM payments WHERE 
                       booking_id LIKE '%$search%' OR 
                       payment_method LIKE '%$search%' OR 
                       payment_provider LIKE '%$search%' OR 
                       transaction_id LIKE '%$search%' OR 
                       status LIKE '%$search%' 
                       ORDER BY created_at DESC";
    $payments_result = mysqli_query($con, $payments_query);
}

// Get statistics
$stats_query = "SELECT 
    COUNT(*) as total_payments,
    SUM(CASE WHEN status = 'success' THEN 1 ELSE 0 END) as successful_payments,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_payments,
    SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed_payments,
    SUM(CASE WHEN status = 'success' THEN amount ELSE 0 END) as total_revenue,
    AVG(CASE WHEN status = 'success' THEN amount ELSE NULL END) as avg_payment_amount
    FROM payments";
$stats_result = mysqli_query($con, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments CRUD Management</title>
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
        .form-section {
            color: black;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .form-control:focus {
            border-color:rgb(146, 95, 68);
            
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
                <h1 class="h2"><i class="bi bi-credit-card"></i> Payments Management System</h1>
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

            <!-- Alert Messages -->
            <?php if (isset($message)): ?>
            <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                <i class="bi bi-<?php echo $message_type == 'success' ? 'check-circle' : ($message_type == 'info' ? 'info-circle' : 'exclamation-triangle'); ?>"></i>
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <div class="card card-stats bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Total Payments</h6>
                                    <h3 class="mb-0"><?php echo $stats['total_payments']; ?></h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-credit-card fs-2"></i>
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
                                    <h6 class="card-title">Successful</h6>
                                    <h3 class="mb-0"><?php echo $stats['successful_payments']; ?></h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-check-circle fs-2"></i>
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
                                    <h3 class="mb-0"><?php echo $stats['pending_payments']; ?></h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-clock fs-2"></i>
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
                                    <h6 class="card-title">Failed</h6>
                                    <h3 class="mb-0"><?php echo $stats['failed_payments']; ?></h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-x-circle fs-2"></i>
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
                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <div class="card card-stats bg-dark text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Avg Payment</h6>
                                    <h5 class="mb-0">Rp <?php echo number_format($stats['avg_payment_amount'], 0, ',', '.'); ?></h5>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-graph-up fs-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <div class="form-section">
                <h4 class="mb-3">
                    <i class="bi bi-<?php echo $edit_payment ? 'pencil-square' : 'plus-circle'; ?>"></i>
                    <?php echo $edit_payment ? 'Edit Payment' : 'Add New Payment'; ?>
                </h4>
                <form method="POST" action="">
                    <?php if ($edit_payment): ?>
                        <input type="hidden" name="payment_id" value="<?php echo $edit_payment['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="booking_id" class="form-label">Booking ID</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-hash"></i></span>
                                <input type="number" class="form-control" id="booking_id" name="booking_id" 
                                       value="<?php echo $edit_payment ? $edit_payment['booking_id'] : ''; ?>" 
                                       required placeholder="Enter booking ID">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-credit-card"></i></span>
                                <select class="form-select" id="payment_method" name="payment_method" required>
                                    <option value="">Select Payment Method</option>
                                    <option value="bank" <?php echo ($edit_payment && $edit_payment['payment_method'] == 'bank') ? 'selected' : ''; ?>>Bank Transfer</option>
                                    <option value="ewallet" <?php echo ($edit_payment && $edit_payment['payment_method'] == 'ewallet') ? 'selected' : ''; ?>>E-Wallet</option>
                                    <option value="credit_card" <?php echo ($edit_payment && $edit_payment['payment_method'] == 'credit_card') ? 'selected' : ''; ?>>Credit Card</option>
                                    <option value="cash" <?php echo ($edit_payment && $edit_payment['payment_method'] == 'cash') ? 'selected' : ''; ?>>Cash</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="payment_provider" class="form-label">Payment Provider</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-building"></i></span>
                                <input type="text" class="form-control" id="payment_provider" name="payment_provider" 
                                       value="<?php echo $edit_payment ? htmlspecialchars($edit_payment['payment_provider']) : ''; ?>" 
                                       required placeholder="e.g., BCA, GoPay, Mandiri">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" step="0.01" class="form-control" id="amount" name="amount" 
                                       value="<?php echo $edit_payment ? $edit_payment['amount'] : ''; ?>" 
                                       required placeholder="0.00">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-check-circle"></i></span>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="pending" <?php echo ($edit_payment && $edit_payment['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                    <option value="success" <?php echo ($edit_payment && $edit_payment['status'] == 'success') ? 'selected' : ''; ?>>Success</option>
                                    <option value="failed" <?php echo ($edit_payment && $edit_payment['status'] == 'failed') ? 'selected' : ''; ?>>Failed</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="transaction_id" class="form-label">Transaction ID</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-receipt"></i></span>
                                <input type="text" class="form-control" id="transaction_id" name="transaction_id" 
                                       value="<?php echo $edit_payment ? htmlspecialchars($edit_payment['transaction_id']) : ''; ?>" 
                                       required placeholder="e.g., TXN001">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="paid_at" class="form-label">Paid At (Optional)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                <input type="datetime-local" class="form-control" id="paid_at" name="paid_at" 
                                       value="<?php echo $edit_payment && $edit_payment['paid_at'] ? date('Y-m-d\TH:i', strtotime($edit_payment['paid_at'])) : ''; ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" name="save_payment" class="btn btn-light">
                            <i class="bi bi-save"></i> <?php echo $edit_payment ? 'Update Payment' : 'Save Payment'; ?>
                        </button>
                        <?php if ($edit_payment): ?>
                        <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-outline-light">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Payments Table -->
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-table"></i> All Payments</h5>
                        <div class="d-flex gap-2">
                            <form method="GET" action="" class="d-flex">
                                <input type="text" name="search" class="form-control form-control-sm" 
                                       placeholder="Search payments..." style="width: 200px;"
                                       value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                <button type="submit" class="btn btn-outline-primary btn-sm ms-2">
                                    <i class="bi bi-search"></i>
                                </button>
                                <?php if (isset($_GET['search'])): ?>
                                <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-outline-secondary btn-sm ms-1">
                                    <i class="bi bi-x"></i>
                                </a>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="table-dark sticky-top">
                                <tr>
                                    <th>ID</th>
                                    <th>Booking ID</th>
                                    <th>Payment Method</th>
                                    <th>Provider</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Transaction ID</th>
                                    <th>Paid At</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if (mysqli_num_rows($payments_result) > 0):
                                    while ($payment = mysqli_fetch_assoc($payments_result)): 
                                ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-primary"><?php echo $payment['id']; ?></span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary"><?php echo $payment['booking_id']; ?></span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo $payment['payment_method'] == 'bank' ? 'success' : ($payment['payment_method'] == 'ewallet' ? 'info' : 'warning'); ?>">
                                            <?php echo ucfirst(str_replace('_', ' ', $payment['payment_method'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($payment['payment_provider']); ?></strong>
                                    </td>
                                    <td>
                                        <strong>Rp <?php echo number_format($payment['amount'], 0, ',', '.'); ?></strong>
                                    </td>
                                    <td>
                                        <?php
                                        $badge_class = '';
                                        switch ($payment['status']) {
                                            case 'pending':
                                                $badge_class = 'bg-warning';
                                                break;
                                            case 'success':
                                                $badge_class = 'bg-success';
                                                break;
                                            case 'failed':
                                                $badge_class = 'bg-danger';
                                                break;
                                        }
                                        ?>
                                        <span class="badge status-badge <?php echo $badge_class; ?>">
                                            <?php echo ucfirst($payment['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info"><?php echo htmlspecialchars($payment['transaction_id']); ?></span>
                                    </td>
                                    <td>
                                        <?php if ($payment['paid_at']): ?>
                                        <small><?php echo date('d/m/Y H:i', strtotime($payment['paid_at'])); ?></small>
                                        <?php else: ?>
                                        <small class="text-muted">Not paid</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <small><?php echo date('d/m/Y H:i', strtotime($payment['created_at'])); ?></small>
                                    </td>
                                    <td>
                                        <div class="btn-group-vertical" role="group">
                                            <!-- Quick Status Update -->
                                            <form method="POST" class="mb-1">
                                                <input type="hidden" name="payment_id" value="<?php echo $payment['id']; ?>">
                                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                                    <option value="pending" <?php echo $payment['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                    <option value="success" <?php echo $payment['status'] == 'success' ? 'selected' : ''; ?>>Success</option>
                                                    <option value="failed" <?php echo $payment['status'] == 'failed' ? 'selected' : ''; ?>>Failed</option>
                                                </select>
                                                <input type="hidden" name="update_status" value="1">
                                            </form>
                                            
                                            <!-- Action Buttons -->
                                            <div class="btn-group" role="group">
                                                <a href="?edit=<?php echo $payment['id']; ?>" class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form method="POST" class="d-inline" 
                                                      onsubmit="return confirm('Are you sure you want to delete payment ID: <?php echo $payment['id']; ?>?')">
                                                    <input type="hidden" name="payment_id" value="<?php echo $payment['id']; ?>">
                                                    <button type="submit" name="delete_payment" class="btn btn-outline-danger btn-sm">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php 
                                    endwhile;
                                else: 
                                ?>
                                <tr>
                                    <td colspan="10" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox fs-1"></i>
                                            <p class="mt-2">No payments found</p>
                                            <?php if (isset($_GET['search'])): ?>
                                            <p>Try adjusting your search criteria</p>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endif; ?>
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
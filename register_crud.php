<?php
require('db_config.php');

// Handle user registration
if (isset($_POST['register_user'])) {
    $fullname = mysqli_real_escape_string($con, $_POST['fullname']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $status = 'active';
    
    // Check if email already exists
    $check_email = "SELECT id FROM customers WHERE email = ?";
    $stmt = mysqli_prepare($con, $check_email);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        $error_message = "Email already exists!";
    } else {
        $insert_query = "INSERT INTO customers (fullname, email, password, phone, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())";
        $stmt = mysqli_prepare($con, $insert_query);
        mysqli_stmt_bind_param($stmt, "sssss", $fullname, $email, $password, $phone, $status);
        
        if (mysqli_stmt_execute($stmt)) {
            $success_message = "User registered successfully!";
        } else {
            $error_message = "Registration failed!";
        }
    }
    mysqli_stmt_close($stmt);
}

// Handle user login
if (isset($_POST['login_user'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = $_POST['password'];
    
    $login_query = "SELECT id, fullname, email, password, phone, status, login_count FROM customers WHERE email = ?";
    $stmt = mysqli_prepare($con, $login_query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($user = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $user['password'])) {
            if ($user['status'] == 'active') {
                // Update login count and last login
                $new_login_count = $user['login_count'] + 1;
                $update_login = "UPDATE customers SET login_count = ?, last_login = NOW(), updated_at = NOW() WHERE id = ?";
                $update_stmt = mysqli_prepare($con, $update_login);
                mysqli_stmt_bind_param($update_stmt, "ii", $new_login_count, $user['id']);
                mysqli_stmt_execute($update_stmt);
                mysqli_stmt_close($update_stmt);
                
                $login_success = "Login successful! Welcome " . $user['fullname'];
                $logged_user = $user;
            } else {
                $login_error = "Account is " . $user['status'] . ". Please contact admin.";
            }
        } else {
            $login_error = "Invalid email or password!";
        }
    } else {
        $login_error = "Invalid email or password!";
    }
    mysqli_stmt_close($stmt);
}

// Handle status update
if (isset($_POST['update_status'])) {
    $user_id = (int)$_POST['user_id'];
    $new_status = mysqli_real_escape_string($con, $_POST['status']);
    
    $update_query = "UPDATE customers SET status = ?, updated_at = NOW() WHERE id = ?";
    $stmt = mysqli_prepare($con, $update_query);
    mysqli_stmt_bind_param($stmt, "si", $new_status, $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Handle delete user
if (isset($_POST['delete_user'])) {
    $user_id = (int)$_POST['user_id'];
    
    $delete_query = "DELETE FROM customers WHERE id = ?";
    $stmt = mysqli_prepare($con, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Handle edit user
if (isset($_POST['edit_user'])) {
    $user_id = (int)$_POST['user_id'];
    $fullname = mysqli_real_escape_string($con, $_POST['fullname']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    
    $update_query = "UPDATE customers SET fullname = ?, email = ?, phone = ?, updated_at = NOW() WHERE id = ?";
    $stmt = mysqli_prepare($con, $update_query);
    mysqli_stmt_bind_param($stmt, "sssi", $fullname, $email, $phone, $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    $edit_success = "User updated successfully!";
}

// Get all customers
$customers_query = "SELECT * FROM customers ORDER BY created_at DESC";
$customers_result = mysqli_query($con, $customers_query);

// Get statistics
$stats_query = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
    SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) as inactive,
    SUM(CASE WHEN status = 'suspended' THEN 1 ELSE 0 END) as suspended,
    SUM(login_count) as total_logins,
    AVG(login_count) as avg_logins
    FROM customers";
$stats_result = mysqli_query($con, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration & Management System</title>
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
        .login-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .register-section {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body class="bg-light">

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><i class="bi bi-people"></i> User Management System</a>
        <div class="navbar-nav ms-auto">
            <button class="btn btn-outline-light me-2" data-bs-toggle="modal" data-bs-target="#loginModal">
                <i class="bi bi-box-arrow-in-right"></i> Login
            </button>
            <button class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#registerModal">
                <i class="bi bi-person-plus"></i> Register
            </button>
        </div>
    </div>
</nav>

<div class="container-fluid mt-3">
    <!-- Success/Error Messages -->
    <?php if (isset($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> <?php echo $success_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> <?php echo $error_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($login_success)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> <?php echo $login_success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($login_error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> <?php echo $login_error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($edit_success)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> <?php echo $edit_success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2"><i class="bi bi-people"></i> Customer Management</h1>
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
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card card-stats bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Users</h6>
                            <h3 class="mb-0"><?php echo $stats['total']; ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-people fs-2"></i>
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
                            <h6 class="card-title">Active Users</h6>
                            <h3 class="mb-0"><?php echo $stats['active']; ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-person-check fs-2"></i>
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
                            <h6 class="card-title">Inactive Users</h6>
                            <h3 class="mb-0"><?php echo $stats['inactive']; ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-person-x fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card card-stats bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Suspended</h6>
                            <h3 class="mb-0"><?php echo $stats['suspended']; ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-person-slash fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">All Customers</h5>
                <div class="d-flex gap-2">
                    <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search users..." style="width: 200px;">
                    <select id="statusFilter" class="form-select form-select-sm" style="width: 150px;">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="suspended">Suspended</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0" id="usersTable">
                    <thead class="table-dark sticky-top">
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Login Count</th>
                            <th>Last Login</th>
                            <th>Created</th>
                            <th>Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($customer = mysqli_fetch_assoc($customers_result)): ?>
                        <tr>
                            <td><?php echo $customer['id']; ?></td>
                            <td>
                                <div>
                                    <strong><?php echo htmlspecialchars($customer['fullname']); ?></strong>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($customer['email']); ?></td>
                            <td><?php echo htmlspecialchars($customer['phone']); ?></td>
                            <td>
                                <?php
                                $badge_class = '';
                                switch ($customer['status']) {
                                    case 'active':
                                        $badge_class = 'bg-success';
                                        break;
                                    case 'inactive':
                                        $badge_class = 'bg-warning';
                                        break;
                                    case 'suspended':
                                        $badge_class = 'bg-danger';
                                        break;
                                }
                                ?>
                                <span class="badge status-badge <?php echo $badge_class; ?>">
                                    <?php echo ucfirst($customer['status']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-info"><?php echo $customer['login_count'] ?? 0; ?></span>
                            </td>
                            <td>
                                <small><?php echo $customer['last_login'] ? date('d/m/Y H:i', strtotime($customer['last_login'])) : 'Never'; ?></small>
                            </td>
                            <td>
                                <small><?php echo date('d/m/Y H:i', strtotime($customer['created_at'])); ?></small>
                            </td>
                            <td>
                                <small><?php echo date('d/m/Y H:i', strtotime($customer['updated_at'])); ?></small>
                            </td>
                            <td>
                                <div class="btn-group-vertical" role="group">
                                    <!-- Status Update Form -->
                                    <form method="POST" class="mb-1">
                                        <input type="hidden" name="user_id" value="<?php echo $customer['id']; ?>">
                                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                            <option value="active" <?php echo $customer['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                                            <option value="inactive" <?php echo $customer['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                            <option value="suspended" <?php echo $customer['status'] == 'suspended' ? 'selected' : ''; ?>>Suspended</option>
                                        </select>
                                        <input type="hidden" name="update_status" value="1">
                                    </form>
                                    
                                    <!-- Action Buttons -->
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $customer['id']; ?>">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                            <input type="hidden" name="user_id" value="<?php echo $customer['id']; ?>">
                                            <button type="submit" name="delete_user" class="btn btn-outline-danger btn-sm">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal<?php echo $customer['id']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit User - <?php echo htmlspecialchars($customer['fullname']); ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST">
                                        <div class="modal-body">
                                            <input type="hidden" name="user_id" value="<?php echo $customer['id']; ?>">
                                            <div class="mb-3">
                                                <label class="form-label">Full Name</label>
                                                <input type="text" class="form-control" name="fullname" value="<?php echo htmlspecialchars($customer['fullname']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($customer['email']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Phone</label>
                                                <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($customer['phone']); ?>" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" name="edit_user" class="btn btn-primary">Update User</button>
                                        </div>
                                    </form>
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

<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-box-arrow-in-right"></i> User Login</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="login_user" class="btn btn-primary">Login</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Register Modal -->
<div class="modal fade" id="registerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-person-plus"></i> User Registration</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="fullname" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" required minlength="6">
                        <div class="form-text">Password must be at least 6 characters long.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" class="form-control" name="phone" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="register_user" class="btn btn-success">Register</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Search functionality
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
    const tableRows = document.querySelectorAll('#usersTable tbody tr');
    
    tableRows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const statusCell = row.cells[4].textContent.toLowerCase();
        
        const matchesSearch = text.includes(searchValue);
        const matchesStatus = statusFilter === '' || statusCell.includes(statusFilter);
        
        row.style.display = matchesSearch && matchesStatus ? '' : 'none';
    });
});

// Status filter functionality
document.getElementById('statusFilter').addEventListener('change', function() {
    const statusFilter = this.value.toLowerCase();
    const searchValue = document.getElementById('searchInput').value.toLowerCase();
    const tableRows = document.querySelectorAll('#usersTable tbody tr');
    
    tableRows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const statusCell = row.cells[4].textContent.toLowerCase();
        
        const matchesSearch = text.includes(searchValue);
        const matchesStatus = statusFilter === '' || statusCell.includes(statusFilter);
        
        row.style.display = matchesSearch && matchesStatus ? '' : 'none';
    });
});

// Auto-refresh page every 5 minutes
setInterval(function() {
    location.reload();
}, 300000);

// Show success message if user logged in
<?php if (isset($logged_user)): ?>
    alert('Welcome <?php echo addslashes($logged_user['fullname']); ?>! You have successfully logged in.');
<?php endif; ?>
</script>
</body>
</html>
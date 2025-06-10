<?php
    require('essentials.php');
    adminLogin();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Users</title>
    <?php require('links.php'); ?>
    <style>
        body {
            margin: 0;
            padding: 0;
        }
        
        .sticky-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            height: 70px;
        }
        
        #dashboard-menu {
            position: fixed;
            top: 70px; /* Height of header */
            left: 0;
            width: 16.66667%; /* col-lg-2 width */
            height: calc(100vh - 70px);
            z-index: 1020;
            overflow-y: auto;
        }
        
        #main-content {
            margin-left: 16.66667%; /* Same as sidebar width */
            margin-top: 70px; /* Height of header */
            min-height: calc(100vh - 70px);
        }
        
        @media (max-width: 991.98px) {
            .sticky-header {
                position: relative;
                height: auto;
            }
            
            #dashboard-menu {
                position: relative;
                top: 0;
                width: 100%;
                height: auto;
            }
            
            #main-content {
                margin-left: 0;
                margin-top: 0;
                min-height: auto;
            }
        }
        
        /* Ensure smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        /* Prevent horizontal scroll */
        body {
            overflow-x: hidden;
        }
    </style>
</head>
<body class="bg-light">

    <?php require('header.php');?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-12 p-4 overflow-hidden">
                <h2 class="mb-4">Users</h2>
                <?php 
                    // Check if bookings_crud.php exists before including
                    if(file_exists('register_crud.php')) {
                        require('register_crud.php');
                    } else {
                        echo '<div class="alert alert-info">Welcome to Crud User</div>';
                    }
                ?>
            </div> 
        </div>
    </div>

    <?php require('scripts.php');?>
</body>
</html>
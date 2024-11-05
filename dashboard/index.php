<?php

require '../server.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Attendance System</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="icon" href="../logo.png">
</head>
<body>
    <div id="dashboardPreloader" style="display: none;">
        <div class="loader"></div>
    </div>
    
    <?php require '../sidebar/sidebar.php'; ?>
    <!-- Main Content -->
    <div id="main-content">
        <!-- Top Navigation Bar -->
        <header>
            <div class="top-nav">
                <div class="top-nav-left">
                    <h1>Welcome, Admin</h1>
                </div>
                <div class="top-nav-right">
                    <button id="logout-btn">Logout</button>
                </div>
            </div>
        </header>

        <!-- Dashboard Body -->
        <main>
            <div class="dashboard-widgets">
                <div class="widget">
                    <div class="widget-header">
                        <h3>Total Members</h3>
                    </div>
                    <div class="widget-body">
                    <?php
                        $total = "SELECT * FROM members WHERE deleted LIKE 'No';";
                        $stmt = $conn->prepare($total);
                        $stmt->execute();
                        $show = $stmt->get_result();
                        $members = $show->num_rows;
                        
                    ?>
                        <p><?php echo $members;?></p>
                    </div>
                </div>

                <div class="widget">
                    <div class="widget-header">
                        <h3>Men</h3>
                    </div>
                    <div class="widget-body">
                    <?php
                        $male = "SELECT sex FROM members WHERE sex LIKE 'Male' AND deleted LIKE 'No';";
                        $stmt = $conn->prepare($male);
                        $stmt->execute();
                        $showmale = $stmt->get_result();
                        $malemembers = $showmale->num_rows;
                        
                    ?>
                        <p><?php echo $malemembers; ?></p>
                    </div>
                </div>

                <div class="widget">
                    <div class="widget-header">
                        <h3>Women</h3>
                    </div>
                    <div class="widget-body">
                    <?php
                        $male = "SELECT sex FROM members WHERE sex LIKE 'Female' AND deleted LIKE 'No';";
                        $stmt = $conn->prepare($male);
                        $stmt->execute();
                        $showfemale = $stmt->get_result();
                        $femalemembers = $showfemale->num_rows;
                        
                    ?>
                        <p><?php echo $femalemembers;?></p>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2>Recent Activity</h2>
                <p>Here you can add more content or charts related to the activity, attendance, or other system data.</p>
            </div>
            <?php 
                /*
                    <div class="content-section">
                    <h2>Recent Activity</h2>
                    <p>Here you can add more content or charts related to the activity, attendance, or other system data.</p>
                </div>
                */
            ?>
        </main>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Add JavaScript if needed for interactivity (like sidebar toggling, etc.)
        });
        $(document).ready(function(){
            $('#dashboardPreloader').show();
            $.ajax({
                
                success:function(){
                    setTimeout(function(){
                        $('#dashboardPreloader').hide();
                    }, 2000);
                }
            });
        });
    </script>
</body>
</html>

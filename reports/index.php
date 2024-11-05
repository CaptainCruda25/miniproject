<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="icon" href="../logo.png">
</head>
<body>
    
    <?php require '../sidebar/sidebar.php'; ?>
    <!-- Main Content -->
    <div id="main-content">
        <!-- Top Navigation Bar -->
        <header>
            <div class="top-nav">
                <div class="top-nav-left">
                    <h1>Welcome, User</h1>
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
                        <p>500</p>
                    </div>
                </div>

                <div class="widget">
                    <div class="widget-header">
                        <h3>Men</h3>
                    </div>
                    <div class="widget-body">
                        <p>10</p>
                    </div>
                </div>

                <div class="widget">
                    <div class="widget-header">
                        <h3>Women</h3>
                    </div>
                    <div class="widget-body">
                        <p>50</p>
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
    </script>
</body>
</html>

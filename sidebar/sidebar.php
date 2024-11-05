<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../logo.png">
    <link rel="stylesheet" href="../css/sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Document</title>
</head>
<body>
  <!-- sidebar.html -->
<div id="sidebar">
    <div class="sidebar-header">
        <img src="../logo.png" alt="Logo" class="sidebar-logo">
        <h2>Dashboard</h2>
    </div>
    <ul class="sidebar-nav">
        <li><a href="../dashboard/" class="active"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="../attendance/"><i class="fa fa-calendar"></i> Attendance</a></li>
        <li><a href="../members/"><i class="fa fa-user-circle"></i> Members</a></li>
        <li><a href="../reports/"><i class="fas fa-file-alt"></i> Reports</a></li>
        <li><a href="../settings/"><i class="fa fa-cogs"></i> Settings</a></li>
        <li><a href="../logout/"><i class="fa fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const links = document.querySelectorAll('.sidebar-nav a');

        links.forEach(link => {
            // Check if the current URL matches the link's href
            if (link.href === window.location.href) {
                link.classList.add('active'); // Add active class if it matches
            }
            link.addEventListener('click', function() {
                // Remove active class from all links
                links.forEach(link => link.classList.remove('active'));
                // Add active class to the clicked link
                this.classList.add('active');
            });
        });
    });
</script>

</body>
</html>

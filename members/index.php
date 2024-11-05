<?php

require '../server.php';


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members</title>
    <link rel="stylesheet" href="../css/members.css">
</head>
<body>
<?php require '../sidebar/sidebar.php'; ?>
    <div class="flex-container">
        <div class="container">
            <h2>Members Records</h2>
            <button id="openModal">Add Member</button>
            <div class="search-container">
                <input type="text" id="searchInput" placeholder="Search Members..." />
                <div class="filter-container">
                    <label for="groupFilter">Filter by Group:</label>
                    <select id="groupFilter">
                        <option value="">All Groups</option>
                        <option value="Father">Father</option>
                        <option value="College">College</option>
                        <!-- Add more options as needed -->
                    </select>
                </div>
            </div>
            <table id="membersTB">
                <thead>
                    <tr>
                        <th>Member ID</th>
                        <th>Member Name</th>
                        <th>Sex</th>
                        <th>Group</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="members">
                    <!-- Attendance records will be inserted here -->
                <?php
                    $fetch = "SELECT * FROM `members` 
                            INNER JOIN cell_group ON members.cell_group_id = cell_group.cell_group_id WHERE deleted LIKE 'No' ORDER BY memberID DESC;";
                    $stmt = $conn->prepare($fetch);
                    $stmt->execute();

                    $show = $stmt->get_result();

                    while($row = $show->fetch_assoc()){
                        $memberID = $row['memberID'];
                        $fname = $row['fname'];
                        $mname = $row['mname'];
                        $lname = $row['lname'];
                        $sex = $row['sex'];
                        $group = $row['cell_group_name'];
                ?>    
                    <tr>
                        <td><?php echo $memberID;?></td>
                        <td><?php echo $lname .", ".$fname. " ". $mname[0]. ".";?></td>
                        <td><?php echo $sex;?></td>
                        <td><?php echo $group;?></td>
                        <td>
                            <button type="button" class="view" value="<?php echo $memberID; ?>"><i class="fas fa-eye"></i></button>
                            <button type="button" class="update" value="<?php echo $memberID; ?>"><i class="fas fa-edit"></i></button>
                            <button type="button" class="delete" value="<?php echo $memberID; ?>"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                <?php
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal for Adding Member Record -->
    <div id="Modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Add Member</h2>
            <form id="Form">
                <div class="form-group">
                    <label for="fname">First Name:</label>
                    <input type="text" id="fname" name="fname">
                </div>
                <div class="form-group">
                    <label for="mname">Middle Name:</label>
                    <input type="text" id="mname" name="mname">
                </div>
                <div class="form-group">
                    <label for="lname">Last Name:</label>
                    <input type="text" id="lname" name="lname">
                </div>
                <div class="form-group">
                    <label for="bday">Birtday:</label>
                    <input type="date" id="bday" name="bday">
                </div>
                <div class="form-group">
                    <label for="sex">Sex:</label>
                    <select id="sex" name="sex">
                        <option disabled selected>Select Sex</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="Group">Group:</label>
                    <select id="Group" name="group">
                        <option disabled selected>Select Group</option>

                    <?php
                        // Getting the group data from the database 
                       $group = "SELECT * FROM cell_group;";
                       $stmt = $conn->prepare($group);
                       $stmt->execute();

                       $get = $stmt->get_result();
                       while($row = $get->fetch_assoc()){
                            $groupID = $row['cell_group_id'];
                            $groupname = $row['cell_group_name'];
                    ?>        
                        <option value="<?php echo $groupID; ?>"><?php echo $groupname; ?></option>
                    <?php    
                       }
                    ?> 
                    </select>
                </div>
                <button type="submit" class="submit-btn">Add Record</button>
            </form>
        </div>
    </div>

    <!-- Modal For Viewing Members Information -->
    <div id="viewModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Member Info</h2>
            <form id="Form">
                <div class="form-group">
                    <label for="fname">Full Name:</label>
                    <span class="fullname"></span>
                </div>
                <div class="form-group">
                    <label for="bday">Birtday:</label>
                    <span class="bday"></span>
                </div>
                <div class="form-group">
                    <label for="sex">Sex:</label>
                    <span class="sex"></span>
                </div>
                <div class="form-group">
                    <label for="group">Group:</label>
                    <span class="group"></span>
                </div>
            </form>
        </div>
    </div>
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/alertify.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/alertify.min.css"/>
    <!-- Semantic UI theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/themes/semantic.rtl.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Open the modal
            $('#openModal').click(function() {
                $('#Modal').show();
            });

            // Close the modal when the close button is clicked
            $('.close').click(function() {
                $('#Modal').hide();
                $('#Form')[0].reset();
            });

            // Close the modal when clicking outside of the modal content
            $(window).click(function(event) {
                if ($(event.target).is('#Modal')) {
                    $('#Modal').hide();
                    $('#Form')[0].reset();
                }
            });
        });

        // add member function
        $(document).on('submit', '#Form', function(e){
            e.preventDefault();

            let add = new FormData(this);
            add.append('add', true);

            $.ajax({
                url: '../function.php',
                method: 'POST',
                data: add,
                processData: false,
                contentType: false,
                success:function(response){
                    let res = jQuery.parseJSON(response);

                    switch(res.status){
                        case 422:
                            alertify.set('notifier','position', 'top-center');
                            alertify.error(res.message);
                            break;
                        case 200: 
                            alertify.set('notifier','position', 'top-right');
                            alertify.success(res.message);
                            $('#Form')[0].reset();
                            $('#Modal').hide();
                            $('#membersTB').load(location.href + " #membersTB");
                            break;
                        case 500:
                            alertify.set('notifier','position', 'top-center');
                            alertify.error(res.message);
                            break;
                    }

                }
            });
        });

        // Search Function

        $(document).on('keyup', '#searchInput', function(){
            let search = $(this).val();

            $.ajax({
                url: '../search.php',
                method: 'POST',
                data: {search:search},
                success:function(data){
                    $('#members').html(data);
                }
            });
        });

        // view info function
        $(document).on('click', '.view', function(){
            let info = $(this).val();
            
            $.ajax({
                url: '../function.php',
                method: 'POST',
                data: {view: info},
                success: function(response){
                    let res = jQuery.parseJSON(response);

                    switch(res.status){
                        case 422:
                            alertify.set('notifier','position', 'top-center');
                            alertify.error(res.message);
                            break;
                        case 200:
                            $('#viewModal').show();

                            // Full name Format
                            let fullname = res.data.lname + ", " + res.data.fname + " " + res.data.mname[0] + ".";
                            
                            // Bday Format

                            let bday = new Date(res.data.bday);
                            let format = {month: 'long', day: 'numeric', year: 'numeric'};
                            let date = bday.toLocaleDateString('en-US', format);

                            // Show Data in Modal
                            $('.fullname').html(fullname);
                            $('.bday').html(date);
                            $('.sex').html(res.data.sex);
                            $('.group').html(res.data.cell_group_name);
                            break;
                        case 500:
                            alertify.set('notifier','position', 'top-center');
                            alertify.error(res.message);
                            break; 
                    }
                }
            });

            $(document).on('click', '.close', function(){
                $('#viewModal').hide();
                $('#Form')[0].reset();
            });

            $(window).on('click', function(event){
                if($(event.target).is('#viewModal')){
                    $('#viewModal').hide();
                    $('#Form')[0].reset();
                }
            });
        });

        // update info function

        // delete info function




    </script>
</body>
</html>

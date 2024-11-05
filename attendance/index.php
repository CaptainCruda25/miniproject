<?php

require '../server.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Dashboard</title>
    <link rel="stylesheet" href="../css/attendance.css">
</head>
<body>

    <div class="flex-container">
        <?php require '../sidebar/sidebar.php'; ?>
        
        <div class="container">
            <div class="dashboard-widgets">
                <div class="widget" id="card1">
                    <div class="widget-header">
                        <h3>Total Attendance</h3>
                    </div>
                    <div class="widget-body">
                        <p>500</p>
                    </div>
                </div>

                <div class="widget" id="card2">
                    <div class="widget-header">
                        <h3>Total Male</h3>
                    </div>
                    <div class="widget-body">
                        <p>10</p>
                    </div>
                </div>

                <div class="widget" id="card3">
                    <div class="widget-header">
                        <h3>Total Female</h3>
                    </div>
                    <div class="widget-body">
                        <p>50</p>
                    </div>
                </div>
                
            </div>
            <h2>Attendance Records</h2>
            <button id="openModal">Add Attendance Record</button>
            <table id="attendanceTB">
                <thead>
                    <tr>
                        <th>Member Name</th>
                        <th>Date</th>
                        <th>Day</th>
                        <th>Group</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="attendanceTable">
                    <!-- Attendance records will be inserted here -->
                    <!-- <tr>
                        <td>Ysrael</td>
                        <td>10-21-2024</td>
                        <td>Wednesday</td>
                        <td>College</td>
                        <td>Present</td>
                    </tr> -->

                    <?php
                        $data = "SELECT * FROM attendance_records INNER JOIN members ON attendance_records.memberID = members.memberID 
                                INNER JOIN cell_group ON cell_group.cell_group_id = members.cell_group_id /*WHERE date = ?*/ ORDER BY attendanceID DESC;";
                        $stmt = $conn->prepare($data);
                        
                        if(!$stmt){
                    ?>
                            <tr>
                                <td colspan="5"><h3>Connection Error!</h3></td>
                            </tr>
                    <?php
                        }
                        else{
                            // $presentdate = date('Y-m-d');
                            // $stmt->bind_param('s', $presentdate);
                            if($stmt->execute()){
                                $result = $stmt->get_result();
                                
                                if($result->num_rows > 0 ){
                                    while($row = $result->fetch_assoc()){
                                        $fname = $row['fname'];
                                        $mname = $row['mname'];
                                        $lname = $row['lname'];
                                        $fullname = $lname. ", " . $fname . " " . $mname[0] . ".";
                                        $datestring = new Datetime($row['date']);
                                        $date = $datestring->format('m-d-Y');
                                        $day = $row['day'];
                                        $group = $row['cell_group_name'];
                                        $status = $row['status'];
                        ?>
                                        <tr>
                                            <td><?php echo $fullname; ?></td>
                                            <td><?php echo $date; ?></td>
                                            <td><?php echo $day; ?></td>
                                            <td><?php echo $group; ?></td>
                                            <td><?php echo $status; ?></td>
                                        </tr>
                        <?php
                                    }
                                }
                                else {
                                    
                        ?>
                                    <tr>
                                        <td colspan="5"><h3>No Data Found....</h3></td>
                                    </tr>
                        <?php
                                }
                            }
                        }                        
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal for Adding Attendance Record -->
    <div id="attendanceModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Add Attendance Record</h2>
            <form id="attendanceForm">
                <div class="form-group">
                    <label for="memberName">Member Name:</label>
                    <select id="membername" name="membername">
                        <option disabled selected>Select Member</option>
                 <?php
                     $members = "SELECT * FROM `members` WHERE deleted LIKE 'No' ORDER BY memberID DESC;";
                     $stmt =$conn->prepare($members);
                     if($stmt){
                        $stmt->execute();
                        $results = $stmt->get_result();

                        while($row = $results->fetch_assoc()){
                            $memberID = $row['memberID'];
                            $fname = $row['fname'];
                            $mname = $row['mname'];
                            $lname = $row['lname'];
                            $fullname = $lname. ", " . $fname . " " . $mname[0]. "."; 

                 ?>
                        <option value="<?php echo $memberID; ?>"><?php echo $fullname; ?></option>
                 <?php
                        }   
                     }
                     else{
                 ?>
                        <option disabled selected><?php echo "ERROR OCCURED!"; ?></option>
                 <?php       
                     }
                     $stmt->close();
                 ?>  
                    </select>
                </div>
                <div class="form-group">
                    <label for="attendanceDate">Day:</label>
                    <input type="text" id="attendanceDay" name="day" readonly>
                </div>
                <div class="form-group">
                    <label for="attendanceDate">Date:</label>
                    <input type="date" id="attendanceDate" name="attendanceDate" readonly>
                </div>
                <div class="form-group">
                    <label for="attendanceDate">Year:</label>
                    <input type="text" id="attendanceYear" name="year" readonly>
                </div>
                <div class="form-group">
                    <label for="attendanceStatus">Status:</label>
                    <select id="attendanceStatus" name="status">
                        <option value="Present">Present</option>
                        <option value="Absent">Absent</option>
                    </select>
                </div>
                
                <button type="submit" class="submit-btn">Add Record</button>
            </form>
        </div>
    </div>

    <!-- Modal For Overall Attendance Summary -->
    <div id="overallModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Overall Summary</h2>
            <form id="attendanceForm">
                <div class="form-group">
                    <label for="memberName">Member Name:</label>
                    <select id="membername" required>
                        <option disabled selected>Select Member</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="attendanceDate">Date:</label>
                    <input type="date" id="attendanceDate" required>
                </div>
                
                <div class="form-group">
                    <label for="attendanceStatus">Status:</label>
                    <select id="attendanceStatus" required>
                        <option value="Present">Present</option>
                        <option value="Absent">Absent</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal For Overall Attendance Of Male -->
    <div id="maleModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Male Summary Record</h2>
            <form id="attendanceForm">
                <div class="form-group">
                    <label for="memberName">Member Name:</label>
                    <select id="membername" required>
                        <option disabled selected>Select Member</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="attendanceDate">Date:</label>
                    <input type="date" id="attendanceDate" required>
                </div>
                
                <div class="form-group">
                    <label for="attendanceStatus">Status:</label>
                    <select id="attendanceStatus" required>
                        <option value="Present">Present</option>
                        <option value="Absent">Absent</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal For Overall Attendance Of Female -->
    <div id="femaleModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Female Summary Record</h2>
            <form id="attendanceForm">
                <div class="form-group">
                    <label for="memberName">Member Name:</label>
                    <select id="membername" required>
                        <option disabled selected>Select Member</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="attendanceDate">Date:</label>
                    <input type="date" id="attendanceDate" name="date" required>
                </div>
                
                <div class="form-group">
                    <label for="attendanceStatus">Status:</label>
                    <select id="attendanceStatus" required>
                        <option value="Present">Present</option>
                        <option value="Absent">Absent</option>
                    </select>
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
                // Present Date 
                let date = new Date();
                let month = String(date.getMonth() + 1).padStart(2, '0');
                let day = String(date.getDate()).padStart(2, '0');
                let year = date.getFullYear();
                let format = `${year}-${month}-${day}`;
                let present = {day: 'numeric'};
                const weeks = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', ' Thursday', 'Friday', 'Saturday']; 
                let weekday = date.getDay();
                let formattedDay = weeks[weekday];
                $('#attendanceDay').val(formattedDay);
                $('#attendanceDate').val(format);
                $('#attendanceYear').val(year);
                $('#attendanceModal').show();
            });

            // Close the modal when the close button is clicked
            $('.close').click(function() {
                $('#attendanceModal').hide();
            });

            // Close the modal when clicking outside of the modal content
            $(window).click(function(event) {
                if ($(event.target).is('#attendanceModal')) {
                    $('#attendanceModal').hide();
                }
            }); 
        });


        // Attendance Function

        $(document).on('submit', '#attendanceForm', function(e){
            e.preventDefault();
            let attendance = new FormData(this);
            attendance.append('attendance', true);

            $.ajax({
                url: '../function.php',
                method: 'POST',
                data: attendance,
                processData: false,
                contentType: false,
                success:function(response){
                    let res = jQuery.parseJSON(response);

                    switch(res.status){
                        case 422:
                            alertify.set('notifier','position', 'top-center');
                            alertify.error(res.message);
                            break;
                        case 500:
                            alertify.set('notifier','position', 'top-center');
                            alertify.error(res.message);
                            break;
                        case 200:
                            alertify.set('notifier','position', 'top-right');
                            alertify.success(res.message);
                            $('#attendanceForm')[0].reset();
                            $('#attendanceTB').load(location.href + " #attendanceTB");
                            $('#attendanceModal').hide();
                            break;
                    }
                }
            });
        });

        // when widgets is clicked modal will appear

        $(document).on('click', '#card1', function(){

            $('#overallModal').show();

            $(document).on('click', '.close', function(){
                $('#overallModal').hide();
            });
            $(window).on('click', function(event){
                if($(event.target).is('#overallModal')){
                    $('#overallModal').hide();
                }
            });

        });

        // Male Modal function
        $(document).on('click', '#card2', function(){
            $('#maleModal').show();

            $(document).on('click', '.close', function(){
                $('#maleModal').hide();
            });
            $(window).on('click', function(event){
                if($(event.target).is('#maleModal')){
                    $('#maleModal').hide();
                }
            });
        });
        
        // Female Modal function
        $(document).on('click', '#card3', function(){
            $('#femaleModal').show();

            $(document).on('click', '.close', function(){
                $('#femaleModal').hide();
            });

            $(window).on('click', function(event){
                if($(event.target).is('#femaleModal')){
                    $('#femaleModal').hide();
                }
            });
        });
    </script>
</body>
</html>

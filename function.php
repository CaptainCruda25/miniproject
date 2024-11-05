<?php

require 'server.php';
error_reporting(0);

if(isset($_POST['open'])){
    $username = mysqli_real_escape_string($conn, $_POST['uname']);
    $password = mysqli_real_escape_string($conn, $_POST['pword']);
    $uname = "";
    
    if(empty($username) || empty($password)){
    
        $res = [
                'status' => 422,
                'message' => 'All Fields Are Mandatory!'
            ];
        
    }
    else {
        $exist = "SELECT * FROM accounts WHERE username LIKE ?;";
        $stmt = $conn->prepare($exist);
        $stmt->bind_param('s', $username);
        $stmt->execute();

        $result = $stmt->get_result();

        while($row = $result->fetch_assoc()){
            $role = $row['accrole'];
            $uname = $row['username'];
            $pass = $row['password'];
        
        }
        
        if($username !== $uname){
            $res = [
                'status' => 422,
                'message' => 'Invalid Username Or Password!'
            ];
        }
        else if(!password_verify($password, $pass)) {
            $res = [
                'status' =>  422,
                'message' => 'Invalid!'
            ];
        }
        else{
            $res = [
                'status' => 200,
                'message' => 'Login Successfully!'
            ];
        }
        
    }
    echo json_encode($res);
}

// Register Function


if(isset($_POST['register'])){
    $username = $_POST['uname'];
    $pword = $_POST['pword'];
    $rpass = $_POST['rpword'];

    if(empty($username) || empty($pword) || empty($rpass)){
        $res = [
            'status' => 422,
            'message' => 'Fill All The Fields!'
        ];
    } 
    else if ($pword !== $rpass){
        $res = [
            'status' => 422,
            'message' => 'Password Doesn\'t Match!'
        ];
    }
    else{
        $find = "SELECT * FROM accounts WHERE username LIKE ?;";
        $stmt = $conn->prepare($find);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        
        $results = $stmt->get_result();

        if($results->num_rows > 0){
            $res = [
                'status' => 422,
                'message' => 'Account Exists!'
            ];
        }
        else {
            $hashpwd = password_hash($pword, PASSWORD_BCRYPT);
            $sql = "INSERT INTO accounts(username, password) VALUES(?, ?);";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $username, $hashpwd);
            
            if($stmt->execute()){
                $res = [
                    'status' => 200, 
                    'message' => 'Register Successfully!'
                ];
            }
            else {
                $res = [
                    'status' => 500,
                    'message' => 'Error Occured!'
                ];
            }
        }
    }
    

    echo json_encode($res);
}

// Add Member Function

if(isset($_POST['add'])){
    $fname = ucfirst($_POST['fname']);
    $mname = ucfirst($_POST['mname']);
    $lname = ucfirst($_POST['lname']);
    $bday = $_POST['bday'];
    $sex = $_POST['sex'];
    $group = $_POST['group'];

    if(empty($fname) || empty($mname) || empty($lname) || empty($bday) || empty($sex) || empty($group)){
        $res = [
            'status' => 422,
            'message' => 'Fill All The Fields'
        ];
    }
    else {
        $exists = "SELECT * FROM `members` 
                INNER JOIN cell_group ON members.cell_group_id = cell_group.cell_group_id WHERE fname LIKE ? AND mname LIKE ? AND lname LIKE ? 
                AND deleted LIKE 'No';";
        $stmt = $conn->prepare($exists);
        $stmt->bind_param('sss', $fname, $mname, $lname);
        $stmt->execute();
        
        $find = $stmt->get_result();
        
        if ($find->num_rows > 0) {
            $res = [
                'status' => 422,
                'message' => 'Member Exists!'
            ];
        }
        else{
            $register = 'INSERT INTO members(lname, fname, mname, bday, sex, cell_group_id) VALUES(?, ?, ?, ?, ?, ?);';
            $stmt = $conn->prepare($register);
            $stmt->bind_param('sssssi', $lname, $fname, $mname, $bday, $sex, $group);

            if($stmt->execute()){
                $res = [
                    'status' => 200, 
                    'message' => 'Added Successfully!'
                ];
            }
            else{
                $res = [
                    'status' => 500,
                    'message' => 'Error Occured!'
                ];
            }
        }
    }

    echo json_encode($res);
}

// View Member Info

if(isset($_POST['view'])){
    $view = $_POST['view'];

    $memberinfo = "SELECT * FROM `members` 
                INNER JOIN cell_group ON members.cell_group_id = cell_group.cell_group_id WHERE memberID = ? 
                AND deleted LIKE 'No' ORDER BY memberID DESC;";
    $stmt = $conn->prepare($memberinfo);
    $stmt->bind_param('i', $view);
    
    if ($stmt->execute()){
        $result = $stmt->get_result();
    
        $fetch = $result->fetch_assoc();
        
        $res = [
            'status'=> 200,
            'message' => 'Fetch SuccessFully!',
            'data'=> $fetch
        ];

    }
    else{
        $res = [
            'status' => 500,
            'message'=> 'Error Occured!'
        ];
    }
    

    echo json_encode($res);
}


// Attendance Function

if(isset($_POST['attendance'])){
    $members = $_POST['membername'];
    $day = $_POST['day'];
    $attendanceDate = $_POST['attendanceDate'];
    $year = $_POST['year'];
    $status = $_POST['status'];

    if(empty($members) || empty($day) || empty($attendanceDate) || empty($status)){
        $res = [
            'status' => 422,
            'message' => 'All Fields Are Mandatory'
        ];
    }
    else {
        $date = date('Y-m-d');
        $attendanceexist = "SELECT * FROM attendance_records WHERE memberID = ? AND date = ?;";
        $stmt = $conn->prepare($attendanceexist);
        
        if(!$stmt){
            $res = [
                'status' => 500,
                'message' => 'Connection Error!'
            ];
        }
        else {
            $stmt->bind_param('is', $members, $date);
            if($stmt->execute()){
                $result = $stmt->get_result();

                if($result->num_rows == 1){
                    $res = [
                        'status' => 422,
                        'message' => 'Attendance Exist'
                    ];
                }
                else{
                    $attendance = "INSERT INTO attendance_records(memberID, date, day, year, status) VALUES(?, ?, ?, ?, ?);";
                    $stmt = $conn->prepare($attendance);
                    if(!$stmt){
                        $res = [
                            'status' => 500,
                            'message' => 'Connection Error!'
                        ];
                    }
                    else{
                        $stmt->bind_param('issss', $members, $attendanceDate, $day, $year, $status);
                        
                        if($stmt->execute()){
                            $res = [
                                'status' => 200,
                                'message' => 'Attendance Successfully Added!'
                            ];
                        }
                        else{
                            $res = [
                                'status' => 500,
                                'message' => 'Error Occured!'
                            ];
                        }
                    }

                }
                $stmt->close();
            }
            else{
                $res = [
                    'status' => 500,
                    'message' => 'Error Occured!'
                ];
            }
        }
    }
    
    echo json_encode($res);
}
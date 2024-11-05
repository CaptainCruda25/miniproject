<?php

require 'server.php';



if(isset($_POST['search'])){
    $search = $_POST['search'];
    $pattern = '%'. $search .'%';

    $searchquery = "SELECT * FROM `members` 
                    INNER JOIN cell_group ON members.cell_group_id = cell_group.cell_group_id WHERE memberID LIKE ? 
                    OR fname LIKE ? OR mname LIKE ? OR lname LIKE ? OR sex LIKE ? OR cell_group_name LIKE ?
                    AND deleted LIKE 'No' ORDER BY memberID DESC;";
    $stmt = $conn->prepare($searchquery);
    $stmt->bind_param('ssssss', $pattern, $pattern, $pattern, $pattern, $pattern, $pattern);
    $execute = $stmt->execute();
    if(!$execute){
?>
         <tr>
            <td colspan="5"><h3>Error Occured!</h3></td>
        </tr>
<?php
        
    }
    else {
        $result = $stmt->get_result();
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $memberId = $row['memberID'];
                $fname = $row['fname'];
                $mname = $row['mname'];
                $lname = $row['lname'];
                $sex = $row['sex'];
                $group = $row['cell_group_name'];
    
    ?>
                <tr>
                    <td><?php echo $memberId;?></td>
                    <td><?php echo $lname .", ".$fname. " ". $mname[0]. ".";?></td>
                    <td><?php echo $sex; ?></td>
                    <td><?php echo $group; ?></td>
                    <td>
                        <button type="button" class="view" value="<?php echo $memberId; ?>"><i class="fas fa-eye"></i></button>
                        <button type="button" class="update" value="<?php echo $memberId; ?>"><i class="fas fa-edit"></i></button>
                        <button type="button" class="delete" value="<?php echo $memberId; ?>"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
    <?php
            }
        }
        else{
    ?>
                <tr>
                    <td colspan="5"><h3>No Data Found!</h3></td>
                </tr>
    <?php
        }
    }    
    
    
    $stmt->close();
}

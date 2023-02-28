<?php

include '../dbconfig.php';
session_start();
if ($_SESSION['employee'] == 'yes') {

    $output = '';
    $sql3 = mysqli_query($conn," SELECT * FROM `leaves` INNER JOIN add_leave on add_leave.leave_id = leaves.leave_id WHERE leaves.e_id = '".$_SESSION['e_id']."' AND leaves.admin_id = '".$_SESSION['admin_id']."' ");
                        $no = 1;    
            while ($row = mysqli_fetch_array($sql3)) {
//                echo $row["leave_status"];exit();
                if ($row["leave_status"] == 0) {
                    $status = '<a href="#" style="background-color: blue;color: white; display: inline-block; padding: .25em .4em; font-size: 75%;font-weight: 700;line-height: 1; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;" data-toggle="modal"> Pending</a>';
                } else if($row["leave_status"] == 11) {
                    $status = '<a href="#" style="background-color: green;color: white; display: inline-block; padding: .25em .4em; font-size: 75%;font-weight: 700;line-height: 1; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;" data-toggle="modal"> Approved</a>';
                } else {
                    $status = '<a href="#" style="background-color: red;color: white; display: inline-block; padding: .25em .4em; font-size: 75%;font-weight: 700;line-height: 1; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;" data-toggle="modal"> Decline</a>';
                }
                
                $output .= '<tr>
                    <td>'.$no++.'</td>
                                <td>' . $row["leave_type"] . '</td>
                                <td>' . $row["from_date"] . '</td>
                                <td>' . $row["to_date"] . '</td>
                                <td>' . $row["leave_reason"] . '</td>
                                <td>'.$status.'</td>
                            </tr>
                        ';
            }
            
    echo $output;
}
?>            

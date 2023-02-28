<?php
session_start();
include '../dbconfig.php';

if ($_SESSION['employee'] == 'yes') {

    $output = '';
    $sql3 = mysqli_query($conn, "select * from resignation where e_id = '" . $_SESSION['e_id'] . "' AND admin_id = '" . $_SESSION['admin_id'] . "' ORDER BY added_time DESC ");
    $no = 1;
    while ($row = mysqli_fetch_array($sql3)) {
        
        if ($row['request_status'] == 00) {
            $status = '<a class="view_data" href="#" style="background-color: blue;color: white; display: inline-block; padding: .25em .4em; font-size: 75%;font-weight: 700;line-height: 1; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;" data-toggle="modal"> Pending</a>';
        } else if($row['request_status'] == 11) {
            $status = '<a class="view_data" href="#" style="background-color: green;color: white; display: inline-block; padding: .25em .4em; font-size: 75%;font-weight: 700;line-height: 1; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;" data-toggle="modal"> Approved</a>';
        } else {
            $status = '<a class="view_data" href="#" style="background-color: red;color: white; display: inline-block; padding: .25em .4em; font-size: 75%;font-weight: 700;line-height: 1; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;" data-toggle="modal"> Decline</a>';
        }
        $output .= "<tr><td>" . $no++ . "</td>
                                <td>" . $row['reason'] . "</td>
                                <td>" . date("d-m-Y",$row['notice_date']) . "</td>
                                <td>" . date("d-m-Y",$row['resignation_date']) . "</td>
                                <td>" . $status . "</td></tr>";
    }
    echo $output;
}
?>            

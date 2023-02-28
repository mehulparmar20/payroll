<?php

session_start();
if(isset($_SESSION['substatus'])){
    header("location:plan.php");
}
if ($_SESSION['admin'] == 'yes') {
    include '../dbconfig.php';
    $sql = mysqli_query($conn, "select * from resignation INNER JOIN employee ON employee.e_id = resignation.e_id where resignation.delete_status = '0' AND resignation.admin_id = '" . $_SESSION['admin_id'] . "' ");
    $output = '<table id="resignation" class="table table-striped custom-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Resigning Employee </th>
                                <th>Department </th>
                                <th>Reason </th>
                                <th>Notice Date </th>
                                <th>Resignation Date </th>
                                <th>Status</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody id="">';
    $no = 1;
   while ($row = mysqli_fetch_array($sql)) {
        if ($row['request_status'] == 00) {
            $status = '<span class="view_data"  style="background-color: blue;color: white; display: inline-block; padding: .25em .4em; font-size: 75%;font-weight: 700;line-height: 1; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;" data-toggle="modal"> Pendding</a>';
        } else if ($row['request_status'] == 11) {
            $status = '<span class="view_data"   style="background-color: green;color: white; display: inline-block; padding: .25em .4em; font-size: 75%;font-weight: 700;line-height: 1; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;" data-toggle="modal"> Approved</a>';
        } else {
            $status = '<span class="view_data"  style="background-color: red;color: white; display: inline-block; padding: .25em .4em; font-size: 75%;font-weight: 700;line-height: 1; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;" data-toggle="modal"> Decline</a>';
        }

        $output .= '
                            <tr>
                                <td>' . $no++ . '</td>
                                <td>
                                    <h2 class="table-avatar blue-link">
                                        <a href="profile.php?id='.$row['e_id'].'" class="avatar"><img alt="" src="../employee/employee_profile/' . $row['employee_profile'] . ' " height="100%" ></a>
                                        <a href="profile.php?id='.$row['e_id'].'">' . ucfirst($row['e_firstname']) . '&nbsp;' . ucfirst($row['e_lastname']) . '</a>
                                    </h2>
                                </td>
                                <td>' . $row['department'] . '</td>
                                <td>' . $row['reason'] . '</td>
                                <td>' . date("d/m/Y", $row['notice_date']) . '</td>
                                <td>' . date("d/m/Y", $row['resignation_date']) . '</td>
                                <td>' . $status . '</td>
                                <td class="text-right">
                                            <a class="edit_data" id=' . $row['resignation_id'] . ' href="#" style="color:black" data-toggle="modal"><i class="fa fa-pencil m-r-5"></i></a>
                                            <a class="delete" id=' . $row['resignation_id'] . ' href="#" style="color:black" data-toggle="modal" data-target="#delete_resignation"><i class="fa fa-trash-o m-r-5"></i></a>
                                            <a href="resignation_print.php?id=' . $row['resignation_id'] . ' " target="_blank" style="color:black"><i class="fa fa-print m-r-5"></i></a>
                                </td>
                            </tr>
                    ';
    }
    $output .= '</tbody>
                    </table>';
    echo $output;
}
?>



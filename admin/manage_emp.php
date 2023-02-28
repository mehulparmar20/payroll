<?php
ob_start();
require_once("../dbconfig.php");
$limit = $_POST['select_row'];
$page = 1;
if ($_POST['page'] > 1) {
    $start = (($_POST['page'] - 1) * $limit);
    $page = $_POST['page'];
} else {
    $start = 0;
}
$admin_id = $_POST['admin_id'];
$query = "
SELECT * FROM employee INNER JOIN departments ON employee.department = departments.departments_id WHERE employee.admin_id = '$admin_id' ";

if ($_POST['query'] != '') {
    $query .= '
   and e_firstname LIKE "%' . str_replace(' ', '%', $_POST['query']) . '%" or e_email LIKE "%' . str_replace(' ', '%', $_POST['query']) . '% and departments.admin_id = ' . $admin_id . ' " 
  ';
}
$query .= "ORDER BY ".$_POST['column']." ".$_POST['sorting']." ";
$filter_query = $query . 'LIMIT ' . $start . ', ' . $limit . '';
$statement = mysqli_query($conn, $query);
$total_data = mysqli_num_rows($statement);

$statement = mysqli_query($conn, $filter_query);
$total_filter_data = mysqli_num_rows($statement);
// <th><center><marquee behavior="scroll" direction="left" scrollamount="1"><b>Employee ID</b></marquee></center></th>
// <th><center><b><marquee behavior="scroll" direction="left" scrollamount="1">Comapany Benefits</marquee></b></center></th>
// <th><center><b><marquee behavior="scroll" direction="left" scrollamount="1">Benefits(ON/ OFF)</marquee></b></center></th>
// <th><center><b>Active/Deactive</b></center></th>
$output = '
<table id="employee" class="table table-striped custom-table">
    <thead>
        <tr>
            <th><center><b>Name</b></center></th>
            
            <th><center><b>Join Date</b></center></th>
            <th><center><b>Status</b></center></th>
           
        </tr>
    </thead>
    <tbody id="view_data">
';
if ($total_data > 0) {
    $no = $start; 
    while ($row = mysqli_fetch_array($statement)) {
        if ($row['employee_status'] == 1) {
            $status = '<i class="fa fa-check-square text-success"></i> Active';
            $status_button = '<a class="deactive" data-toggle="modal"  data-target="#deactive_employee" href="#" title="Deactive" id="' . $row['emp_cardid'] . '" style="background-color: #b92f33; height: 25px; width: 25px;border-radius: 50%;display: inline-block;color: white;line-height: 24px">&nbsp;<i style="font-size: 12px" class="fa fa-times m-r-5"></i></a>';
        } else {
            $status = '<i class="fa fa-user-times text-denger"></i> Deactivate';
            $status_button = '<a class="active_emp" data-toggle="modal"  data-target="#active_employee" href="#" title="Active" id="' . $row['emp_cardid'] . '" style="background-color: #0fa01b; height: 25px; width: 25px;border-radius: 50%;display: inline-block;color: white;line-height: 24px" >&nbsp;<i style="font-size: 12px" class="fa fa-check m-r-5"></i></a>';
        }
        if ($row['e_benefits'] == 1) {
            $status_benefits = '<i class="fa fa-check-square text-success"></i> ON';
            $benifits = '<a class="off_benifits" data-toggle="modal"  data-target="#off_benifits" href="#" title="OFF" id="' . $row['e_id'] . '" style="background-color: #b92f33; height: 25px; width: 25px;border-radius: 50%;display: inline-block;color: white;line-height: 24px" >&nbsp;<i style="font-size: 12px" class="fa fa-times m-r-5"></i></a>';
        } else {
            $status_benefits = '<i class="fa fa-user-times text-denger"></i> OFF';
            $benifits = '<a class="on_benifits" data-toggle="modal"  data-target="#on_benifits" href="#" title="ON" id="' . $row['e_id'] . '" style="background-color: #0fa01b; height: 25px; width: 25px;border-radius: 50%;display: inline-block;color: white;line-height: 24px" >&nbsp;<i style="font-size: 12px" class="fa fa-check m-r-5"></i></a>';
        }
// <td><center>' . $row['emp_cardid'] . '</center></td>
//<td><center>' . $status_benefits . '</center></td>
// <td><center>'.$benifits.'</center></td>
// <td class=""><center>'.$status_button.'</center></td>

        $output .= '<tr>  
                                <td>
                                    <h2 class="table-avatar">
                                        <a href="profile.php?id=' . $row['e_id'] . '" class="avatar"><img alt="" height="100%" src="../employee/employee_profile/' . $row['employee_profile'] . '"></a>
                                        <a href="profile.php?id=' . $row['e_id'] . '">' . $row["e_firstname"] . '&nbsp;' . $row["e_lastname"] . '<span>' . $row["departments_name"] . '</span></a>
                                    </h2>
                                </td>
                                <td><center>' . date("d/m/Y", $row['join_date']) . '</center></td>
                                <td><center>' . $status . '</center></td>
                                
                            </tr>';
    }
} else {
    $output .= '
  <tr>
    <td colspan="2" align="center">No Data Found</td>
  </tr>
  ';
}

$output .= '
</table>
<br />
<div class="col-auto float-right ml-auto">
  <ul class="pagination">
';

$total_links = ceil($total_data / $limit);
$previous_link = '';
$next_link = '';
$page_link = '';
$page_array = array();

//echo $total_links;

if ($total_links > 4) {
    if ($page < 5) {
        for ($count = 1; $count <= 5; $count++) {
            $page_array[] = $count;
        }
        $page_array[] = '...';
        $page_array[] = $total_links;
    } else {
        $end_limit = $total_links - 5;
        if ($page > $end_limit) {
            $page_array[] = 1;
            $page_array[] = '...';
            for ($count = $end_limit; $count <= $total_links; $count++) {
                $page_array[] = $count;
            }
        } else {
            $page_array[] = 1;
            $page_array[] = '...';
            for ($count = $page - 1; $count <= $page + 1; $count++) {
                $page_array[] = $count;
            }
            $page_array[] = '...';
            $page_array[] = $total_links;
        }
    }
} else {
    for ($count = 1; $count <= $total_links; $count++) {
        $page_array[] = $count;
    }

}

for ($count = 0; $count < count($page_array); $count++) {
    if ($page == $page_array[$count]) {
        $page_link .= '
    <li class="page-item active">
      <a class="page-link" href="#">' . $page_array[$count] . ' <span class="sr-only">(current)</span></a>
    </li>
    ';

        $previous_id = $page_array[$count] - 1;
        if ($previous_id > 0) {
            $previous_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="' . $previous_id . '">Previous</a></li>';
        } else {
            $previous_link = '
      <li class="page-item disabled">
        <a class="page-link" href="#">Previous</a>
      </li>
      ';
        }
        $next_id = $page_array[$count] + 1;
        if ($next_id >= $total_links) {
            $next_link = '
      <li class="page-item disabled">
        <a class="page-link" href="#">Next</a>
      </li>
        ';
        } else {
            $next_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="' . $next_id . '">Next</a></li>';
        }
    } else {
        if ($page_array[$count] == '...') {
            $page_link .= '
      <li class="page-item disabled">
          <a class="page-link" href="#">...</a>
      </li>
      ';
        } else {
            $page_link .= '
      <li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="' . $page_array[$count] . '">' . $page_array[$count] . '</a></li>
      ';
        }
    }
}

$output .= $previous_link . $page_link . $next_link;
$output .= '
  </ul>

</div>
';

echo $output;

?>
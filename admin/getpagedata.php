<?php
ob_start();
if(!isset($_POST['admin_id'])){
    header("../index.php");
    exit();
}
require_once ("../dbconfig.php");

$limit = $_POST['select_row'];
$page = 1;
if($_POST['page'] > 1)
{
    $start = (($_POST['page'] - 1) * $limit);
    $page = $_POST['page'];
}
else
{
    $start = 0;
}
$admin_id = $_POST['admin_id'];
$query = "
SELECT * FROM employee INNER JOIN departments ON employee.department = departments.departments_id WHERE employee.admin_id = '$admin_id' and employee.employee_status = 1 and employee.delete_status = 0
";

if($_POST['query'] != '')
{
    $query .= "and employee.". $_POST['searchcolumn']." LIKE '%". $_POST['query']."%' ";
}

$query .= "ORDER BY employee.".$_POST['column']." ".$_POST['sorting']." ";

$filter_query = $query . 'LIMIT '.$start.', '.$limit.'';
$statement = mysqli_query($conn, $query);
$total_data = mysqli_num_rows($statement);
$statement = mysqli_query($conn,$filter_query);
$total_filter_data = mysqli_num_rows($statement);

$no = $start + 1;
$tabledata = array();
if($total_data > 0)
{
    while($row = mysqli_fetch_array($statement))
    {
        $row['join_date'] = date('d-m-Y',$row['join_date']);
        $tabledata[] = $row;
    }
}


$total_links = ceil($total_data/$limit);
$previous_link = '';
$next_link = '';
$page_link = '';
$page_array = array();

//echo $total_links;

if($total_links > 4)
{
    if($page < 5)
    {
        for($count = 1; $count <= 5; $count++)
        {
            $page_array[] = $count;
        }
        $page_array[] = '...';
        $page_array[] = $total_links;
    }
    else
    {
        $end_limit = $total_links - 5;
        if($page > $end_limit)
        {
            $page_array[] = 1;
            $page_array[] = '...';
            for($count = $end_limit; $count <= $total_links; $count++)
            {
                $page_array[] = $count;
            }
        }
        else
        {
            $page_array[] = 1;
            $page_array[] = '...';
            for($count = $page - 1; $count <= $page + 1; $count++)
            {
                $page_array[] = $count;
            }
            $page_array[] = '...';
            $page_array[] = $total_links;
        }
    }
}
else
{
    for($count = 1; $count <= $total_links; $count++)
    {
        $page_array[] = $count;
    }

}

$array = array(
    'table' => $tabledata,
    'paginate' => $page_array,
    'page' => $page,
    'totallink' => $total_links,
    'no' => $no);

echo json_encode($array);

?>
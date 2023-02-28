<?php
include 'dbconfig.php';
if ($_POST['action'] == 'view_plan') {
    $id = $_POST['id'];
    $h_id = $_POST['hardware_type'];
    $month = $_POST['month'];
    $no_of_emp = $_POST['no'];
    $query = mysqli_query($conn, "SELECT * FROM product_plan where plan_id = '$id'");
    while ($row = mysqli_fetch_assoc($query)) {
        $plan_name = $row['plan_name'];
        $plan_price = $row['product_price'];
        $plan_discount = $row['discount'];
        $tax = $row['tax'];
    }

    $h_type = mysqli_query($conn, "SELECT * FROM hardwere_type where hardware_id = '$h_id'");
    while ($row = mysqli_fetch_assoc($h_type)) {
        $hardware_name = $row['hardware_name'];
        $hardware_price = $row['hardware_price'];
    }
    
    $prd_name = $plan_name;   //plan Name
    $price = $plan_price * $month * $no_of_emp; //Service price
    $fee = $hardware_price; // Hardware Fees
    $prd_price = $price;
    $discount = 0;
      if($no_of_emp >= 100){
          $discount = (ceil($prd_price * $plan_discount / 100));
      }
      if($no_of_emp >= 250){
          $plan_discount = $plan_discount + 5;
          $discount = (ceil($prd_price * $plan_discount / 100));
      }
      if($month >= 12){
          $plan_discount = $plan_discount + 5;
          $discount = (ceil($prd_price * $plan_discount / 100));
      }
      $dis = (ceil($prd_price - $discount));
      $total = $fee + $dis;
      $gst = $total * $tax / 100;
      $total = $total + $gst;
      $prd_price = (ceil($total));
    
if($h_id == 1) {
    $select ='<select id="confirm" onchange="confirm_pro()" name="confirm"><option value="yes" selected>YES</option><option value="no">NO</option></select>';
    if($_POST['confirm'] == 'no'){
        $total =  $dis;
        $gst = $total * $tax / 100;
        $total = $total + $gst;
        $prd_price = (ceil($total));
        $select = '<select id="confirm" onchange="confirm_pro()" name="confirm"><option value="yes">YES</option><option value="no" selected>NO</option></select>';
    }
    $output = ' <label class="form-control"> Plan Name: ' . $plan_name . '</label><br>
                            <label class="form-control"> Service Price: ' . '&#x20b9;' . number_format($price) . ' (&#x20b9;' . $plan_price . ' * ' . $month . ' Mon' . ' * ' . $no_of_emp . ' Emp) </label><br>
                            <label class="form-control"> Hardware Type: ' . $hardware_name . '</label><br>
                            <label class="form-control"> Hardware Price: ' . '&#x20b9;' . number_format($fee) . '&nbsp;&nbsp;&nbsp;&nbsp; '.$select.' Select NO If you do not need Hardware(Mobile/ Tablet).</span></label><br>
                            <label class="form-control"> Discount: ' . '&#x20b9;' . number_format(@$discount) . '</label><br>
                            <label class="form-control"> Total: ' . '&#x20b9;' . number_format($prd_price) . ' (Include ' . $tax . '% GST)</label><br>   
                            <input type="hidden" name="plan" id="plan" value = ' . $plan_name . '> 
                            <input type="hidden" name="price" id="price" value = ' . $prd_price . '> ';
}elseif ($h_id == 2) {
    $output = ' <label class="form-control"> Plan Name: ' . $plan_name . '</label><br>
                            <label class="form-control"> Service Price: ' . '&#x20b9;' . number_format($price) . ' (&#x20b9;' . $plan_price . ' * ' . $month . ' Mon' . ' * ' . $no_of_emp . ' Emp) </label><br>
                            <label class="form-control"> Hardware Type: ' . $hardware_name . '</label><br>
                            <label class="form-control"> Hardware Price: ' . '&#x20b9;' . number_format($fee) . '</label><br>
                            <label class="form-control"> Discount: ' . '&#x20b9;' . number_format(@$discount) . '</label><br>
                            <label class="form-control"> Total: ' . '&#x20b9;' . number_format($prd_price) . ' (Include ' . $tax . '% GST)</label><br>   
                            <input type="hidden" name="plan" id="plan" value = "' . $plan_name . '"> 
                            <input type="hidden" name="price" id="price" value = "' . $prd_price . '"> 
                            <input type="hidden" name="confirm" id="confirm" value ="yes"> ';
}elseif ($h_id == 3){
    $output = ' <label class="form-control"> Plan Name: ' . $plan_name . '</label><br>
                            <label class="form-control"> Service Price: ' . '&#x20b9;' . number_format($price) . ' (&#x20b9;' . $plan_price . ' * ' . $month . ' Mon' . ' * ' . $no_of_emp . ' Emp) </label><br>
                            <label class="form-control"> Hardware Type: ' . $hardware_name . '</label><br>
                            <label class="form-control"> Hardware Price: ' . '&#x20b9;' . number_format($fee) . '</label><br>
                            <label class="form-control"> Discount: ' . '&#x20b9;' . number_format(@$discount) . '</label><br>
                            <label class="form-control"> Total: ' . '&#x20b9;' . number_format($prd_price) . ' (Include ' . $tax . '% GST)</label><br>   
                            <input type="hidden" name="plan" id="plan" value = ' . $plan_name . '> 
                            <input type="hidden" name="price" id="price" value = ' . $prd_price . '>
                            <input type="hidden" name="confirm" id="confirm" value ="yes">';
}else{
    $select ='<select id="confirm" onchange="confirm_pro()" name="confirm"><option value="yes" selected>YES</option><option value="no">NO</option></select>';
    if($_POST['confirm'] == 'no'){
        $total =  $dis;
        $gst = $total * $tax / 100;
        $total = $total + $gst;
        $prd_price = (ceil($total));
        $select = '<select id="confirm" onchange="confirm_pro()" name="confirm"><option value="yes">YES</option><option value="no" selected>NO</option></select>';
    }
    $output = ' <label class="form-control"> Plan Name: ' . $plan_name . '</label><br>
                            <label class="form-control"> Service Price: ' . '&#x20b9;' . number_format($price) . ' (&#x20b9;' . $plan_price . ' * ' . $month . ' Mon' . ' * ' . $no_of_emp . ' Emp) </label><br>
                            <label class="form-control"> Hardware Type: ' . $hardware_name . '</label><br>
                            <label class="form-control"> Hardware Price: ' . '&#x20b9;' . number_format($fee) . '&nbsp;&nbsp;&nbsp;&nbsp; '.$select.' Select NO If you do not need Hardware(Mobile/ Tablet).</span></label><br>
                            <label class="form-control"> Discount: ' . '&#x20b9;' . number_format(@$discount) . '</label><br>
                            <label class="form-control"> Total: ' . '&#x20b9;' . number_format($prd_price) . ' (Include ' . $tax . '% GST)</label><br>   
                            <input type="hidden" name="plan" id="plan" value = ' . $plan_name . '> 
                            <input type="hidden" name="price" id="price" value = ' . $prd_price . '> ';

}
                echo $output;
}
if($_POST['action'] == 'check_email'){

    $email = $_POST['company_email'];
    $query = mysqli_query($conn, "SELECT * FROM company_admin where admin_email = '$email'");
    $no = mysqli_num_rows($query);
    if($no > 0){
        $output = 'true';
    }else{
        $output = 'false';
    }
    echo json_encode($output);
}
if ($_POST['action'] == 'view_renew_plan') {
    if(isset($_POST['id']) == ''){
        $id = $_POST['plan_name'];
    }else{
        $id = $_POST['id'];
    }
    
    $month = $_POST['month'];
    $no_of_emp = $_POST['current_employee'];
    $no_of_emp += $_POST['add_employee'];
    
    $query = mysqli_query($conn, "SELECT * FROM product_plan where plan_name = '$id'");
    while ($row = mysqli_fetch_array($query)) {
        $plan_name = $row['plan_name'];
        $plan_price = $row['product_price'];
        $plan_discount = $row['discount'];
        $tax = $row['tax'];
    }
    $prd_name = $plan_name;   //plan Name
    $price = $plan_price * $month * $no_of_emp; //Service price
    $prd_price = $price; 
    $discount = 0;
      if($no_of_emp >= 100){
          $discount = (ceil($prd_price * $plan_discount / 100));
      }
      if($no_of_emp >= 250){
          $plan_discount = $plan_discount + 5;
          $discount = (ceil($prd_price * $plan_discount / 100));
      }
      if($month >= 12){
          $plan_discount = $plan_discount + 5;
          $discount = (ceil($prd_price * $plan_discount / 100));
      }
      $dis = (ceil($prd_price - $discount));
      $total = $dis;
      $gst = $total * $tax / 100;
      $total = $total + $gst;
      $prd_price = (ceil($total));
    
$output = ' <ul class="list-group z-depth-0">
                <li class="list-group-item justify-content-between">Plan Name: ' . $plan_name . '</li>
                <li class="list-group-item justify-content-between"> Service Price: ' . '&#x20b9;' . number_format($price) . ' (&#x20b9;' . $plan_price . ' * ' . $month . ' Mon'.' * ' . $no_of_emp . ' Emp) </li>
                <li class="list-group-item justify-content-between"> Discount: ' . '&#x20b9;' . number_format(@$discount) . '</li>
                <li class="list-group-item justify-content-between">Total: ' . '&#x20b9;' . number_format($prd_price) . ' (Include ' . $tax . '% GST)</li>   
            </ul>
            <input type="hidden" name="plan_name" id="plan_name" value = ' . $plan_name. '> 
            <input type="hidden" name="price" id="price" value = ' . $prd_price . '> ';
echo $output;
}

if ($_POST['action'] == 'renew_plan') {
    $plan_name = $_POST['plan_name'];
    $company_name = $_POST['company_name'];
    $contact_no = $_POST['company_no'];
    $month = $_POST['month'];
    $admin_id = $_POST['admin_id'];
    $time = time();
    $no_of_emp = $_POST['add_employee'];
    $price = $_POST['price'];
    $query = mysqli_query($conn, "INSERT INTO `renew_request`(`company_name`,`company_contact`,`new_employee`, `renew_date`, `renew_price`, `plan_name`, `plan_month`, `admin_id`) VALUES ('$company_name',$contact_no,'$no_of_emp','$time','$price','$plan_name','$month','$admin_id')");
    
}


?>                                               
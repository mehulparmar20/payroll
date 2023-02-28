<?php
session_start();
include '../dbconfig.php';
if ($_SESSION['admin'] == 'yes') {
//print_r($_FILES['files']);exit();
    if (!empty(array_filter($_FILES['files']['name']))) {
        $targetDir = "company_document/"; // target folder path
        $allowedType = array('jpg', 'jpeg', 'png', 'pdf'); // allowed file
        $current_time = time();
        $admin_id = $_SESSION['admin_id'];
        foreach ($_FILES['files']['name'] as $key => $val) {
//            $filename = $_FILES['files']['name'][$key]; // file name
            $filename = $_SESSION['admin_id']. rand(0,999999).$_FILES['files']['name'][$key]; // file name
            $temLoc = $_FILES['files']['tmp_name'][$key]; // temporary location
            $targetPath = $targetDir . $filename; // target file path with file 
            $fileType = pathinfo($targetPath, PATHINFO_EXTENSION); // get file extention
            $fileSize = $_FILES['files']['size'][$key]; // get file size
            
            $storage = mysqli_query($conn," select company_upload_storage from company_admin where admin_id = '".$_SESSION['admin_id']."' ");
            while ($s = mysqli_fetch_array($storage))
            {
                $store = $s['company_upload_storage'];
            }
            
            if (in_array($fileType, $allowedType)) { // condition for check fileType is same or not
                if ($fileSize < $store) { // condition for check file size
                    if (move_uploaded_file($temLoc, $targetPath)) { // condition for move file from current location(temporary location) to upload/ folder
                        $Size = $store - $fileSize;
                        mysqli_query($conn, "Insert into company_document(admin_id,file_name,file_size,file_extension,file_added_time) values('$admin_id','$filename','$fileSize','$fileType','$current_time')"); // get filename and date
                        mysqli_query($conn, " update company_admin set company_upload_storage = '".$Size."' where admin_id = '".$admin_id."' ");
                    } else {
                        $message = "Could Not Upload File";
                    }
                } else {
                    $message = "File Size is To Large";
                }
            } else {
                $message = "File Type Error";
            }
        }
    }
    $response = array(
        "message" => $message
    );
    echo json_encode($response);
}
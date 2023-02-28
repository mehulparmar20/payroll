<?php

include '../dbconfig.php';
session_start();
if ($_SESSION['admin'] == 'yes') {

//print_r($_FILES['files']);exit();
    if (!empty(array_filter($_FILES['files']['name']))) {

        $targetDir = "company_document/"; // target folder path
        $allowedType = array('jpg', 'jpeg', 'png'); // allowed file
        $current_time = time();
        $admin_id = $_SESSION['admin_id'];

        foreach ($_FILES['files']['name'] as $key => $val) {
            $filename = $_SESSION['admin_id']. rand(0,999999).$_FILES['files']['name'][$key]; // file name
            $temLoc = $_FILES['files']['tmp_name'][$key]; // temporary location
            $targetPath = $targetDir . $filename; // target file path with file
            $fileType = pathinfo($targetPath, PATHINFO_EXTENSION); // get file extention
            $fileSize = $_FILES['files']['size'][$key]; // get file size
            if (in_array($fileType, $allowedType)) { // condition for check fileType is same or not
                if ($fileSize < 200000) { // condition for check file size
                    if (move_uploaded_file($temLoc, $targetPath)) { // condition for move file from current location(temporary location) to upload/ folder
                        $sql = mysqli_query($conn," select * from company_logo where admin_id = $admin_id ");
                        $count = $sql->num_rows;
                        if($count < 1)
                        {
                            $sql = mysqli_query($conn, "Insert into company_logo (logo_name,admin_id,entry_time) values('$filename','$admin_id','$current_time')"); // get filename and date
                            if($sql){
                                $message = 'Image Uploaded Successfully.';
                            }
                        }  else {
                            $sql = mysqli_query($conn, "update company_logo set logo_name = '$filename' where admin_id = '$admin_id' "); // get filename and date
                            if($sql){
                                $message = 'Image Change Successfully.';
                            }
                        }
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
    } else {
        $message = 'Please Select an File';

    }
    $respose = array(
        "message" => $message
    );
    echo json_encode($respose);
}





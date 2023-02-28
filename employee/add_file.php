<?php

include '../dbconfig.php';
session_start();
if ($_SESSION['employee'] == 'yes') {
echo "hello";
//print_r($_FILES['files']);exit();
//    if (!empty(array_filter($_FILES['files']['name']))) {
//        print_r($_FILES['files']);exit();
        $targetDir = "uploads/"; // target folder path
        $allowedType = array('jpg', 'jpeg', 'png', 'pdf'); // allowed file
        $current_time = time();
        $admin_id = $_SESSION['admin_id'];
        $e_id = $_SESSION['e_id'];
        $sql = mysqli_query($conn ,"Select * from employee_document where e_id = '$e_id' ");
        $count = 1;
        while ($row = mysqli_fetch_array($sql))
        {
            $count++;
        }
        if($count <= 5)
        {
            $error =0;
            $type =0;
            $size =0;
            foreach ($_FILES['files']['name'] as $key => $val) {
                $filename = $_SESSION['e_id']. rand(0,999999).$_FILES['files']['name'][$key]; // file name
                $temLoc = $_FILES['files']['tmp_name'][$key]; // temporary location
                $targetPath = $targetDir . $filename; // target file path with file 
                $fileType = pathinfo($targetPath, PATHINFO_EXTENSION); // get file extention
                $fileSize = $_FILES['files']['size'][$key]; // get file size
                if (in_array($fileType, $allowedType)) { // condition for check fileType is same or not
                    if ($fileSize < 200000) { // condition for check file size
                        if (move_uploaded_file($temLoc, $targetPath)) { // condition for move file from current location(temporary location) to upload/ folder
                            mysqli_query($conn, "Insert into employee_document(file_name,file_size,file_extension,file_added_time,e_id,admin_id) values('$filename','$fileSize','$fileType','$current_time','$e_id','$admin_id')"); // get filename and date
                        } else {
                            $error++;
                        }
                    } else {
                        $size++;
                    }
                } else {
                    $type++;
                }
            }
            if($error > 0){
                echo "Could Not Upload File";
            }elseif($size > 0){
                echo "File Size is To Large";
            }elseif($type){
                 echo "File Type Error";
            }else{
                 echo "File uploaed Successfully";
            }
        }
        else
        {
            echo "Max 5 File Upload";
//            exit();
        }
//    } else {
//        echo 'Please Select an File';
//        exit();
//    }
}

    
    
    

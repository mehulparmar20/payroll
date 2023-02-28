<?php

include '../dbconfig.php';
session_start();
if ($_SESSION['admin'] == 'yes') {

//print_r($_FILES['files']);exit();
    if (!empty(array_filter($_FILES['files1']['name']))) {
        
        $targetDir = "company_document/"; // target folder path
        $allowedType = array('jpg', 'jpeg', 'png'); // allowed file
        $current_time = time();
        $admin_id = $_SESSION['admin_id'];
        
        foreach ($_FILES['files1']['name'] as $key => $val) {
            $filename = $_SESSION['admin_id']. rand(0,999999).$_FILES['files1']['name'][$key]; // file name
            $temLoc = $_FILES['files1']['tmp_name'][$key]; // temporary location
            $targetPath = $targetDir . $filename; // target file path with file 
            $fileType = pathinfo($targetPath, PATHINFO_EXTENSION); // get file extention
            $fileSize = $_FILES['files1']['size'][$key]; // get file size
            if (in_array($fileType, $allowedType)) { // condition for check fileType is same or not
                if ($fileSize < 200000) { // condition for check file size
                    if (move_uploaded_file($temLoc, $targetPath)) { // condition for move file from current location(temporary location) to upload/ folder
                        $sql = mysqli_query($conn," select * from company_document where admin_id = $admin_id ");
                        $count = $sql->num_rows;
                        
                        if($count < 1)
                        {
                            mysqli_query($conn, "Insert into company_document(invoice_logo,admin_id,entry_time) values('$filename','$admin_id','$current_time')"); // get filename and date
                            
                        }  else {
                           mysqli_query($conn, "update company_document set invoice_logo = '$filename' where admin_id = '$admin_id' "); // get filename and date
                        }
                    } else {
                        echo "Could Not Upload File";
                        
                    }
                } else {
                    echo "File Size is To Large";
                    
                }
            } else {
                echo "File Type Error";
                
            }
        }
    } else {
        echo 'Please Select an File';
        
    }
}

    
    
    

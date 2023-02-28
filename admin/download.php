<?php
include '../dbconfig.php';

if(!empty($_GET['file'])){
//    echo "select * from employee_document where file_name = '".$_GET['file']."' ";    exit();
    $sql = mysqli_query($conn,"select * from employee_document where file_name = '".$_GET['file']."' ");
    $row = mysqli_fetch_array($sql);
    $file = $row['file_name'];
    
    $fileName = basename($file);
    
    $filePath = '../employee/uploads/'.$fileName;
    
    if(!empty($fileName) && file_exists($filePath)){
        // Define headers
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$fileName");
        header("Content-Type: application/zip");
        header("Content-Transfer-Encoding: binary");
        
        // Read the file
        readfile($filePath);
        exit;
    }else{
        echo 'The file does not exist.';
    }
}

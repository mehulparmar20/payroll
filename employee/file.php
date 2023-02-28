<?php
include '../dbconfig.php';
?>
<!DOCTYPE html>  
<html>  
    <head>  
        <title>Webslesson Tutorial | Insert Multiple Images into Mysql Database using PHP</title>  
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
    </head>  
    <body>  
        <br /><br />  
        <div class="container">  
            <h3 align="center">Insert Multiple Images into Mysql Database using PHP</h3>  
            <br />  
            <form method="post" id="upload_multiple_images" enctype="multipart/form-data">
                <input type="file" name="files[]" id="files" multiple accept=".jpg, .png, .pdf" />
                <br />
                <input type="submit" name="insert" id="insert" value="Insert" class="btn btn-info" />
            </form>
            <br />  
            <br />

            <div>
                <?php
                $sql = mysqli_query($conn, "select * From employee_document");
                while ($row = mysqli_fetch_array($sql)) {
                    
                    if ($row['file_extension'] == 'pdf') {
//                        echo 'File is Pdf<br>';
                        echo '<img src="uploads/pdf.png" width="10%" height="5%"><br>';
                    } else if ($row['file_extension'] == 'png') {
//                        echo 'File is png<br>';
                        echo '<img src="uploads/'.$row["file_name"].'" width="10%" height="5%">';
                    } else if ($row['file_extension'] == 'jpg') {
//                        echo 'File is jpg<br>';
                        echo '<img src="uploads/'.$row["file_name"].'" width="10%" height="5%">';
                    } else {
//                        echo 'File is jpeg<br>';
                        echo '<img src="uploads/'.$row["file_name"].'" width="10%" height="5%">';
                    }

                    echo $row['file_name'].'<br>';
                    echo $row['file_size']/1000 .'kb<br>';
                    echo time_Ago($row['file_added_time']).'<br><br>';
                }
                ?>
            </div>

        </div>  
    </body>  
</html>  
<?php

function time_Ago($time) {

    $diff = time() - $time;
    //echo $time."  ".time();
    // Time difference in seconds 
    $sec = $diff;
    // echo $sec;
    // Convert time difference in minutes 
    $min = round($diff / 60);

    // Convert time difference in hours 
    $hrs = round($diff / 3600);

    // Convert time difference in days 
    $days = round($diff / 86400);

    // Convert time difference in weeks 
    $weeks = round($diff / 604800);

    // Convert time difference in months 
    $mnths = round($diff / 2600640);

    // Convert time difference in years 
    $yrs = round($diff / 31207680);

    // Check for seconds 
    if ($sec <= 60) {
        echo "$sec seconds ago";
    }

    // Check for minutes 
    else if ($min <= 60) {
        if ($min == 1) {
            echo "one minute ago";
        } else {
            echo "$min minutes ago";
        }
    }

    // Check for hours 
    else if ($hrs <= 12) {
        if ($hrs == 1) {
            echo "an hour ago";
        } else {
            //echo  "$hrs hours ago"; 
        }
    }

    // Check for days 
    else if ($days <= 7) {
        if ($days == 1) {
            echo "Yesterday";
        } else {
            echo "$days days ago";
        }
    }

    // Check for weeks 
    else if ($weeks <= 4.3) {
        if ($weeks == 1) {
            echo "a week ago";
        } else {
            echo "$weeks weeks ago";
        }
    }

    // Check for months 
    else if ($mnths <= 12) {
        if ($mnths == 1) {
            echo "a month ago";
        } else {
            echo "$mnths months ago";
        }
    }

    // Check for years 
    else {
        if ($yrs == 1) {
            echo "one year ago";
        } else {
            echo "$yrs years ago";
        }
    }
}
?>


<?php
if (isset($_POST['insert'])) {
    $targetDir = "uploads/"; // target folder path
    $allowedType = array('jpg', 'jpeg', 'png', 'pdf'); // allowed file
    $successmsg = $errormsg = ''; // for message
    $current_time = time();
    print_r($_FILES);exit();
    if (!empty(array_filter($_FILES['files']['name']))) {
        foreach ($_FILES['files']['name'] as $key => $val) {
            $filename = $_FILES['files']['name'][$key]; // file name
            $temLoc = $_FILES['files']['tmp_name'][$key]; // temporary location
            $targetPath = $targetDir . $filename; // target file path with file 
            $fileType = pathinfo($targetPath, PATHINFO_EXTENSION); // get file extention
            $fileSize = $_FILES['files']['size'][$key]; // get file size

            if (in_array($fileType, $allowedType)) { // condition for check fileType is same or not
                if ($fileSize < 200000) { // condition for check file size
                    if (move_uploaded_file($temLoc, $targetPath)) { // condition for move file from current location(temporary location) to upload/ folder
                        $sqlValue = "('$filename','$fileSize','$fileType','$current_time')"; // get filename and date
                    } else {
                        echo "<script>alert('Could Not Upload File');</script>";
                    }
                } else {
                    echo "<script>alert('File Size is to large');</script>";
                }
            } else {
                echo "<script>alert('File Type Error');</script>";
            }

            if (!empty($sqlValue)) { // check sqlValue are empty or not
                $query = "Insert into employee_document(file_name,file_size,file_extension,file_added_time) values" . $sqlValue;
                if (mysqli_query($conn, $query)) { // condition for check query is correct or not
                    echo "<script>alert('File Uploaded Successfully');</script>";
                } else {
                    echo "<script>alert('Database Error');</script>";
                }
            }
        }
    }
}
?>

<!--<script>  
$(document).ready(function(){

    load_images();

    function load_images()
    {
        $.ajax({
            url:"../fetch.php",
            success:function(data)
            {
                $('#images_list').html(data);
            }
        });
    }
 
    $('#upload_multiple_images').on('submit', function(event){
        event.preventDefault();
        var image_name = $('#image').val();
        if(image_name == '')
        {
            alert("Please Select Image");
            return false;
        }
        else
        {
            $.ajax({
                url:"../test.php",
                method:"POST",
                data: new FormData(this),
                contentType:false,
                cache:false,
                processData:false,
                success:function(data)
                {
                    $('#image').val('');
                    load_images();
                }
            });
        }
    });
 
});  
</script>-->
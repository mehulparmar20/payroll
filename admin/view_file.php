<?php
session_start();
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
        $GLOBALS['st'] = "$sec seconds ago";
    }

    // Check for minutes 
    else if ($min <= 60) {
        if ($min == 1) {
            $GLOBALS['st'] = "one minute ago";
        } else {
            $GLOBALS['st'] = "$min minutes ago";
        }
    }

    // Check for hours 
    else if ($hrs <= 12) {
        if ($hrs == 1) {
            $GLOBALS['st'] = "an hour ago";
        } else {
            $GLOBALS['st'] = "$hrs hours ago";
        }
    }

    // Check for days 
    else if ($days <= 7) {
        if ($days == 1) {
            $GLOBALS['st'] = "Yesterday";
        } else {
            $GLOBALS['st'] = "$days days ago";
        }
    }

    // Check for weeks 
    else if ($weeks <= 4.3) {
        if ($weeks == 1) {
            $GLOBALS['st'] = "a week ago";
        } else {
            $GLOBALS['st'] = "$weeks weeks ago";
        }
    }

    // Check for months 
    else if ($mnths <= 12) {
        if ($mnths == 1) {
            $GLOBALS['st'] = "one month ago";
        } else {
            $GLOBALS['st'] = "$mnths months ago";
        }
    }

    // Check for years 
    else {
        if ($yrs == 1) {
            $GLOBALS['st'] = "one year ago";
        } else {
            $GLOBALS['st'] = "$yrs years ago";
        }
    }
}

include '../dbconfig.php';

if ($_SESSION['admin'] == 'yes') {
    $output = '';
    $sql = mysqli_query($conn, "select * from company_document where admin_id = '" . $_SESSION['admin_id'] . "' ORDER BY file_added_time DESC ");
    while ($row = mysqli_fetch_array($sql)) {
        if ($row['file_extension'] == 'pdf') {
            $status = '<div class="card-file-thumb">
                                        <i class="fa fa-file-pdf-o"></i>
                                    </div>';
        } else {
            $status = '<div class="card-file-thumb-png">
                                        <img src="company_document/' . $row['file_name'] . '" height="120px" width="100%">      
                                    </div>';
        }

        time_Ago($row['file_added_time']);

        $output .= '<div class="col-6 col-sm-4 col-md-3 col-lg-4 col-xl-3"> 
                        <div class="card card-file">' . $status . '
                        <div class="card-deck">
                            <div class="dropdown-file">
                                <a href="" class="dropdown-link" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="file_download.php?file='.$row['file_name'].'" class="dropdown-item download">Download</a>
                                        <a href="company_document/'.$row['file_name'].'" target="_blank" class="dropdown-item download">View Document</a>
                                        <a href="#" id="' . $row['company_document_id'] . '" class="dropdown-item delete">Delete</a>
                                    </div>															</div>
                                    &nbsp;&nbsp;<center><h6>'.$row['file_name'].'</h6>
                                    <span>' . $row['file_size'] / 1000 . ' KB</span></center>
                            </div>
                            <div class="card-footer">
                                <span class="d-none d-sm-inline">Last Modified: ' . $st . '<p></p></span>
                            </div>
                            </div>
                        </div>
                    </div>';
    }
    echo $output;
}
<?php

include 'dbcon.php';
                                   
$mysqli->query("insert into demo(name) values ('called')");

$data = $_POST;
$mac_provided = $data['mac'];
// Get the MAC from the POST data

$phone = $data['buyer_phone'];

unset($data['mac']);  // Remove the MAC key from the data.

$ver = explode('.', phpversion());
$major = (int) $ver[0];
$minor = (int) $ver[1];

if($major >= 5 and $minor >= 4){
     ksort($data, SORT_STRING | SORT_FLAG_CASE);
}
else{
     uksort($data, 'strcasecmp');
}

// You can get the 'salt' from Instamojo's developers page(make sure to log in first): https://www.instamojo.com/developers
// Pass the 'salt' without the <>.
$mac_calculated = hash_hmac("sha1", implode("|", $data), "4a7734db75ce4e52aa279f62ad60aa32");

if($mac_provided == $mac_calculated){
    //echo "MAC is fine";
    // Do something here
    if($data['status'] == "Credit"){
       $mysqli->query("insert into demo(name) values ($phone)");
    }
    else{
       $mysqli->query("insert into demo(name) values ('Incomplete')");
    }
}
else{
    echo "Invalid MAC passed";
    $mysqli->query("insert into demo(name) values ('Invalid')");
}
?>
<?php
date_default_timezone_set('UTC');
include("lib/check-generator.php");
include 'dbconfig.php';
$CHK = new CheckGenerator;


$check['logo'] = "";
$check['from_name'] = "";
$check['from_address1'] = "";
$check['from_address2'] = "";
$check['from_address1'] = "";

$check['routing_number'] = "";
$check['account_number'] = "";
$check['bank_1'] = "";
$check['bank_2'] = "";
$check['bank_3'] = "";
$check['bank_4'] = "";

$check['signature'] = "";

$check['pay_to'] = "Chetan Parmar";
$check['amount'] = '2500';
$check['date'] = " 2 5 1 0 2 0 1 9";
$check['memo'] = "";

// 3 checks per page

$check['check_number'] = '';
$CHK->AddCheck($check);





if(array_key_exists('REMOTE_ADDR', $_SERVER)) {
  // Called from a browser
  header('Content-Type: application/octet-stream', false);
  header('Content-Type: application/pdf', false);
  $CHK->PrintChecks();
} else {
  // Called from the command line
  ob_start();
  $CHK->PrintChecks();
  $pdf = ob_get_clean();
  file_put_contents('checks.pdf', $pdf);
  echo "Saved to file: checks.pdf\n";
}


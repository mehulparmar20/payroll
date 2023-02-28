<?php 
// echo 'called';
// exit();
session_start();
require_once 'vendor/stripe/stripe-php/init.php';
require_once 'register/Register.php';
require_once 'dbconfig.php';

$mysqli = $conn;
$stripe = new \Stripe\StripeClient('sk_test_51GzePFHpxXHfgq9UsUYEwyLhplZvLMaaxQyGlih6q4jlsiyCjClkXRepQ3q8oC7wj72xFelXxS04mER1d7o4XWa4008Mr0KZ5O');

$payload = file_get_contents('php://input');
//$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;
try {
  $event = \Stripe\Event::constructFrom(
    json_decode($payload, true)
  );
} catch(\UnexpectedValueException $e) {
  // Invalid payload
  echo '⚠️ Webhook error while parsing basic request.';
  http_response_code(400);
  exit();
}

$data = json_decode($payload, true);
$type =  $event->type;

// Handle the event
switch ($type) {
   case 'invoice.paid': 
     //Subscription
      $metadata = $data['data']['object']['metadata'];    
      if(!isset($metadata['mode'])){
          $subId = $data['data']['object']['subscription'];
          $stripeobj = new Register();
          $stripeobj->renewSubscription($subId,$mysqli);    
      }
      
     break;
   case 'checkout.session.completed': 
      $invoicePaid = $event->data->object;
      $object = $data['data']['object'];
      $metadata = $object['metadata'];
      if(array_key_exists('mode',$metadata) == 'subscription'){
          $paymentMode = $metadata['mode'];
          $allproducts = json_decode($metadata['data'], true);
          $stripeobj = new Register();
          $stripeobj->createcompanyAdmin($conn,$allproducts,$object);    
      }
      break;
   default:
    // Unexpected event type
    echo 'Received unknown event type ' . $event->type;
}
http_response_code(200);
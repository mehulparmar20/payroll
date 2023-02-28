<?php

$data = '{
  "id": "evt_1IXqa4HpxXHfgq9UOWEK27V2",
  "object": "event",
  "api_version": "2020-03-02",
  "created": 1616430375,
  "data": {
    "object": {
      "id": "cs_test_b1KmSdx0e0FWFMwhEHbCwkclg6ZucaAo2yroGrMjHalr6ynehp2Io5SdQ6",
      "object": "checkout.session",
      "allow_promotion_codes": null,
      "amount_subtotal": 1949900,
      "amount_total": 1949900,
      "billing_address_collection": null,
      "cancel_url": "https://dev.windsondispatch.com/stripe/cancel.php",
      "client_reference_id": null,
      "currency": "inr",
      "customer": "cus_JAAvHVNGBRYvgi",
      "customer_details": {
        "email": "verifyemail425@gmail.com",
        "tax_exempt": "none",
        "tax_ids": [

        ]
      },
      "customer_email": null,
      "livemode": false,
      "locale": null,
      "metadata": {
        "mode": "subscription",
        "data": {
            "plantype" : "advance",
            "adminname" : "Shyam Patel",
            "comName" : "NBP TECHNOLOGY LLP",
            "adminemail" : "verifyemail425@gmail.com",
            "userpass" : "Windson@123",
            "quntity" : "100",
            "planproid" : "prod_J8K7LKfKqcCdAY",
            "planpriceId" : "price_1IW3T2HpxXHfgq9Upzww8Xuh",
            "devproId" : "price_1IWfSUHpxXHfgq9UNEpbZcUT",
            "cusId" : "cus_JAAvHVNGBRYvgi",
        },
        "cusId": "cus_JAAvHVNGBRYvgi"
      },
      "mode": "subscription",
      "payment_intent": null,
      "payment_method_types": [
        "card"
      ],
      "payment_status": "paid",
      "setup_intent": null,
      "shipping": null,
      "shipping_address_collection": null,
      "submit_type": null,
      "subscription": "sub_JAAvf64eUf35yk",
      "success_url": "https://dev.windsondispatch.com/stripe/success.php",
      "total_details": {
        "amount_discount": 0,
        "amount_shipping": 0,
        "amount_tax": 0
      }
    }
  },
  "livemode": false,
  "pending_webhooks": 2,
  "request": {
    "id": "req_DHQ9LQfADw7qqv",
    "idempotency_key": null
  },
  "type": "checkout.session.completed"
}';
function remove_utf8_bom($text){
    $bom = pack('H*','EFBBBF');
    $text = preg_replace("/^$bom/", '', $text);
    return $text;
}
function remove_slasn($text){
    $text = str_replace("\n", '', $text);
    return $text;
}
$res = remove_utf8_bom(json_encode($data));
$filterdata = json_decode($res,true);
print_r($filterdata);
exit();

foreach ($data as $string) {
    echo 'Decoding: ' . $string;
    json_decode($string);

    switch (json_last_error()) {
        case JSON_ERROR_NONE:
            echo ' - No errors';
            break;
        case JSON_ERROR_DEPTH:
            echo ' - Maximum stack depth exceeded';
            break;
        case JSON_ERROR_STATE_MISMATCH:
            echo ' - Underflow or the modes mismatch';
            break;
        case JSON_ERROR_CTRL_CHAR:
            echo ' - Unexpected control character found';
            break;
        case JSON_ERROR_SYNTAX:
            echo ' - Syntax error, malformed JSON';
            break;
        case JSON_ERROR_UTF8:
            echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
            break;
        default:
            echo ' - Unknown error';
            break;
    }

    echo PHP_EOL;
}


//echo $data;
//$arr = json_decode($data);
//if($arr === null){
//    echo 'invalid json format'.json_last_error();
//}
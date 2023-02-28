<?php
require './vendor/autoload.php';
class Stripe{
    //Data for creating a customer
    private $stripe;
    private $customerName;
    private $customerAddress;
    private $email;
    private $phone;

    public function __construct(){
        $this->stripe = new \Stripe\StripeClient('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');
    }

    public function createCustomer($data){
        $customer = $this->stripe->customers->create([
                'name' => $data['comName'],
                'phone' => $data['admincontact'],
                'email' => $data['adminemail'],
                'metadata' => ["name" => $data['adminname']]
          ]);
        return $customer['id'];
    }

    public function getPlans($db,$plantype,$quntity){
        $planArray = array();
        $planArray['advance'] = array( 'devproId' => 'prod_JAvtzs1dxlAa2s', 'devpriId' => 'price_1IYbKGHpxXHfgq9ULcFJYFAc'); // test device proid prod_JAvtzs1dxlAa2s price id price_1IWfSUHpxXHfgq9UNEpbZcUT
        $planArray['pro'] = array( 'devproId' => 'prod_JAvtzs1dxlAa2s', 'devpriId' => 'price_1IYa1RHpxXHfgq9UA1TXZMp8');
//        $query = $db->query("SELECT * FROM product_plan WHERE plan_name =  {$plantype} ");
//        $row = $query->fetch_assoc();
        $proid = 'prod_JAvtcwPjkqYvSk'; //'prod_J8K7LKfKqcCdAY';
        if ( $quntity >= 10 && $quntity <= 99) {
            // $planid = 'price_1IW3T2HpxXHfgq9Ur8wpropu'; // test
            $planid = 'price_1IYa1ZHpxXHfgq9U6r7w1oul'; // live 
            $res = array('proid' => $proid,'priceId' => $planid);
        }elseif($quntity >= 100 && $quntity <= 199){
            $planid = 'price_1IYa1ZHpxXHfgq9UBdk0v5NG'; // test price_1IW3T2HpxXHfgq9Upzww8Xuh
            $res = array('proid' => $proid,'priceId' => $planid);
        }else{
            $planid = 'price_1IYa1ZHpxXHfgq9UVlKAQAgX'; // test price_1IW3T3HpxXHfgq9UhpLcDqdD
            $res = array('proid' => $proid,'priceId' => $planid);
        }
        return array('id' => $res,'devId' => $planArray[strtolower($plantype)]);
    }
    
    // public function decSubscriptionqty($planid,$db) {
    //     $data = $this->getSubscriptionId($db);
    //     $subid = $data['subcriptionId'];
    //     $quntity = $data['totalemployee'];
    //     $res = $this->takeSubDecision($subid, $quntity, $planid);
    //     if ($res['status']) {
    //         if ($res['quantity'] > 0) {
    //             $qty = $res['quantity']-1; 
    //             $incres = $this->stripe->subscriptionItems->update(
    //                 $res['subId'],
    //                 ['quantity' =>  $qty]
    //             );   
    //         }
    //     }
    // }
    
    // public function getSubscriptionId($db) {
    //     $adminId = $_SESSION['admin_id'];
    //     $query = $db->query("SELECT * FROM company_admin WHERE admin_id =  '{$adminId}' ");
    //     $row = $query->fetch_assoc();
    //     return $row;
    // }
    
    // public function takeSubDecision($subid,$quntity, $planid) { // Stripe Plan Change and update Subscription 
    //     $arr = array();
    //     $arr['prod_JAvtcwPjkqYvSk'] = array("planname" =>'advance','price1' => 'price_1IYa1ZHpxXHfgq9U6r7w1oul');
    //     $data = $this->stripe->subscriptions->retrieve(                                                                                                 
    //         $subid,
    //         []
    //     );
    //     if ( $quntity >= 10 && $quntity <= 99) {
    //         // $planid = 'price_1IW3T2HpxXHfgq9Ur8wpropu'; // test
    //         $planid = 'price_1IYa1ZHpxXHfgq9U6r7w1oul'; // live 
    //         $res = array('proid' => $proid,'priceId' => $planid);
    //     }elseif($quntity >= 100 && $quntity <= 199){
    //         $planid = 'price_1IYa1ZHpxXHfgq9UBdk0v5NG'; // test price_1IW3T2HpxXHfgq9Upzww8Xuh
    //         $res = array('proid' => $proid,'priceId' => $planid);
    //     }else{
    //         $planid = 'price_1IYa1ZHpxXHfgq9UVlKAQAgX'; // test price_1IW3T3HpxXHfgq9UhpLcDqdD
    //         $res = array('proid' => $proid,'priceId' => $planid);
    //     }
    //     $subdata = $data['items']['data'];
    //     $flagstatus = 'no';
    //     $res = array();
    //     foreach ($subdata as $s) {
    //         if ($s['plan']['product'] == $planid) {
    //             $res['status'] = true;
    //             $res['subId'] = $s['id'];
    //             $res['quantity'] = $s['quantity'];
    //             $flagstatus = 'yes';
    //             break;
    //         }
    //     }
    //     if ($flagstatus == 'yes') {
    //         return $res;
    //     } else {
    //         $res['status'] = false;
    //         $res['price'] = $arr[$planid]['priceId'];
    //         return $res;
    //     }   
    // }

    public function createCheckout($db, $data) {
        $quntity = $data['quntity'];

        $planId = $this->getPlans($db,$data['plantype'],$quntity);
        // Recuring Plan Price ID
        $data['planproid'] = $planId['id']['proid'];
        $data['planpriceId'] = $planId['id']['priceId'];
        $priceId = $planId['id']['priceId'];
        // Create Customer in Stripe and get customer id from stripe
        $cusId = $this->createCustomer($data);
        // get Device product id and Price id [Note : Device Payment is only one time payment not Recuring Payment]
        $devdata = $planId['devId'];
        $data['devproId'] = $devdata['devproId'];
        $devpriceId = $devdata['devpriId'];
        $myJSON = [
            "plantype" => $data['plantype'],
            "adminname" => $data['adminname'],
            "comName" => $data['comName'],
            "adminemail" => $data['adminemail'],
            "admincontact" => $data['admincontact'],
            "userpass" => $data['userpass'],
            "quntity" => $data['quntity'],
            "planproid" => $data['planproid'],
            "planpriceId" => $data['planpriceId'],
            "devproId" => $devpriceId,
            "cusId" => $cusId,
        ];
        $taxId = "txr_1H1mZyHpxXHfgq9UyK6hXZk4";
        $jsondata = json_encode($myJSON);
        $checkout_session = $this->stripe->checkout->sessions->create([
            'success_url' => 'https://new.windsonpayroll.com/stripe/success.php',
            'cancel_url' => 'https://new.windsonpayroll.com/stripe/cancel.php',
            'payment_method_types' => ['card'],
            'customer' => $cusId,
            'line_items' => [
              [
                'price' => $priceId,
                'quantity' => $quntity,
                'tax_rates' => [$taxId]
              ],
            //   [
            //     'price' => $devpriceId,
            //     'quantity' => 1,
            //   ],
            ],
            'mode' => 'subscription',
            'metadata' => ['mode' => 'subscription','data' => $jsondata,'cusId' => $cusId]
          ]);
         echo $checkout_session['id'];
    }
}

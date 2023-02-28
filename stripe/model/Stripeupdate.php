<?php
include_once __DIR__.'/../../vendor/autoload.php';
class Stripeupdate{
    //Data for creating a customer
    private $stripe;
    private $customerName;
    private $customerAddress;
    private $email;
    private $phone;

    public function __construct(){
        $this->stripe = new \Stripe\StripeClient('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');
    }


    public function decSubscriptionqty($planid,$db) {
        $data = $this->getSubscriptionId($db);
        $subid = $data['subcriptionId'];
        $quntity = $data['totalemployee'];
        $res = $this->takeSubDecision($subid, $quntity, $planid);
        if ($res['status']) {
            if ($res['quantity'] > 0) {
                $qty = $res['quantity']-1;
                $incres = $this->stripe->subscriptionItems->update(
                    $res['subId'],
                    ['quantity' =>  $qty]
                );
                if($incres){
                    return true;
                }else{
                    return false;
                }
            }
        }
    }

    public function incSubscriptionqty($planid,$buyquntity,$db) {
        $data = $this->getSubscriptionId($db);
        $subid = $data['subcriptionId'];
        $quntity = $data['totalemployee'];
        $res = $this->takeSubDecision($subid, $quntity, $planid);
        if ($res['status']) {
            if ($res['quantity'] > 0) {
                $qty = $res['quantity'] + $buyquntity;
                $incres = $this->stripe->subscriptionItems->update(
                    $res['subId'],
                    ['quantity' =>  $qty]
                );
                if($incres){
                    return true;
                }else{
                    return false;
                }
            }
        }
    }

    public function getSubscriptionId($db) {
        $adminId = $_SESSION['admin_id'];
        $query = $db->query("SELECT * FROM company_admin WHERE admin_id =  '{$adminId}' ");
        $row = $query->fetch_assoc();
        return $row;
    }

    public function takeSubDecision($subid,$quntity, $planid) { // Stripe Plan Change and update Subscription
        $arr = array();
        $arr['prod_JAvtcwPjkqYvSk'] = array("planname" =>'advance','price1' => 'price_1IYa1ZHpxXHfgq9U6r7w1oul');
        $data = $this->stripe->subscriptions->retrieve(
            $subid,
            []
        );
        $res = array('proid' => 'prod_JAvtcwPjkqYvSk','priceId' => $planid);
        $subdata = $data['items']['data'];
        $flagstatus = 'no';
        $res = array();
        foreach ($subdata as $s) {
            if ($s['price']['id'] == $planid) {
                $res['status'] = true;
                $res['subId'] = $s['id'];
                $res['quantity'] = $s['quantity'];
                $flagstatus = 'yes';
                break;
            }
        }
        if ($flagstatus == 'yes') {
            return $res;
        } else {
            $res['status'] = false;
            $res['price'] = $planid;
            return $res;
        }
    }

    public function manageBilling() {
        $session = $this->stripe->billingPortal->sessions->create([
            'customer' => $_SESSION['stripe_id'],
//            'configuration' => 'bpc_1IJt9qF8XlusGvbDMUVbe5lt',
            'return_url' => 'https://new.windsonpayroll.com/plan.php',
        ]);
        echo json_encode($session);
    }

    public function fatchinvoice($data) {
        if ($data['last'] == '' || $data['last'] == null) {
            $array = [
                'customer' => $_SESSION['stripe_id'],
                'limit' => 10,
            ];
        } else {
            $array = [
                'customer' => $_SESSION['stripe_id'],
                'limit' => 10,
                'starting_after' => $data['last']
            ];
        }
        $invoices = $this->stripe->invoices->all($array);
        echo json_encode($invoices);

    }
}

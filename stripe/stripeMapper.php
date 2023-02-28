<?php
require_once 'model/Stripe.php';
class StripeMapper{
    private $obj;

    public function __construct(){
        $this->obj = new Stripe();
    }

    public function createCustomer($db, $data){
        return $this->obj->createCustomer($db, $data);
    }

    public function getPlans($db){
        return $this->obj->getPlans($db);
    }

    public function getAddons($db){
        return $this->obj->getAddons($db);
    }

    public function createCheckout($db, $data) {
        
        return $this->obj->createCheckout($db, $data);
    } 
}
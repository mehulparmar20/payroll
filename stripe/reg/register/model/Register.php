<?php
use Stripe\StripeClient;

require_once('./vendor/autoload.php');
require_once('./vendor/stripe/stripe-php/init.php');
require_once ('./app/Mail.php');
require_once ('./notification/utils/AuthJWT.php');
class Register
{
    private $id;
    private $firstname;
    private $lastname;
    private $companyname;
    private $phone_no;
    private $email;
    private $businesstype;
    private $terms_condition;
    private $username;
    private $password;
    private $country;
    private $state;
    private $town;
    private $address;
    private $zip;
    private $mcnumber;
    private $ffnumber;
    private $plan_id;
    private $token;
    private $createddate;
    private $planstartdate;
    private $planexpireddate;
    private $plantype;
    private $noofuser;
    private $discount;
    private $payment_status;
    private $otp;
    private $card_no;
    private $card_cvv;
    private $card_name;
    private $card_exdate;
    private $total_users;
    private $newpass;
    private $docid;
    private $usertype;
    private $stripeID;
    private $planname;

    public function getPlanName()
    {
        return $this->planname;
    }
    public function setPlanName($planname)
    {
        $this->planname = $planname;
    }

    public function getDocid()
    {
        return $this->docid;
    }
    public function setDocid($docid)
    {
        $this->docid = $docid;
    }
    public function getStripeID()
    {
        return $this->stripeID;
    }
    public function setStripeID($stripeID)
    {
        $this->stripeID = $stripeID;
    }
    public function getUsertype()
    {
        return $this->usertype;
    }
    public function setUsertype($usertype)
    {
        $this->usertype = $usertype;
    }
    public function getNewpass()
    {
        return $this->newpass;
    }
    public function setNewpass($newpass)
    {
        $this->newpass = $newpass;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return mixed
     */
    public function getCompanyname()
    {
        return $this->companyname;
    }

    /**
     * @param mixed $companyname
     */
    public function setCompanyname($companyname): void
    {
        $this->companyname = $companyname;
    }

    /**
     * @return mixed
     */
    public function getPhoneNo()
    {
        return $this->phone_no;
    }

    /**
     * @param mixed $phone_no
     */
    public function setPhoneNo($phone_no): void
    {
        $this->phone_no = $phone_no;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getBusinesstype()
    {
        return $this->businesstype;
    }

    /**
     * @param mixed $businesstype
     */
    public function setBusinesstype($businesstype): void
    {
        $this->businesstype = $businesstype;
    }

    /**
     * @return mixed
     */
    public function getTermsCondition()
    {
        return $this->terms_condition;
    }

    /**
     * @param mixed $terms_condition
     */
    public function setTermsCondition($terms_condition): void
    {
        $this->terms_condition = $terms_condition;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country): void
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function setState($state): void
    {
        $this->state = $state;
    }

    /**
     * @return mixed
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * @param mixed $town
     */
    public function setTown($town): void
    {
        $this->town = $town;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address): void
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @param mixed $zip
     */
    public function setZip($zip): void
    {
        $this->zip = $zip;
    }

    /**
     * @return mixed
     */
    public function getMcnumber()
    {
        return $this->mcnumber;
    }

    /**
     * @param mixed $mcnumber
     */
    public function setMcnumber($mcnumber): void
    {
        $this->mcnumber = $mcnumber;
    }

    /**
     * @return mixed
     */
    public function getFfnumber()
    {
        return $this->ffnumber;
    }

    /**
     * @param mixed $ffnumber
     */
    public function setFfnumber($ffnumber): void
    {
        $this->ffnumber = $ffnumber;
    }

    /**
     * @return mixed
     */
    public function getPlanId()
    {
        return $this->plan_id;
    }

    /**
     * @param mixed $plan_id
     */
    public function setPlanId($plan_id): void
    {
        $this->plan_id = $plan_id;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token): void
    {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getCreateddate()
    {
        return $this->createddate;
    }

    /**
     * @param mixed $createddate
     */
    public function setCreateddate($createddate): void
    {
        $this->createddate = $createddate;
    }

    /**
     * @return mixed
     */
    public function getPlanstartdate()
    {
        return $this->planstartdate;
    }

    /**
     * @param mixed $planstartdate
     */
    public function setPlanstartdate($planstartdate): void
    {
        $this->planstartdate = $planstartdate;
    }

    /**
     * @return mixed
     */
    public function getPlanexpireddate()
    {
        return $this->planexpireddate;
    }

    /**
     * @param mixed $planexpireddate
     */
    public function setPlanexpireddate($planexpireddate): void
    {
        $this->planexpireddate = $planexpireddate;
    }

    /**
     * @return mixed
     */
    public function getPlantype()
    {
        return $this->plantype;
    }

    /**
     * @param mixed $plantype
     */
    public function setPlantype($plantype): void
    {
        $this->plantype = $plantype;
    }

    /**
     * @return mixed
     */
    public function getNoofuser()
    {
        return $this->noofuser;
    }

    /**
     * @param mixed $noofuser
     */
    public function setNoofuser($noofuser): void
    {
        $this->noofuser = $noofuser;
    }

    /**
     * @return mixed
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @param mixed $discount
     */
    public function setDiscount($discount): void
    {
        $this->discount = $discount;
    }

    /**
     * @return mixed
     */
    public function getPaymentStatus()
    {
        return $this->payment_status;
    }

    /**
     * @param mixed $payment_status
     */
    public function setPaymentStatus($payment_status): void
    {
        $this->payment_status = $payment_status;
    }

    /**
     * @return mixed
     */
    public function getOtp()
    {
        return $this->otp;
    }

    /**
     * @param mixed $otp
     */
    public function setOtp($otp): void
    {
        $this->otp = $otp;
    }

    /**
     * @return mixed
     */
    public function getCardNo()
    {
        return $this->card_no;
    }

    /**
     * @param mixed $card_no
     */
    public function setCardNo($card_no): void
    {
        $this->card_no = $card_no;
    }

    /**
     * @return mixed
     */
    public function getCardCvv()
    {
        return $this->card_cvv;
    }

    /**
     * @param mixed $card_cvv
     */
    public function setCardCvv($card_cvv): void
    {
        $this->card_cvv = $card_cvv;
    }

    /**
     * @return mixed
     */
    public function getCardName()
    {
        return $this->card_name;
    }

    /**
     * @param mixed $card_name
     */
    public function setCardName($card_name): void
    {
        $this->card_name = $card_name;
    }

    /**
     * @return mixed
     */
    public function getCardExdate()
    {
        return $this->card_exdate;
    }

    /**
     * @param mixed $card_exdate
     */
    public function setCardExdate($card_exdate): void
    {
        $this->card_exdate = $card_exdate;
    }

    /**
     * @return mixed
     */
    public function getTotalUsers()
    {
        return $this->total_users;
    }

    /**
     * @param mixed $total_users
     */
    public function setTotalUsers($total_users): void
    {
        $this->total_users = $total_users;
    }

            public function createUser($db, $register, $helper)
            {
                $id = $register->getId();
                $fname = $register->getFirstname();
                $lname = $register->getLastname();
                $name = $fname . " " . $lname;
                $companyname = $register->getCompanyname();
                $phone_no = $register->getPhoneNo();
                $email = $register->getEmail();
                $password = hash('sha1', $register->getPassword());
                $plan = $register->getPlanId();
                $card_details = array();
                $plan_start = time();
                $plan_end = strtotime("+1 month", $plan_start);
                $noOfUser = 3;
                $comId = (int)$id;
                $db->companyAdmin->insertOne([
                    '_id' => (int)$id,
                    'firstName' => $fname,
                    'lastName' => $lname,
                    'companyName' => $companyname,
                    'companyEmail' => $email,
                    'companyContact' => $phone_no,
                    'companyAddress' => '',
                    'companyPassword' => $password,
                    'stripeID' => $this->stripeID,
                    'username' => '',
                    'planType' => $plan,
                    'country' => '',
                    'state' => '',
                    'town' => '',
                    'zip' => '',
                    'mcNumber' => '',
                    'ffNumber' => '',
                    'token' => '',
                    'subscription' => 0,
                    'planStart' => $plan_start,
                    'planEnd' => $plan_end,
                    'card_details' => $card_details,
                    'createddate' => $plan_start,
                    'noOfUser' => $noOfUser,
                    'remainingUser' => ($noOfUser - 1),
                ]);
                // insert admin as a user in user collections
                $userAdd = $this->addRegisterUser($register, $db, $helper);
                // add sub company
                $companyAdd = $this->addRegisterCompany($register, $db, $helper);
                // Create User Subscription
                $userSubscription = $this->addUserSubscription($register, $db, $helper);
                // add Equipment
                $this->addEquipment($register, $db, $helper);
                // add Load Type 
                $this->addLoadType($register, $db, $helper);
                // add Payment Types
                $this->addPaymentTerms($register, $db, $helper);
                // add Debit Bank
                $this->addBankDebit($register, $db, $helper);
                //  add bank credit
                $this->addBankCredit($register, $db, $helper);
                // add credit card
                $this->addCreditCard($register, $db, $helper);

                    $values = array(
                        "user_type" => 'admin',
                        "companyName" => $companyname,
                        "companyid" => $comId,
                        "username" => $fname,
                        "_id" => $userAdd[1],
                        "user_name" => $fname . " " . $lname
                    );

                    $string = $email . $name;
                    $authtoken = hash("sha1", $string);

                    $obj = new AuthJWT();
                    $jwtres = $obj->setJWT($values);
                    $auth = $jwtres['jwt'];
                // Welcome Email
                    $get_mail = new Mail();
                    $mail = $get_mail->set_mail();
                    $mail->setFrom('noreply@windsondispatch.com', 'Windson Dispatch');
                    $mail->addAddress($email);
                    $mail->isHTML(true); // Set email format to HTML
                    $mail->Subject = 'Welcome to Windson Dispatch';
                    $template = $get_mail->get_mailTemplate('welcome');
                    $template = str_replace("@name@", $name, $template);
                    $template = str_replace("@token@", $authtoken, $template);
                    $template = str_replace("@id@", $id, $template);
                    $template = str_replace("@email@", $email, $template);
                    $template = str_replace("@auth@", $auth, $template);
                    $mail->Body = $template;
                    $mail->send();
                    echo 'success';
            }
    
        public function addRegisterUser($register, $db, $helper) {
                  
            $id = $register->getId();
            $fname = $register->getFirstname();
            $lname = $register->getLastname();
            $name = $fname . " " . $lname;
            $companyname = $register->getCompanyname();
            $phone_no = $register->getPhoneNo();
            $email = $register->getEmail();
            $password = hash('sha1', $register->getPassword());
            $plan = $register->getPlanId();


            $string = $email . $name;
            $authtoken = hash("sha1", $string);
            // get last id from user
            $user_id = $helper->getNextSequence("users", $db);
            $planIdarray = ['trucker' => "601d16db5a246c4d17484492", 'broker' => "601d16fd9f96e273db425432", 'carrier' => "601d17194b858041727d25a2"];
            
            $getPlanData = $db->PlanInfo->aggregate([
                ['$match' => ['_id' => $planIdarray[$plan]]],
                ['$project' => ['privilege' => 1]]
                ]);
                foreach ($getPlanData as $maindata) {
    
                    // print_r($maindata['privilege']['admin']);
                    
                    // $arr = array('admin' => $maindata['privilege']['admin']);
                    // insert data to user collection
                    if($db->user->insertOne([
                        '_id' => (int)$user_id,
                        'counter' => 0,
                        'companyID' => $id,
                        'userEmail' => $email,
                        'companyName' => $companyname,
                        'userName' => '',
                        'userPass' => $password,
                        'userFirstName' => $fname,
                        'userLastName' => $lname,
                        'userAddress' => '',
                        'userLocation' => '',
                        'userZip' => '',
                        'userTelephone' => $phone_no,
                        'userExt' => 0,
                        'TollFree' => '',
                        'userFax' => '',
                        'privilege' => array(
                            'insertUser' => 1,
                            'updateUser' => 1,
                            'deleteUser' => 1,
                            'importUser' => 1,
                            'exportUser' => 1,
                        ),
                        'dashboard' => $maindata['privilege']['dashboard'],
                        'master' => $maindata['privilege']['master'],
                        'admin' => $maindata['privilege']['admin'],
                        'ifta' => $maindata['privilege']['ifta'],
                        'account' => $maindata['privilege']['account'],
                        'accountpayment' => $maindata['privilege']['accountpayment'],
                        'reports' => $maindata['privilege']['reports'],
                        'user_type' => 'admin',
                        'insertedTime' => time(),
                        'deleteStatus' => 0,
                        'mode' => 'day',
                        'otp' => '',
                        'auth_token' => $authtoken,
                        'emailVerificationStatus' => 0,
                    ])) {
                        return array('Success', $user_id);
                    } else {
                        return array('Error', $user_id);
                    }
                }

        }

        public function addRegisterCompany($register, $db, $helper) {
            
            $id = $register->getId();
            $fname = $register->getFirstname();//
            $lname = $register->getLastname();//
            $name = $fname . " " . $lname;//
            $companyname = $register->getCompanyname();//
            $phone_no = $register->getPhoneNo();//
            $email = $register->getEmail();//
            $comId = (int)$id;//

            $companyData = $db->company->insertOne(
                array('_id' => $helper->getNextSequence("company",$db),
                'companyID' => $comId,
                'counter' => 1,
                'company' => array(
                        array(
                            '_id' =>  0,
                            'companyName' => $companyname,
                            'shippingAddress' => '',
                            'telephoneNo' => $phone_no,
                            'faxNo' => '',
                            'mcNo' => '',
                            'usDotNo' => '',
                            'mailingAddress' => $email,
                            'factoringCompany' => '',
                            'bankCompany' => '',
                            'website' => '',
                            'counter' => 0,
                            'created_by' => $fname . $lname,
                            'created_time' => time(),
                            'file' => array(),
                            'status' => 'Yes',
                            'deleteStatus' => "NO",
                            'deleteUser' => "",
                            'deleteTime' => "",
                        )
                    )
                ) 
            );
        }

        public function addUserSubscription($register, $db, $helper) {

            $plan = $register->getPlanId();
            $id = $register->getId();

            $getPlan = $db->PlanInfo->findOne(['name' => $plan]);
                $planID = $getPlan['_id'];
                $price = $getPlan['discountprice'] != '0' ? $getPlan['discountprice'] : $getPlan['price'];

                $db->User_Subscription->insertOne([
                        '_id' => $helper->getNextSequence("User_Subscription", $db),
                        'companyID' => $id,
                        'planID' => $planID,
                        'addon' => [],
                        'planprice' => $price,
                        'duecharge' => 123,
                        'subscription_start' => time(),
                        'subscription_end' => strtotime('+1 month', time()),
                        'plan_switch' => 'NA',
                ]);
                // $addon = array(
                //     'planname' => '',
                //     'planID' => '',
                //     'startdate' => '',
                //     'enddate' => '',
                //     'status' => '', // active/ inactive
                //     'employeePrice' => '',
                //     'pricegivenby' => '',
                //     'counter' => 0 // remaning quota
                // );
        }

        public function addEquipment($register, $db, $helper) {
            $comid = $register->getId();
            $id = $helper->getNextSequence("equipmentcount", $db);
            $data = array(
                '_id'=> $id,
                'companyID'=>(int)$comid,
                'counter' => 9,
                'equipment' => array(
                    [
                        '_id' => 0,
                        'equipmentType' => 'FLATBED',
                        'counter' => 0,
                        'created_by' => 'System',
                        'created_time' => time(),
                        'deleteStatus' => "NO",
                        'deleteUser' => "",
                        'deleteTime' => "",
                    ],
                    [
                        '_id' => 1,
                        'equipmentType' => 'STEPDECK',
                        'counter' => 0,
                        'created_by' => 'System',
                        'created_time' => time(),
                        'deleteStatus' => "NO",
                        'deleteUser' => "",
                        'deleteTime' => "",
                    ],
                    [
                        '_id' => 2,
                        'equipmentType' => 'LOW BOY',
                        'counter' => 0,
                        'created_by' => 'System',
                        'created_time' => time(),
                        'deleteStatus' => "NO",
                        'deleteUser' => "",
                        'deleteTime' => "",
                    ],
                    [
                        '_id' => 3,
                        'equipmentType' => 'R49',
                        'counter' => 0,
                        'created_by' => 'System',
                        'created_time' => time(),
                        'deleteStatus' => "NO",
                        'deleteUser' => "",
                        'deleteTime' => "",
                    ],
                    [
                        '_id' => 4,
                        'equipmentType' => 'FO',
                        'counter' => 0,
                        'created_by' => 'System',
                        'created_time' => time(),
                        'deleteStatus' => "NO",
                        'deleteUser' => "",
                        'deleteTime' => "",
                    ],
                    [
                        '_id' => 5,
                        'equipmentType' => 'FLATBED 53',
                        'counter' => 0,
                        'created_by' => 'System',
                        'created_time' => time(),
                        'deleteStatus' => "NO",
                        'deleteUser' => "",
                        'deleteTime' => "",
                    ],
                    [
                        '_id' => 6,
                        'equipmentType' => 'VAN 53',
                        'counter' => 0,
                        'created_by' => 'System',
                        'created_time' => time(),
                        'deleteStatus' => "NO",
                        'deleteUser' => "",
                        'deleteTime' => "",
                    ],
                    [
                        '_id' => 7,
                        'equipmentType' => 'REEFER 53',
                        'counter' => 0,
                        'created_by' => 'System',
                        'created_time' => time(),
                        'deleteStatus' => "NO",
                        'deleteUser' => "",
                        'deleteTime' => "",
                    ],
                    [
                        '_id' => 8,
                        'equipmentType' => 'CONESTOGA',
                        'counter' => 0,
                        'created_by' => 'System',
                        'created_time' => time(),
                        'deleteStatus' => "NO",
                        'deleteUser' => "",
                        'deleteTime' => "",
                    ]
                )
            );
            $db->equipment_add->insertOne($data);
        }

        public function addLoadType($register, $db, $helper) {
            $id = $helper->getNextSequence("loadType", $db);
            $comid = $register->getId();
            $data = array(
                '_id' => (int)$id,
                'companyID' => (int)$comid,
                'counter' => 4,
                'loadType' => array(
                    [
                        '_id' => 0,
                        'loadName' => 'TL',
                        'loadType' => 'Yes',
                        'counter' => 0,
                        'created_by' => 'System',
                        'created_time' => time(),
                        'deleteStatus' => "NO",
                        'deleteUser' => "",
                        'deleteTime' => "",
                    ],
                    [
                        '_id' => 1,
                        'loadName' => 'LTL',
                        'loadType' => 'Yes',
                        'counter' => 0,
                        'created_by' => 'System',
                        'created_time' => time(),
                        'deleteStatus' => "NO",
                        'deleteUser' => "",
                        'deleteTime' => "",
                    ],
                    [
                        '_id' => 2,
                        'loadName' => 'TONU',
                        'loadType' => 'Yes',
                        'counter' => 0,
                        'created_by' => 'System',
                        'created_time' => time(),
                        'deleteStatus' => "NO",
                        'deleteUser' => "",
                        'deleteTime' => "",
                    ],
                    [
                        '_id' => 3,
                        'loadName' => 'LINE HAUL',
                        'loadType' => 'Yes',
                        'counter' => 0,
                        'created_by' => 'System',
                        'created_time' => time(),
                        'deleteStatus' => "NO",
                        'deleteUser' => "",
                        'deleteTime' => "",
                    ]
                )
            );
            $db->load_type->insertOne($data);
        }

        public function addPaymentTerms($register, $db, $helper) {
            $id = $helper->getNextSequence("payment_term", $db);
            $comid = $register->getId();
            $data = array(
                '_id' => (int)$id,
                'companyID' => (int)$comid,
                'counter' => 3,
                'payment' => array(
                    [
                        '_id' => 0, 
                        'paymentTerm' => 'NET 30 DAYS',
                        'counter' => 0,
                        'created_by' => 'System',
                        'created_time' => time(),
                        'deleteStatus' => "NO",
                        'deleteUser' => "",
                        'deleteTime' => "",
                    ],
                    [
                        '_id' => 1, 
                        'paymentTerm' => 'COD',
                        'counter' => 0,
                        'created_by' => 'System',
                        'created_time' => time(),
                        'deleteStatus' => "NO",
                        'deleteUser' => "",
                        'deleteTime' => "",
                    ],
                    [
                        '_id' => 2,
                        'paymentTerm' => '14 DAYS',
                        'counter' => 0,
                        'created_by' => 'System',
                        'created_time' => time(),
                        'deleteStatus' => "NO",
                        'deleteUser' => "",
                        'deleteTime' => "",
                    ]
                )
            );
            $db->payment_terms->insertOne($data);
        }

        public function addBankDebit($register, $db, $helper) {
            $id = $helper->getNextSequence("bank_debit_category", $db);
            $comid = $register->getId();
            $data = array(
                '_id'=> (int)$id,
                'companyID'=> (int)$comid,
                'counter' => 6,
                'bank_debit' => array(
                    [
                        '_id' => 0,
                        'bankName'=> 'INSURANCE',
                        'counter'=>0,
                        'created_by' => 'System',
                        'created_time' => time(),
                        'status'=>'No',
                        'deleteStatus' => "NO",
                        'deleteUser' => "",
                        'deleteTime' => "",
                    ],
                    [
                        '_id' => 1,
                        'bankName'=> 'EXPENSE',
                        'counter'=>0,
                        'created_by' => 'System',
                        'created_time' => time(),
                        'status'=>'No',
                        'deleteStatus' => "NO",
                        'deleteUser' => "",
                        'deleteTime' => "",
                    ],
                    [
                        '_id' => 2,
                        'bankName'=> 'FUEL',
                        'counter'=>0,
                        'created_by' => 'System',
                        'created_time' => time(),
                        'status'=>'No',
                        'deleteStatus' => "NO",
                        'deleteUser' => "",
                        'deleteTime' => "",
                    ],
                    [
                        '_id' => 3,
                        'bankName'=> 'LOAN PAYMENT',
                        'counter'=>0,
                        'created_by' => 'System',
                        'created_time' => time(),
                        'status'=>'No',
                        'deleteStatus' => "NO",
                        'deleteUser' => "",
                        'deleteTime' => "",
                    ],
                    [
                        '_id' => 4,
                        'bankName'=> 'PAYROLL',
                        'counter'=>0,
                        'created_by' => 'System',
                        'created_time' => time(),
                        'status'=>'No',
                        'deleteStatus' => "NO",
                        'deleteUser' => "",
                        'deleteTime' => "",
                    ],
                    [
                        '_id' => 5,
                        'bankName'=> 'REPAIR & MAINTENANCE',
                        'counter'=>0,
                        'created_by' => 'System',
                        'created_time' => time(),
                        'status'=>'No',
                        'deleteStatus' => "NO",
                        'deleteUser' => "",
                        'deleteTime' => "",
                    ]
                )
            );
            $db->bank_debit_category->insertOne($data);
        }

        public function addBankCredit($register, $db, $helper) {
            $id = $helper->getNextSequence("bank_credit_category", $db);
            $comid = $register->getId();
            $data = array(
                '_id'=> (int)$id,
                'companyID'=> (int)$comid,
                'counter' => 2,
                'bank_credit' => array(
                    [
                        '_id' => 0,
                        'bankName'=> 'SALES INCOME',
                        'counter'=>0,
                        'created_by' => 'System',
                        'created_time' => time(),
                        'deleteStatus' => "NO",
                        'deleteUser' => "",
                        'deleteTime' => "",
                    ],
                    [
                        '_id' => 0,
                        'bankName'=> 'REFUND',
                        'counter'=>0,
                        'created_by' => 'System',
                        'created_time' => time(),
                        'deleteStatus' => "NO",
                        'deleteUser' => "",
                        'deleteTime' => "",
                    ]
                )
            );
            $db->bank_credit_category->insertOne($data);
        }

        public function addCreditCard($register, $db, $helper) {
            $comid = $register->getId();
           $data =  array('_id'=> $this->id,
            'companyID'=>(int) $comid,
            'counter' => 5,
            'credit_card' => array(
                    [
                        '_id' => 0,
                        'cardName'=> 'INSURANCE',
                        'counter'=>0,
                        'created_by' => 'System',
                        'created_time' => time(),
                        'deleteStatus' => "NO",
                        'deleteUser' => "",
                        'deleteTime' => "",
                    ],
                    [
                        '_id' => 1,
                        'cardName'=> 'EXPENSE',
                        'counter'=>0,
                        'created_by' => 'System',
                        'created_time' => time(),
                        'deleteStatus' => "NO",
                        'deleteUser' => "",
                        'deleteTime' => "",
                    ],
                    [
                        '_id' => 2,
                        'cardName'=> 'STATE EXPENSE',
                        'counter'=>0,
                        'created_by' => 'System',
                        'created_time' => time(),
                        'deleteStatus' => "NO",
                        'deleteUser' => "",
                        'deleteTime' => "",
                    ],
                    [
                        '_id' => 3,
                        'cardName'=> 'TOLL',
                        'counter'=>0,
                        'created_by' => 'System',
                        'created_time' => time(),
                        'deleteStatus' => "NO",
                        'deleteUser' => "",
                        'deleteTime' => "",
                    ],
                    [
                        '_id' => 4,
                        'cardName'=> 'MISC EXPENSE',
                        'counter'=>0,
                        'created_by' => 'System',
                        'created_time' => time(),
                        'deleteStatus' => "NO",
                        'deleteUser' => "",
                        'deleteTime' => "",
                    ]
                )
            );
            $db->credit_card_category->insertOne($data);
        }
        
    // Email is exist or not
    public function emailExist($db,$register){
        $email = $register->getEmail();
        $data = $db->companyAdmin->findOne(['companyEmail' => $email]);
        if($data == ''){
            echo "nouser";
        }else{
            echo "user";
        }
    }


     // Email is exist or not
    public function VerifyEmail($db,$register){
        $email = $this->getEmail();
        $id = $this->getId();
        $token = $this->getToken();
        $data = $db->user->findOne(['companyID' => (int)$id,'userEmail' => $email, 'auth_token' => $token]);
        if($data['userEmail'] == $email && $data['auth_token'] == $token){
            $db->user->updateOne(['_id' => (int)$data['_id']],['$set' => ['emailVerificationStatus' => 1,]]);
            return "valid";
        }else{
            return "invalid";
        }
    }


    // Email is exist or not
    //     public function verifyOtp($db,$register){
    //         $id = $register->getId();
    //         $otp = $register->getOtp();
    //         $data = $db->user->findOne(['_id' => (int)$id,'otp' => (int)$otp,]);
    //         $time = time();
    //         if($data != ''){
    //             if($data['otp_extime'] > $time){
    //                 $_SESSION['user_type'] = $data['user_type'];
    //                 $_SESSION['company'] = 'user';
    //                 $_SESSION['companyName'] = $data['companyName'];
    //                 $_SESSION['userName'] = $data['userFirstName']." ".$data['userLastName'];
    //                 $_SESSION['userId'] = $data['_id'];
    //                 $_SESSION['companyId'] = $data['companyID'];
    //                 $_SESSION['companyPass'] = $data['userPass'];
    // 		        $_SESSION['adminname'] = $data['userFirstName']. " " .$data['userLastName'];
    //                 $_SESSION['theme'] = $data['mode'];
    //                 $_SESSION['lock'] = 'unset';
    // //                $_SESSION['ip'] = $data['ip'];
    //                 $_SESSION['privilege'] = json_encode($data['privilege']);
    //                 $_SESSION['master'] = json_encode($data['master']);
    //                 $_SESSION['reports'] = json_encode($data['reports']);
    //                 $_SESSION['admin'] = json_encode($data['admin']);
    //                 $_SESSION['ifta'] = json_encode($data['ifta']);
    //                 $_SESSION['account'] = json_encode($data['account']);
    //                 echo "user";
    //             }else{
    //                 echo "expired";
    //             }
    //         }else{
    //             echo "nouser";
    //         }
    //     }

    public function verifyOtp($db,$register){
        $id = $register->getId();
        $otp = $register->getOtp();
        $data = $db->user->findOne(['_id' => (int)$id,'otp' => (int)$otp,]);
        $company = $db->companyAdmin->findOne(['_id' => $data['companyID']]);
        $time = time();
        if($data != ''){
            if($data['otp_extime'] > $time){
                $obj = new AuthJWT();
                $values = array(
                    "user_type" => $data['user_type'],
                    "companyName" => $data['companyName'],
                    "companyID" => $data['companyID'],
                    "username" => $data['userPass'],
                    "userID" => $data['_id']
                );
                $jwtres = $obj->setJWT($values);
                $randomId = rand(100000,999999);
                $_SESSION['logid'] = $randomId;
                $onetimeid = $obj->setRandomid($randomId);
                $token = $jwtres['jwt'];
                $_SESSION['user_type'] = $data['user_type'];
                $_SESSION['company'] = 'user';
                $_SESSION['authtoken'] = $token;
                $_SESSION['onetimeid'] = $onetimeid;
                $_SESSION['companyName'] = $data['companyName'];
                $_SESSION['userName'] = $data['userFirstName']." ".$data['userLastName'];
                $_SESSION['userId'] = $data['_id'];
                $_SESSION['companyId'] = $data['companyID'];
                $_SESSION['companyPass'] = $data['userPass'];
                $_SESSION['adminname'] = $data['userFirstName']. " " .$data['userLastName'];
                $_SESSION['theme'] = $data['mode'];
                $_SESSION['lock'] = 'unset';
    //                $_SESSION['ip'] = $data['ip'];
                if(isset($data['dashboard'])) {
                    $_SESSION['dashboard'] = json_encode($data['dashboard']);
                }
                $_SESSION['privilege'] = json_encode($data['privilege']);
                $_SESSION['master'] = json_encode($data['master']);
                $_SESSION['reports'] = json_encode($data['reports']);
                $_SESSION['admin'] = json_encode($data['admin']);
                $_SESSION['ifta'] = json_encode($data['ifta']);
                $_SESSION['account'] = json_encode($data['account']);
                $_SESSION['expiry'] = json_encode($company['planEnd']);
                $_SESSION['stripe_id'] = $company['stripeID'];
                $_SESSION['plan'] = $company['planType'];
                $_SESSION['subscription'] = $company['subscription'];
                echo "user";
            }else{
                echo "expired";
            }
        }else{
            echo "nouser";
        }
    }

    // check login credetials
    //     public function loginRequest($db,$register){
    //         $email = $register->getEmail();
    //         $password = hash('sha1',$register->getPassword());
    //         $data = $db->user->findOne(['userEmail' => $email,
    //                                     'userPass' =>$password,
    //                                    ]);
    //         if($data == ''){
    //             $response = array(
    //                 'status' =>'invalid'
    //             );
    //             echo json_encode($response);
    //         }else{
    //             // get data from user
    //             $id = $data['_id'];
    //             $name = $data['userFirstName']." ".$data['userLastName'];
    //             $company_id  = $data['companyID'];
    //             $res = $db->companyAdmin->findOne(['_id' => (int)$company_id,]);
    //             $time = time();
    //             if($res != ''){
    // //                print_r($res);
    //                 if($res['planEnd'] > $time){
    //                     $otp = rand(100000,999999);
    //                     $time = time();
    //                     $extime = $time + 900;
    //                     $localIP = getHostByName(getHostName());
    //                     $db->user->updateOne(['_id' => (int)$id],['$set' => ['otp' => $otp,'otp_extime'=> $extime,'ip' => $localIP]]);
    //                     $get_mail = new Mail();
    //                     $mail = $get_mail->set_mail();
    //                     $mail->setFrom('noreply@windsondispatch.com', 'Windson Dispatch');
    //                     $mail->addAddress($email);
    //                     $mail->isHTML(true); // Set email format to HTML
    //                     $mail->Subject = 'OTP verification from Windson Dispatch';
    //                     $template = $get_mail->get_mailTemplate('otp_verification');
    //                     $template = str_replace("@name@", $name, $template);
    //                     $template = str_replace("@otp@", $otp, $template);
    //                     $mail->Body = $template;
    //                     $response = array(
    //                         'id' => $id,
    //                         'status' => 'valid'
    //                     );
    //                     $mail->send();
    //                     echo json_encode($response);

    //                 }else{
    //                     $response = array(
    //                         'status' =>'plan_expired'
    //                     );
    //                     echo json_encode($response);
    //                 }
    //             }
    //         }
    //     }
    public function loginRequest($db, $register)
    {
        $email = $register->getEmail();
        $password = hash('sha1', $register->getPassword());
        // echo $password;
        $data = $db->user->findOne([
            'userEmail' => $email,
            'userPass' => $password,
        ]);
        if ($data == '') {
            $response = array(
                'status' => 'invalid'
            );
            echo json_encode($response);
        } else {
            $flag = 1;
            if ($data['user_type'] == 'admin') {    //check user-type if admin and if account not verify. 
                if ($data['emailVerificationStatus'] == 1) {
                    $flag = 1;
                } else {
                    $name = $data['userFirstName'] . " " . $data['userLastName'];
                    $response = array(
                        'status' => 'Emailnotverify',
                        'email' => $email,
                        'id' => $data['companyID'],
                        'token' => $data['auth_token'],
                        'name' => $name,
                        '_id' => $data['_id'],
                        'user_type' => $data['user_type'],
                    );
                    $flag = 0;
                    echo json_encode($response);
                }
            }
            $optsendto = "";
            // get data from user
            if ($flag == 1) {   // here flag = 1 for all non-admin user and admin(for those who verify email)
                $id = $data['_id'];
                if ($data['deleteStatus'] == 0) {
                    $name = $data['userFirstName'] . " " . $data['userLastName'];
                    $company_id  = $data['companyID'];
                    $res = $db->companyAdmin->findOne(['_id' => (int)$company_id,]);
                    $time = time();
                    if ($res != '') {
                        if ($res['planEnd'] > $time) {
                            $get_mail = new Mail();
                            $mail = $get_mail->set_mail();
                            if(array_key_exists("loginmethod", $res)) { // if field is not there for frist time than else 
                                if ($res['loginmethod'] == 'admin') { // all user login via admin-EmailId(When admin select login via admin)
                                    // $mail->addAddress($res['loginemail']);
                                    // $optsendto = "admin";

                                    $dataEmail = (array)$res['loginemail'];
                                    // print_r($dataEmail);
                                    $emailSize = sizeof($dataEmail);
                                   
                                    $mail->addAddress($dataEmail[0]); //    in companyAdmin collection -> loginemail array has multiple emails but first email become main email and others are assign as Cc.
                                   if($emailSize >= 2) {
                                        for($i = 1 ; $i < $emailSize ; $i++) {
                                            $mail->addCC($dataEmail[$i]);
                                        }                
                                    }
                                    $optsendto = "admin";
                                    
                                } else {
                                    $mail->addAddress($email);
                                    $optsendto = "user";
                                }
                            }    
                             else {
                                $mail->addAddress($email);
                                $optsendto = "user";
                            }
                            $otp = rand(100000, 999999);
                            $time = time();
                            $extime = $time + 900;
                            $localIP = getHostByName(getHostName());
                            $db->user->updateOne(['_id' => (int)$id], ['$set' => ['otp' => $otp, 'otp_extime' => $extime, 'ip' => $localIP]]);
                            $mail->setFrom('noreply@windsondispatch.com', 'Windson Dispatch');
                            $mail->isHTML(true); // Set email format to HTML
                            $mail->Subject = 'OTP verification from Windson Dispatch';
                            $template = $get_mail->get_mailTemplate('otp_verification');
                            $template = str_replace("@name@", $name, $template);
                            $template = str_replace("@otp@", $otp, $template);
                            $mail->Body = $template;
                            $response = array(
                                'id' => $id,
                                'status' => 'valid',
                                'otpsendto' => $optsendto,
                                'otp' => $otp
                            );
                            $mail->send();
                            echo json_encode($response);
                        } else {
                            $response = array(
                                'status' => 'plan_expired'
                            );
                            echo json_encode($response);
                        }
                    }
                } else {
                    $response = array(
                        'status' => 'accountdeleted'
                    );
                    echo json_encode($response);
                }
            }
        }
    }
    public function registerUser($db,$register,$helper){
        // get value
        $id = $register->getId();
        $fname = $register->getFirstname();
        $lname = $register->getLastname();
        $name = $fname." ".$lname;
        $companyname = $register->getCompanyname();
        $phone_no = $register->getPhoneNo();
        $email = $register->getEmail();
        $business_type = $register->getBusinesstype();
        $username = $register->getUsername();
        $password = hash('sha1',$register->getPassword());
        $country = $register->getCountry();
        $state = $register->getState();
        $town = $register->getTown();
        $address = $register->getAddress();
        $zip = $register->getZip();
        $mc_number = $register->getMcnumber();
        $ff_number = $register->getFfnumber();
        $plan = $register->getPlanId();
        $noOfUser = $register->getTotalUsers();
        $time = time();
    // store data in database
        if($id == ''){
          $id = $helper->getNextSequence("registerUser",$db);
            $db->registeruser->insertOne([
                '_id' => $id,
                'companyName' => $companyname,
                'companyEmail' => $email,
                'companyContact' => $phone_no,
                'companyAddress' => $address,
                'companyPassword' => $password,
                'username' => $username,
                'planType' => $business_type,
                'country' => $country,
                'state' => $state,
                'town' => $town,
                'zip' => $zip,
                'mcNumber' => $mc_number,
                'ffNumber' => $ff_number,
                'plan' => $plan,
                'createddate' => $time,
                'noOfUser' => $noOfUser,
            ]);
            echo $id;
        }else{
            $db->registeruser->updateOne(['_id' => (int)$id],
                ['$set' => ['companyName' => $companyname,
                            'companyEmail' => $email,
                            'companyContact' => $phone_no,
                            'companyAddress' => $address,
                            'companyPassword' => $password,
                            'username' => $username,
                            'planType' => $business_type,
                            'country' => $country,
                            'state' => $state,
                            'town' => $town,
                            'zip' => $zip,
                            'mcNumber' => $mc_number,
                            'ffNumber' => $ff_number,
                            'plan' => $plan,
                            'createddate' => $time,
                            'noOfUser' => $noOfUser,
                           ]
                ]);
            echo $id;
        }
    }

    public function forgot($db,$register)
    {
        $email = $register->getEmail();
        $response = $db->user->findOne(['userEmail' => $email]);
        if($response) {
            $id = $response['_id'];
            $name = $response['userName'];
                $otp = rand(100000,999999);
                $time = time();
                $extime = $time + 900;
                $localIP = getHostByName(getHostName());
                $db->user->updateOne(['_id' => (int)$id],['$set' => ['otp' => $otp,'otp_extime'=> $extime,'ip' => $localIP]]);
                $get_mail = new Mail();
                $mail = $get_mail->set_mail();
                $mail->setFrom('noreply@windsondispatch.com', 'Windson Dispatch');
                $mail->addAddress($email);
                $mail->isHTML(true); // Set email format to HTML
                $mail->Subject = 'Reset Password from Windson Dispatch';
                $template = $get_mail->get_mailTemplate('password-change');
                $template = str_replace("@name@", $name, $template);
                $template = str_replace("@otp@", $otp, $template);
                $mail->Body = $template;
                $response = array(
                        'id' => $id,
                        'status' => 'valid'
                );
               if($mail->send()) {
                //    echo "mail Send";
               } else {
                //    echo "Error in Mail";
               }
                echo json_encode($response);
        } else {
            echo "Email Not Exits";
        }

    }

    public function verifyForgot($db,$register){
        $id = $register->getId();
        $otp = $register->getOtp();
        $data = $db->user->findOne(['_id' => (int)$id,'otp' => (int)$otp,]);
        $time = time();
        if($data != ''){
            if($data['otp_extime'] > $time) 
            {
                if($data['otp'] == $otp) {
                    echo "verify";
                } else {
                    echo "error";
                }
            } else{
                echo "expired";
            }
        } else{
            echo "nouser";
        }
    }

    public function changePass($db,$register){
        $id = $register->getId();
        $userpass = hash('sha1',$register->getNewpass());
        $res = $db->user->updateOne(['_id' => (int)$id],
                                        ['$set' => ['userPass' => $userpass]
                                   ]);
        if($res) {
            echo "success";
        } else {
            echo "fail";
        }
    }
    public function emailVerify($db, $register)
    {
        $name = $this->getCompanyname();
        $authtoken = $this->getPassword();
        $id = $this->getId();
        $email = $this->getEmail();
        $usertype = $this->getUsertype();
        $docid = $this->getDocid();
        $obj = new AuthJWT();
        $values = array(
            "companyid" => $id,
            "_id" => $docid,
            "user_type" => $usertype,
            "user_name" => $name
        );
        $jwtres = $obj->setJWT($values);
        $auth = $jwtres['jwt'];
        $get_mail = new Mail();
        $mail = $get_mail->set_mail();
        $mail->setFrom('noreply@windsondispatch.com', 'Windson Dispatch');
        $mail->addAddress($email);
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Welcome to Windson Dispatch';
        $template = $get_mail->get_mailTemplate('welcome');
        $template = str_replace("@name@", $name, $template);
        $template = str_replace("@token@", $authtoken, $template);
        $template = str_replace("@id@", $id, $template);
        $template = str_replace("@email@", $email, $template);
        $template = str_replace("@auth@", $auth, $template);
        $mail->Body = $template;
        $mail->send();
        echo 'success';
    }
    public function changeEmailStatus($db, $register)
    {
        $docid = $this->getDocid();
        $cid = $this->getId();
        $res = $db->user->updateOne(
            ['_id' => (int)$docid],
            ['$set' => ['emailVerificationStatus' => 1]]
        );
        if ($res) {
            echo "Success";
        } else {
            echo "Error";
        }
    }

}
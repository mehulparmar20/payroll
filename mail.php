<?php
require_once 'vendor/autoload.php';
use \Mailjet\Resources;
class Mail{
    public function  sentApiMail($email, $name ,$template, $subject){
        $mj = new \Mailjet\Client('bb86d6d33a71b268fbbd7827783f2dc4', '531055093b7bf3ffafc0ecd6ff9cbd5a', true, ['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "support@windsonpayroll.com",
                        'Name' => "Windson Payroll"
                    ],
                    'To' => [
                        [
                            'Email' => $email,
                            'Name' => $name
                        ]
                    ],
                    // 'Cc' => [
                    //     $cc
                    // ],
                    'Subject' => $subject,
                    'HTMLPart' => $template,
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        if($response->success()){
            return true;
        }else{
            return false;
        }
    }

    public function  sentPHPMailer(){

    }

    public function getEmailtemplate($name){
        $content = file_get_contents("email-template/".$name.".php");
        return $content;
    }
}
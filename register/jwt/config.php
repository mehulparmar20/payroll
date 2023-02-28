<?php
class Keys{
    public function getkeys(){
        // show error reporting
        error_reporting(E_ALL);

        // set your default time-zone
        date_default_timezone_set('UTC');

        $time  = time();
        $extime = $time + 86400;
        $keys = array(
                        'key' => "60d735000d6ce5885bdaca694d03ec9bf8195a24",
                        'iss' => "http://new.windsonpayroll.com",
                        'aud' => "http://app.windsonpayroll.com",
                        'iat' => $time,
                        'nbf' => $time,
                        'exp' => $extime,
                    );
        return $keys;
    }
}

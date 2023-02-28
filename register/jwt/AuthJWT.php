<?php

include_once("config.php");
include_once("./vendor/firebase/php-jwt/src/BeforeValidException.php");
include_once("./vendor/firebase/php-jwt/src/ExpiredException.php");
include_once("./vendor/firebase/php-jwt/src/SignatureInvalidException.php");
include_once("./vendor/firebase/php-jwt/src/JWT.php");
use \Firebase\JWT\JWT;

class AuthJWT
{
    // For Encrypt
    public function setJWT($data, $exttime){
        $obj = new Keys();
        $keys = $obj->getkeys();
        $notbefore_claim = time() + 100;
        $token = array(
            "iss" => $keys['iss'],
            "aud" => $keys['aud'],
            "iat" => $keys['iat'],
            "nbf" => $notbefore_claim,
            "exp" => $exttime,
            "data" => $data,
        );
        // generate jwt
        $jwt = JWT::encode($token, $keys['key']);
        $resdata = array(
            "message" => "success",
            "jwt" => $jwt
        );
        return $resdata;
    }
// For Decrypt
    public function getJwt($data){
        $obj = new Keys();
        $keys = $obj->getkeys();
        try {
            // decode jwt
            $decoded = JWT::decode($data, $keys['key'], array('HS256'));
            // show user details
            echo json_encode(array(
                "message" => "success.",
                "data" => $decoded->data
            ));

        }catch (Exception $e){
            // set response code
            // tell the user access denied  & show error message
            echo json_encode(array(
                "message" => "Access denied.",
                "error" => $e->getMessage()
            ));
        }
    }

    public function setRandomid($random)
    {
        return bin2hex($random);
    }
// For Decrypt
    public function getRandomid($random)
    {
        return hex2bin($random);
    }

    // only for master
    public function getJwtmaster($data){
        $obj = new Keys();
        $keys = $obj->getkeys();
        try {
            // decode jwt
            $decoded = JWT::decode($data, $keys['key'], array('HS256'));
            // show user details
            return json_encode(array(
                "message" => "*@Access Gr@nted By Windson Dispatch@*",
                "data" => $decoded->data
            ));
        }catch (Exception $e){
            // set response code
            // tell the user access denied  & show error message
            return json_encode(array(
                "message" => "Access denied.",
                "error" => $e->getMessage()
            ));
        }
    }

}
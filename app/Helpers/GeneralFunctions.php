<?php
/**
 * Created by PhpStorm.
 * User: Stephen
 * Date: 05/13/2020
 * Time: 12:55 PM
 */

namespace App\Helpers;
use App\Conf\Config;
use App\ReferralInvites;


class GeneralFunctions
{
    //
    private $log;
    function __construct()
    {
        $this->log = new CustomLogger();
    }
    public function basicAuthentication($data)
    {
        $username = null;
        $password = null;

// mod_php
        if (isset($_SERVER['PHP_AUTH_USER'])) {
            $username = $_SERVER['PHP_AUTH_USER'];
            $password = $_SERVER['PHP_AUTH_PW'];

// most other servers
        } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {

            if (strpos(strtolower($_SERVER['HTTP_AUTHORIZATION']),'basic')===0)
                list($username,$password) = explode(':',base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));

        }

        if (is_null($username)) {

            header('WWW-Authenticate: Basic realm="My Realm"');
            header('HTTP/1.0 401 Unauthorized');
            $resp=false;
        } else {
            if ($username ==Config::MOJOX_USER && $password == Config::MOJOX_PASSWORD)
            {
                $resp=true;
            }else
            {
                $resp=false;
            }
        }
        $resp = true;
        return $resp;
    }
    public function sendSMS($message,$msisdn,$time)
    {
        if(Config::ENABLE_SMS)
        {
            $url = Config::SMS_API;
            $data = array(
                "apikey" => Config::API_KEY,
                "partnerID" => Config::PARTNER_ID,
                "message" => $message,
                "shortcode" => Config::SHORT_CODE,
                "mobile" => $msisdn
//                "timeToSend" => $time
            );
            $headers = array("Content-Type:application/json");

            $payload = json_encode($data);
            $response = $this->generateCurlRequest($url,$payload,$headers);
            $this->log->mojoxInfoLogger->info("MSISDN: ".$msisdn." FUNCTION :".__METHOD__." ".__LINE__."The response from sms gateway is ".$response);

            return $response;
        }else
        {

            $this->log->mojoxInfoLogger->info("MSISDN: ".$msisdn." FUNCTION :".__METHOD__." ".__LINE__." Sending Confirmation sms deactivated");

        }
    }

    public function generateCurlRequest($url, $params, $headers){
        try{
            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $url );
            curl_setopt( $ch, CURLOPT_POST, true );
            curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $params);
            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        }catch(Exception $ex){

            $this->log->mojoxInfoLogger->info("Exception while invoking $url " . $ex->getMessage());
        }
    }

    //
    /**
     * Function generates a random activation key
     * @param $keyLength
     * @return int activation code
     */
    public function generateActivationKey($keyLength)
    {
        return substr(str_shuffle("0123456789"),0,$keyLength);
    }

    /**
     * Function generates a random activation key
     * @param $keyLength
     * @return int activation code
     */
    public function generateReferralCode($keyLength)
    {
        $code = substr(str_shuffle("0123456789"),0,$keyLength);
        $referralCode = "P".$code;
        $referralInvites = ReferralInvites::where(['referralCode' => $referralCode])->limit(1)->get();
        if(count($referralInvites)>0)
        {
            $this->generateReferralCode(3);
        }
        return $referralCode;
    }

    public function curlDate()
    {
        date_default_timezone_set("Africa/Nairobi");
        return date('Y-m-d H:i:s');
    }

}

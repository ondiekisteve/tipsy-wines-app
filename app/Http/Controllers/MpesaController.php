<?php

namespace App\Http\Controllers;

use App\Conf\Config;
use App\Helpers\CustomLogger;
use App\Helpers\GeneralFunctions;
use App\TransactionRequestLogs;
use App\Transactions;
use App\Wallet;
use App\WalletHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MpesaController extends Controller
{
    private $log;
    private $functions;

    function __construct()
    {
        $this->log = new CustomLogger();
        $this->functions = new GeneralFunctions();
    }

    /**
     * Lipa na M-PESA password
     * */
    public function lipaNaMpesaPassword()
    {
        $lipa_time = Carbon::rawParse('now')->format('YmdHms');
        $passkey = Config::PASS_KEY;
        $BusinessShortCode = Config::BUSINESS_SHORT_CODE;
        $timestamp = $lipa_time;
        $lipa_na_mpesa_password = base64_encode($BusinessShortCode . $passkey . $timestamp);
        return $lipa_na_mpesa_password;
    }

    /**
     * Lipa na M-PESA STK Push method
     * */
    public function customerMpesaSTKPush(Request $request)
    {
        date_default_timezone_set("Africa/Nairobi");

        $this->log->tipsyInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
            "We are hitting safaricom at ".Carbon::now(Config::DEFAULT_TIMEZONE));
        try {
            $data = $request->json()->all();
            $auth = $this->functions->basicAuthentication($data);
            $curlDate = $this->functions->curlDate();
            if ($auth) {
                if (isset($data['MSISDN']) && isset($data['amount'])) {

                    if ($this->verifySafaricomPhoneNo($data['MSISDN'])) {
                        $amount = $data['amount'];
                        $MSISDN = $data['MSISDN'];
                        if ($amount > Config::MINI_AMOUNT && $amount < Config::MAXIMUM_AMOUNT) {
                            $url = Config::STK_PUSH_URL;
                            $curl = curl_init();
                            curl_setopt($curl, CURLOPT_URL, $url);
                            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer ' . $this->generateAccessToken()));
                            $curl_post_data = [
                                //Fill in the request parameters with valid values
                                'BusinessShortCode' => Config::BUSINESS_SHORT_CODE,
                                'Password' => $this->lipaNaMpesaPassword(),
                                'Timestamp' => Carbon::rawParse('now')->format('YmdHms'),
                                'TransactionType' => Config::TRANSACTION_TYPE,
                                'Amount' => $amount,
                                'PartyA' => $MSISDN, // replace this with your phone number
                                'PartyB' => Config::BUSINESS_SHORT_CODE,
                                'PhoneNumber' => $MSISDN, // replace this with your phone number
                                'CallBackURL' => Config::CONFIRMATION_URL_API,
                                'AccountReference' => $MSISDN,
                                'TransactionDesc' => "Loading Mojox Power Bank Wallet"
                            ];
                            $data_string = json_encode($curl_post_data);
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($curl, CURLOPT_POST, true);
                            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
                            $curl_response = curl_exec($curl);
                            $stkresp = json_decode($curl_response, true);
                            $this->log->tipsyInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                                "ResponseCode " . $stkresp['ResponseCode'] . " and merchantRequestID is " . $stkresp['MerchantRequestID']. "Receiving Response at ".Carbon::now(Config::DEFAULT_TIMEZONE));

                            if ($stkresp['ResponseCode'] == Config::SUCCESSFUL_STK_PUSH_CODE) {
                                TransactionRequestLogs::create([
                                    "MSISDN" => $MSISDN,
                                    "amount" => $amount,
                                    "merchantRequestID" => $stkresp['MerchantRequestID'],
                                    "checkoutRequestID" => $stkresp['CheckoutRequestID'],
                                    "responseCode" => $stkresp['ResponseCode'],
                                    "responseDescription" => $stkresp['ResponseDescription'],
                                    "customerMessage" => $stkresp['CustomerMessage'],
                                    "status" => Config::INACTIVE,
                                    "dateCreated" => $curlDate
                                ]);
                                $resp = array(
                                    "status" => Config::SUCCESSFULLY_PROCESSED_REQUEST,
                                    "message" => "Heads up! Payment initiated successfully, Enter your Mpesa PIN to complete transaction "
                                );

                            } else {
                                $resp = array(
                                    "status" => Config::INVALID_PAYLOAD,
                                    "message" => "Error Occurred while initiating payment, try again"
                                );
                            }
                        } else {
                            $resp = array(
                                "status" => Config::INVALID_PAYLOAD,
                                "message" => "Enter amount between Ksh." . Config::MINI_AMOUNT . ' and Ksh' . Config::MAXIMUM_AMOUNT
                            );
                        }
                    } else {
                        $resp = array(
                            "status" => Config::INVALID_PAYLOAD,
                            "message" => "Nominate a Safaricom number to complete the request"
                        );
                    }
                } else {
                    $resp = array(
                        "status" => Config::INVALID_PAYLOAD,
                        "message" => "Invalid Payload, Error Occurred"
                    );
                }
            } else {
                $resp = array(
                    "status" => Config::INVALID_AUTHENTICATION_CREDENTIALS,
                    "message" => "Invalid authentication credentials, Error Occurred"
                );
            }
        } catch (\Exception $e) {
            $resp = array(
                "status" => Config::GENERIC_EXCEPTION_CODE,
                "message" => Config::GENERIC_EXCEPTION_MESSAGE
            );
            $this->log->tipsyInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "Mpesa STK push error exception is " . $e->getMessage());
        }

        return json_encode($resp);
    }

    /**
     * Generate access token
     * */
    public function generateAccessToken()
    {
        $consumer_key = Config::CONSUMER_KEY;
        $consumer_secret = Config::CONSUMER_SECRET;
        $credentials = base64_encode($consumer_key . ":" . $consumer_secret);
        $url = Config::MPESA_ACCESS_TOKEN_URL;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Basic " . $credentials));
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $curl_response = curl_exec($curl);
        $access_token = json_decode($curl_response);
        return $access_token->access_token;
    }

    /**
     * J-son Response to M-pesa API feedback - Success or Failure
     */
    public function createValidationResponse($result_code, $result_description)
    {
        $result = json_encode(["ResultCode" => $result_code, "ResultDesc" => $result_description]);
        $response = new Response();
        $response->headers->set("Content-Type", "application/json; charset=utf-8");
        $response->setContent($result);
        return $response;
    }

    /**
     *  M-pesa Validation Method
     * Safaricom will only call your validation if you have requested by writing an official letter to them
     */
    public function validation(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $this->log->tipsyDebugLogger->debug("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
            "Payload recieved for validation request " . json_encode($data));
        $result_code = "0";
        $result_description = "Accepted validation request.";
        return $this->createValidationResponse($result_code, $result_description);
    }

    /**
     * M-pesa Transaction confirmation method, we save the transaction in our databases
     */
    public function stkConfirmationCallback(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $this->log->tipsyDebugLogger->debug("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
            "Yes it works >> payload received is>> " . json_encode($data));

        try {

            $curlDate = $this->functions->curlDate();
            $stkCallback = $data['Body']['stkCallback'];
            $resultCode = $stkCallback['ResultCode'];
            $checkoutRequestID = $stkCallback['CheckoutRequestID'];
            $merchantRequestID = $stkCallback['MerchantRequestID'];
            $resultDesc = $stkCallback['ResultDesc'];

            if ($resultCode == Config::TRANSACTION_SUCCESS_CODE) {
                $callBackResponses = $data['Body']['stkCallback']['CallbackMetadata']['Item'];
                $MSISDN = NULL;
                $amount = 0;
                $mpesaReceiptNumber = NULL;
                $balance = 0;
                $transactionDate = NULL;

                $this->log->tipsyDebugLogger->debug("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                    "merchantRequestID is  " . $merchantRequestID);

                foreach ($callBackResponses as $callBackResponse) {
                    if ($callBackResponse['Name'] == 'Amount') {
                        $amount = isset($callBackResponse['Value']) ? $callBackResponse['Value'] : 0.00;
                    }
                    if ($callBackResponse['Name'] == 'MpesaReceiptNumber') {
                        $mpesaReceiptNumber = isset($callBackResponse['Value']) ? $callBackResponse['Value'] : NULL;
                    }
                    if ($callBackResponse['Name'] == 'Balance') {
                        $balance = isset($callBackResponse['Value']) ? $callBackResponse['Value'] : 0.00;
                    }
                    if ($callBackResponse['Name'] == 'TransactionDate') {
                        $transactionDate = isset($callBackResponse['Value']) ? $callBackResponse['Value'] : '';
                    }
                    if ($callBackResponse['Name'] == 'PhoneNumber') {
                        $MSISDN = isset($callBackResponse['Value']) ? $callBackResponse['Value'] : NULL;
                    }
                }
                $transactions = Transactions::where(['mpesaReceiptNumber' => $mpesaReceiptNumber])->where('dateCreated', '>', Carbon::now(Config::DEFAULT_TIMEZONE)->subMinute(Config::QUERY_TRANSACTIONS_EVERY))->limit(1)->get();

                if(count($transactions) == 0)
                {
                    $transactionID = Transactions::insertGetId([
                        "MSISDN" => $MSISDN,
                        "amount" => $amount,
                        "accountNumber" => $MSISDN,
                        "mpesaReceiptNumber" => $mpesaReceiptNumber,
                        "balance" => $balance,
                        "transactionDate" => $transactionDate,
                        "merchantRequestID" => $merchantRequestID,
                        "checkoutRequestID" => $checkoutRequestID,
                        "resultCode" => $resultCode,
                        "resultDesc" => $resultDesc,
                        "transactionType" => "STK Push",
                        "status" => Config::INACTIVE,
                        "dateCreated" => $curlDate
                    ]);

                    if ($transactionID > 0) {
                        $wallet = Wallet::where(["MSISDN" => $MSISDN])->limit(1)->get();
                        $currentAmount = 0;
                        if (count($wallet) > 0) {

                            foreach ($wallet as $item) {
                                $currentAmount = $item['amount'];
                            }
                            $finalAmount = $currentAmount + $amount;
                            Wallet::where(["MSISDN" => $MSISDN])->update([
                                "amount" => $finalAmount,
                                "previousAmount" => $currentAmount,
                                "dateModified" => $curlDate
                            ]);
                        } else {
                            Wallet::create([
                                "MSISDN" => $MSISDN,
                                "amount" => $amount,
                                "previousAmount" => 0,
                                "dateCreated" => $curlDate
                            ]);
                        }

                        WalletHistory::create([
                            "MSISDN" => $MSISDN,
                            "transactionID" => $transactionID,
                            "amount" => $amount,
                            "previousAmount" => $currentAmount,
                            "transactionStatus" => 0,
                            "dateModified" => $curlDate,
                            "transactionType" => Config::DEBIT
                        ]);

                    }
                } else
                {
                    Transactions::where(['mpesaReceiptNumber' => $mpesaReceiptNumber])->update([
                        "merchantRequestID" => $merchantRequestID,
                        "checkoutRequestID" => $checkoutRequestID,
                        "resultCode" => $resultCode,
                        "resultDesc" => $resultDesc,
                        "dateModified" => $curlDate
                    ]);
                }
                // Responding to the confirmation request
                // $response = new Response();

                //$response->headers->set("Content-Type", "text/xml; charset=utf-8");
                // $response->setContent(json_encode(["C2BPaymentConfirmationResult" => "Success"]));
            } else {
                $transactionRequestLogs = TransactionRequestLogs::where(['checkoutRequestID' => $checkoutRequestID])->limit(1)->get();
                if (count($transactionRequestLogs) > 0) {
                    $MSISDN = NULL;
                    $amount = 0;
                    foreach ($transactionRequestLogs as $transactionRequestLog) {
                        $MSISDN = $transactionRequestLog['MSISDN'];
                        $amount = $transactionRequestLog['amount'];
                    }

                    Transactions::create([
                        "MSISDN" => $MSISDN,
                        "accountNumber" => $MSISDN,
                        "amount" => $amount,
                        "mpesaReceiptNumber" => NULL,
                        "balance" => 0,
                        "transactionDate" => $curlDate,
                        "merchantRequestID" => $merchantRequestID,
                        "checkoutRequestID" => $checkoutRequestID,
                        "resultCode" => $resultCode,
                        "resultDesc" => $resultDesc,
                        "transactionType" => "STK Push",
                        "status" => Config::INACTIVE,
                        "dateCreated" => $curlDate
                    ]);

                    // $response->headers->set("Content-Type", "text/xml; charset=utf-8");
                    // $response->setContent(json_encode(["C2BPaymentConfirmationResult" => "Reject"]));
                }
            }

        } catch (\Exception $e) {
            $this->log->tipsyDebugLogger->debug("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "an exception occurred is " . $e->getMessage());
        }
        // return $response;
    }

    /**
     * M-pesa Register Validation and Confirmation method
     */
    public function registerUrls(Request $request)
    {
        $data = $request->json()->all();
        $auth = $this->functions->basicAuthentication($data);
        $curlDate = $this->functions->curlDate();
        $resp = array();
        if ($auth) {
            if (isset($data['ShortCode']) && isset($data['ResponseType']) && isset($data['ConfirmationURL']) && isset($data['ValidationURL'])) {
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, Config::REGISTER_URL_API);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization: Bearer ' . $this->generateAccessToken()));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                $resp = curl_exec($curl);

            } else {
                $resp = array(
                    "status" => Config::INVALID_PAYLOAD,
                    "message" => "Invalid payload"
                );
            }
        } else {
            $resp = array(
                "status" => Config::INVALID_AUTHENTICATION_CREDENTIALS,
                "message" => "Invalid authentication credentials, Error Occurred"
            );
        }
        return $resp;
    }

    public function confirmationCallback(Request $request)
    {
        date_default_timezone_set("Africa/Nairobi");
        $data = json_decode($request->getContent(), true);
        $curlDate = $this->functions->curlDate();
        $this->log->tipsyDebugLogger->debug("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
            "Response from mpesa paybill is " . json_encode($data));

        $transactionType = $data['TransactionType'];
        $transID = $data['TransID'];
        $transactionDate = $data['TransTime'];
        $amount = $data['TransAmount'];
        $businessshortcode = $data['BusinessShortCode'];
        $accountNumber = $data['MSISDN'];
        try{
            $transactions = Transactions::where(['mpesaReceiptNumber' => $transID])->where('dateCreated', '>', Carbon::now(Config::DEFAULT_TIMEZONE)->subMinute(Config::QUERY_TRANSACTIONS_EVERY))->limit(1)->get();
        }catch (\Exception $e)
        {
            $this->log->tipsyDebugLogger->debug("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception that occurred is  " . $e->getMessage());
        }
        if (count($transactions) == 0) {
            if (isset($data['BillRefNumber'])) {
                $accountNumber = $data['BillRefNumber'];
                $accountNumber = (int)$accountNumber;
                $accountNumber = str_replace("+", "", $accountNumber);
                if (substr($accountNumber, 0, 3) != Config::DEFAULT_COUNTRY_CODE) {
                    $accountNumber = Config::DEFAULT_COUNTRY_CODE . $accountNumber;
                }
            }
            $invoiceno = $data['InvoiceNumber'];
            $MSISDN = $data['MSISDN'];
            $balance = $data['OrgAccountBalance'];
            $firstname = $data['FirstName'];
            $middleName = $data['MiddleName'];
            $lastName = $data['LastName'];

            $transactionID = Transactions::insertGetId([
                "MSISDN" => $MSISDN,
                "amount" => $amount,
                "accountNumber" => $accountNumber,
                "mpesaReceiptNumber" => $transID,
                "balance" => $balance,
                "transactionDate" => $transactionDate,
                "merchantRequestID" => $invoiceno,
                "checkoutRequestID" => $invoiceno,
                "transactionType" => "Pay Bill",
                "firstName" => $firstname,
                "middleName" => $middleName,
                "lastName" => $lastName,
                "status" => Config::INACTIVE,
                "dateCreated" => $curlDate
            ]);

            if ($transactionID > 0) {
                $wallet = Wallet::where(["MSISDN" => $accountNumber])->limit(1)->get();
                $currentAmount = 0;
                if (count($wallet) > 0) {

                    foreach ($wallet as $item) {
                        $currentAmount = $item['amount'];
                    }
                    $finalAmount = $currentAmount + $amount;
                    Wallet::where(["MSISDN" => $accountNumber])->update([
                        "amount" => $finalAmount,
                        "previousAmount" => $currentAmount,
                        "dateModified" => $curlDate
                    ]);
                } else {
                    Wallet::create([
                        "MSISDN" => $accountNumber,
                        "amount" => $amount,
                        "previousAmount" => 0,
                        "dateCreated" => $curlDate
                    ]);
                }

                WalletHistory::create([
                    "MSISDN" => $accountNumber,
                    "transactionID" => $transactionID,
                    "amount" => $amount,
                    "previousAmount" => $currentAmount,
                    "transactionStatus" => 0,
                    "dateModified" => $curlDate,
                    "transactionType" => Config::DEBIT
                ]);

            }
        }else
        {
            Transactions::where(['mpesaReceiptNumber' => $transID])->update([
                "firstName" => $data['FirstName'],
                "middleName" => $data['MiddleName'],
                "lastName" => $data['LastName'],
                "dateModified" => $curlDate
            ]);
        }

    }

    public function verifySafaricomPhoneNo($MSISDN)
    {

        if (!preg_match(Config::SAFARICOM_REGEX, $MSISDN)) {
            return false;
        } else {
            return true;
        }
    }
}

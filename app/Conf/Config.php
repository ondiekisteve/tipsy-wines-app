<?php


namespace App\Conf;


class Config
{
    //Log Variables
    const TIPSY_APP_NAME = "TIPSY";
    const TIPSY_DEBUG = "C:\\xampp\\htdocs\\logs\\debug.log";
    const TIPSY_INFO = "C:\\xampp\\htdocs\\logs\\info.log";
    const TIPSY_ERROR = "C:\\xampp\\htdocs\\logs\\error.log";
    const TIPSY_FATAL = "C:\\xampp\\htdocs\\logs\\fatal.log";


    /**
     * MNO mobile number regexes
     */
    const SAFARICOM_REGEX = "/^(254)((([7|2])([0|1|2|4|9|5])([0-9]{7}))|(76)([89]{1})([0-9]{6})|((110)|(111))([0-9]{6}))$/i";
    const AIRTEL_REGEX = "/^(254)(([7]([385]{1}([0-9]{7})|([6][2]{1})([0-9]{6}))|((100)|(101)|(102))([0-9]{6})))$/i";
    const TELKOM_REGEX = "/^(25477|020|040|050|060|066)([0-9]{7})$/i";

    //Tipsy status codes
    const SUCCESSFULLY_PROCESSED_REQUEST = 1000;
    const INVALID_AUTHENTICATION_CREDENTIALS =1001;
    const INVALID_PAYLOAD = 1002;
    const GENERIC_EXCEPTION_CODE= 1003;
    const RECORD_NOT_FOUND_CODE= 1004;
    const RECORD_ALREADY_PROCESSED= 1005;
    const GENERIC_EXCEPTION_MESSAGE= "The service is currently unavailable, try again later";
    const ACTIVE = 1;
    const INACTIVE = 0;


    const DEFAULT_COUNTRY_CODE = 254;

    const QUERY_TRANSACTIONS_EVERY = 5;

    const CURRENCY = 'KES';

    const CONSUMER_KEY="REIJcSkXAk7khXKtjwqcZmomJ62b0Fa5";

    const CONSUMER_SECRET="DHt93RhyBRGWuqEG";

    const MPESA_ACCESS_TOKEN_URL = "https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials";

    const PASS_KEY = "a614666c44dcd840614573f2b2422a61ea8549eafdb4d96235877c17d6ff7032";
    const BUSINESS_SHORT_CODE = 174379;
    const STK_PUSH_URL ="https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest";
//    const TRANSACTION_TYPE ="Buy Goods";
    const TRANSACTION_TYPE ="CustomerBuyGoodsOnline";
    const REGISTER_URL_API = "https://api.safaricom.co.ke/mpesa/c2b/v1/registerurl";
    const CONFIRMATION_URL_API = "https://www.hirola.africa/api/v1/stkConfirmationCallback";
    const VALIDATION_URL_API = "https://www.hirola.africa/api/v1/validationCallback";
    const COMPLETED_RESPONSE = "Cancelled";


}

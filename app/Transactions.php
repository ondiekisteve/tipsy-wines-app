<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    //
    protected $connection = "mysql";
    protected $table = "transactions";
    protected $primaryKey = "transactionID";
    protected $fillable = ['MSISDN','accountNumber','amount','mpesaReceiptNumber','balance','transactionDate','merchantRequestID','checkoutRequestID','resultCode','resultDesc','status','firstName','middleName','lastName','businessShortCode','transactionType','dateModified','dateCreated'];
    public $timestamps= false;
}

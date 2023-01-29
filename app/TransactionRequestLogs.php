<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionRequestLogs extends Model
{
    //
    protected $connection = "mysql";
    protected $table = "transactionRequestLogs";
    protected $primaryKey = "transactionRequestLogID";
    protected $fillable = ['MSISDN','amount','merchantRequestID','checkoutRequestID','responseCode','responseDescription','customerMessage','status','dateModified','dateCreated'];
    public $timestamps= false;
}

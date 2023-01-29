<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WalletHistory extends Model
{
    //
    protected $connection = "mysql";
    protected $table = "walletHistory";
    protected $primaryKey = "walletHistoryID";
    protected $fillable = ['MSISDN','stationID','transactionID','amount','previousAmount','transactionStatus','transactionType','dateModified','dateCreated'];
    public $timestamps= false;
}

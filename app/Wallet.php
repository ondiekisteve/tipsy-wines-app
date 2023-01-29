<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    //
    protected $connection = "mysql";
    protected $table = "wallet";
    protected $primaryKey = "walletID";
    protected $fillable = ['MSISDN','amount','previousAmount','dateModified','dateCreated'];
    public $timestamps= false;
}

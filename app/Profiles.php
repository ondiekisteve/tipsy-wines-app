<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profiles extends Model
{
    //
    protected $connection = "mysql";
    protected $table = "profiles";
    protected $primaryKey = "profileID";
    protected $fillable = ['MSISDN','pin','referralCode','surname','firstName','middleName','email','dob','gender','idNumber','referralActivationStatus','registrationChannel','creditBalance','loyaltyBalance','isVerified','dateModified','dateCreated'];
    public $timestamps= false;
}



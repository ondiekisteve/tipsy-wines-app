<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Specification extends Model
{
    protected $connection = "mysql";
    protected $table = "specifications";
    protected $primaryKey = "id";
    protected $fillable = ["productID","attribute","value","description","status","createdBy","modifiedBy","dateModified","dateCreated"];
    public $timestamps= false;
}

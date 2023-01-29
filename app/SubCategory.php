<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $connection = "mysql";
    protected $table = "sub_categories";
    protected $primaryKey = "id";
    protected $fillable = ["categoryID","name","description","status","createdBy","modifiedBy","dateModified","dateCreated"];
    public $timestamps= false;
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $connection = "mysql";
    protected $table = "products";
    protected $primaryKey = "id";
    protected $fillable = ["categoryID","subCategoryID","name","price","quantity","discount","description","status","createdBy","modifiedBy","dateModified","dateCreated"];
    public $timestamps= false;
}

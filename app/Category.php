<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $connection = "mysql";
    protected $table = "categories";
    protected $primaryKey = "id";
    protected $fillable = ["name","description","status","createdBy","modifiedBy","dateModified","dateCreated"];
    public $timestamps= false;
}

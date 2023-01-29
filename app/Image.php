<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $connection = "mysql";
    protected $table = "images";
    protected $primaryKey = "id";
    protected $fillable = ["productID","name","mimeType","size","isResized","url","description","status","createdBy","modifiedBy","dateModified","dateCreated"];
    public $timestamps= false;
}

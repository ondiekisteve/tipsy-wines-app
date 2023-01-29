<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('productID')->nullable();
            $table->string('name')->nullable();
            $table->string('mimeType')->nullable();
            $table->bigInteger('size')->nullable();
            $table->tinyInteger('isResized')->nullable();
            $table->string('url')->nullable();
            $table->string('description')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->bigInteger('modifiedBy')->nullable();
            $table->bigInteger('createdBy')->nullable();
            $table->dateTime('dateModified')->nullable();
            $table->timestamp('dateCreated')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('images');
    }
}

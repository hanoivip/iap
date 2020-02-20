<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientIaps extends Migration
{
    public function up()
    {
        Schema::create('client_iaps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('client_id');
            $table->string('merchant_id')->comment('');
            $table->boolean('disable')->default(false);
            $table->string('merchant_title');
            $table->string('merchant_image');
            $table->string('price')->comment('Default currency USD');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('client_iaps');
    }
}

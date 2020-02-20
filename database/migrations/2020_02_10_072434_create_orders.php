<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrders extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order');
            $table->integer('client_id');
            $table->string('client_order');
            $table->string('item');
            $table->string('item_price');
            $table->string('payment_type')->default("");
            $table->string('payment_id')->default("");
            $table->integer('payment_result')->default(0);
            $table->integer('callback_result')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}

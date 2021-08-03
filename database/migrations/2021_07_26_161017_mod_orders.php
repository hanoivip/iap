<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModOrders extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('user');
            $table->string('server');
            $table->string('role');
            $table->dropColumn('client_order');
            $table->dropColumn('payment_type');
            $table->dropColumn('payment_id');
            $table->dropColumn('payment_result');
            $table->dropColumn('callback_result');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('client_order')->default("");
            $table->string('payment_type')->default("");
            $table->string('payment_id')->default("");
            $table->integer('payment_result')->default(0);
            $table->integer('callback_result')->default(0);
            $table->dropColumn('user');
            $table->dropColumn('server');
            $table->dropColumn('role');
        });
    }
}

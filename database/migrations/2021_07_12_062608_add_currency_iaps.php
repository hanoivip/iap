<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCurrencyIaps extends Migration
{
    public function up()
    {
        Schema::table('client_iaps', function (Blueprint $table) {
            $table->string('currency')->default('');
        });
    }

    public function down()
    {
        Schema::table('client_iaps', function (Blueprint $table) {
            $table->dropColumn('currency');
        });
    }
}

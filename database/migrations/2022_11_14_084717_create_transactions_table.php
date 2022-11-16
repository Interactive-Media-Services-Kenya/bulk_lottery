<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('msisdn');
            $table->double('amount',12,2);
            $table->string('result_desc')->nullable();
            $table->string('reference')->nullable();
            $table->string('merchant_request_id')->nullable();
            $table->string('result_code')->nullable();
            $table->string('checkout_request_id')->nullable();
            $table->string('transaction_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};

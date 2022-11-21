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
        Schema::create('transaction_customers', function (Blueprint $table) {
            $table->id();
            $table->string('msisdn')->nullable();
            $table->string('amount')->nullable();
            $table->string('mpesa_trx_time')->nullable();
            $table->string('reference')->nullable();
            $table->string('mpesa_transaction_date')->nullable();
            $table->string('mpesa_account')->nullable();
            $table->string('mpesa_sender')->nullable();
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
        Schema::dropIfExists('transaction_customers');
    }
};

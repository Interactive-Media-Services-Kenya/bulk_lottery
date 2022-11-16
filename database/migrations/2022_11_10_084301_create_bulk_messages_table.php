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
        Schema::create('bulk_messages', function (Blueprint $table) {
            $table->id();
            $table->longText('message')->nullable();
            $table->foreignId('client_id')->nullable()->constrained();
            $table->unsignedBigInteger('originator')->nullable(); // Number Sending the message
            $table->unsignedBigInteger('destination')->nullable(); // Number receiving the message
            $table->foreignId('brand_id')->nullable()->constrained();
            $table->foreignId('campaign_id')->nullable()->constrained();
            $table->unsignedBigInteger('sender_id')->nullable();
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
        Schema::dropIfExists('bulk_messages');
    }
};

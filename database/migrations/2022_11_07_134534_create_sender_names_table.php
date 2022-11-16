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
        Schema::create('sender_names', function (Blueprint $table) {
            $table->id();
            $table->string('short_code');
            $table->bigInteger('sdpaccesscode');
            $table->bigInteger('spid');
            $table->bigInteger('sdpserviceid');
            $table->string('serviceprovider');
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
        Schema::dropIfExists('sender_names');
    }
};

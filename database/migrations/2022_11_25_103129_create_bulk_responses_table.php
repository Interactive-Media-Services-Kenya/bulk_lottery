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
        Schema::create('bulk_responses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('message')->nullable();
            $table->string('correlator')->nullable();
            $table->string('balance')->nullable();
            $table->string('user')->nullable();
            $table->foreignId('client_id')->nullable()->constrained();
            $table->foreignId('message_id')->nullable()->constrained();
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
        Schema::dropIfExists('bulk_responses');
    }
};

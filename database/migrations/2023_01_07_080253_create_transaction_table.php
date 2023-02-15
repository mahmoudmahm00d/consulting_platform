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
            $table->unsignedBigInteger('from_wallet')->nullable();
            $table->unsignedBigInteger('to_wallet');
            $table->foreign('from_wallet')->references('id')->on('wallets')->onDelete('cascade');
            $table->foreign('to_wallet')->references('id')->on('wallets')->onDelete('cascade');
            $table->string('type');
            $table->unsignedBigInteger('appointment_id')->nullable();
            $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('cascade');
            $table->float('amount');
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
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['from_wallet']);
            $table->dropForeign(['to_wallet']);
            $table->dropForeign(['appointment_id']);
        });

        Schema::dropIfExists('transactions');
    }
};

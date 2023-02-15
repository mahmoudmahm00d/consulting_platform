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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('specialist_id');
            $table->unsignedBigInteger('specialize_id')->nullable();
            $table->unsignedBigInteger('schedule_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('specialist_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('specialize_id')->references('id')->on('specializes')->onDelete('set null');
            $table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('set null');
            $table->date('date');
            $table->time('start_at');
            $table->time('finish_at');
            $table->string('status');
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
            $table->dropForeign(['user_id']);
            $table->dropForeign(['specialist_id']);
        });

        Schema::dropIfExists('appointments');
    }
};

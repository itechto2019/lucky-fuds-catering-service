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
        Schema::create('reserves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('package_id')->nullable();
            $table->string('client');
            $table->string('contact');
            $table->string('email');
            $table->string('method');
            $table->date('date');
            $table->time('time');
            $table->string('address');
            $table->string('guest');
            $table->string('event');
            $table->string('status')->nullable()->default('pending');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('CASCADE');
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
        Schema::dropIfExists('reserves');
    }
};

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
        Schema::create('user_reserves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_info_id')->constrained('user_infos')->cascadeOnDelete();
            $table->foreignId('package_id')->constrained('packages')->cascadeOnDelete();
            $table->string('contact');
            $table->string('email');
            $table->date('date');
            $table->time('time');
            $table->string('address');
            $table->string('guest');
            $table->string('event');
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
        Schema::dropIfExists('user_reserves');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
 
    public function up()
    {
        Schema::create('user_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('profile')->nullable()->default("user.png");
            $table->string('name');
            $table->string('contact');
            $table->string('email');
            $table->string('address');
            $table->string('method');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('user_infos');
    }
};

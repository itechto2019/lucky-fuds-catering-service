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
        Schema::create('admin_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->longText('profile')->nullable()->default("https://storage.googleapis.com/lucky-fuds-catering-service.appspot.com/profile/user.png?GoogleAccessId=firebase-adminsdk-vjtb9%40lucky-fuds-catering-service.iam.gserviceaccount.com&Expires=4824937523&Signature=TDcSRfCsg89ELHHvb%2Bvna5LlfBt6AXFzu1vEl62CVFAapmVvYeK1DAr%2BK0rIuArNBs20gH1R3RL8Te2K0EYwoIN2AZmjXOqXLMsDfkxGLo87MyYcvWLKRhw6EeYw0HzL3AyhB%2Fo1ejpcfaUWZM%2BebZeRFiGsuyv0sCfSOoOLnue%2BOoX8qFmX8KJu4yC%2Bosi7FeHnc1WgiN5ZVKYkWEi3HydEfr%2B1C435cm7NJEG7qW77kJljDYSb6ZmfzsAQ0kKedGZG4SkP2oCedxyMBVcUcO24GQy0AEMuyAcnA9AYyF9Ygef%2B1k6H3uArqkWwK8mQQsgC61LcJXDdt8yga4v3nA%3D%3D");
            $table->text('temp_name')->nullable(true);
            $table->string('name')->nullable()->default('');
            $table->string('contact')->nullable()->default('');
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
        Schema::dropIfExists('admin_infos');
    }
};

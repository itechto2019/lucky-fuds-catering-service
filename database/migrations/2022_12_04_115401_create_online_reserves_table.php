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
        Schema::create('online_reserves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_payment')->constrained('reservation_payments')->cascadeOnDelete();
            $table->foreignId('user_reserve')->constrained('user_reserves')->cascadeOnDelete();
            $table->string('payment_status')->nullable()->default(false);
            $table->bigInteger('reference')->nullable();
            $table->text('image')->nullable();
            $table->text('temp_name')->nullable();
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
        Schema::dropIfExists('online_reserves');
    }
};

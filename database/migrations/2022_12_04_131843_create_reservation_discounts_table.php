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
        Schema::create('reservation_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_reserve')->constrained('user_reserves')->cascadeOnDelete();
            $table->text('variant')->nullable()->default('full payment');
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
        Schema::dropIfExists('reservation_discounts');
    }
};

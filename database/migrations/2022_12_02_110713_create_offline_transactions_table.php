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
        Schema::create('offline_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction')->constrained('transactions')->cascadeOnDelete();
            $table->foreignId('user_rent')->constrained('user_rents')->cascadeOnDelete();
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
        Schema::dropIfExists('offline_transactions');
    }
};

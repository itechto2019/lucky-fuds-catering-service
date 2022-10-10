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
        Schema::create('for_rents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_id')->constrained('stocks');
            $table->integer('quantity');
            $table->boolean('is_rented')->nullable()->default(true);
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('for_rents');
    }
};

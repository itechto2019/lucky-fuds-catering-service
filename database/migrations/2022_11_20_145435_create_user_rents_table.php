<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('user_rents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_info_id')->constrained('user_infos')->cascadeOnDelete();
            $table->foreignId('stock_id')->constrained('stocks')->cascadeOnDelete();
            $table->foreignId('for_rent_id')->constrained('for_rents')->cascadeOnDelete();
            $table->decimal('amount', 8, 2);
            $table->date('date');
            $table->date('return');
            $table->string('status')->nullable()->default('pending');
            $table->boolean('is_returned')->nullable()->default(false);
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
        Schema::dropIfExists('user_rents');
    }
};

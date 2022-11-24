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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->longText('image')->nullable()->default("https://storage.googleapis.com/lucky-fuds-catering-service.appspot.com/stocks/no_image.png?GoogleAccessId=firebase-adminsdk-vjtb9%40lucky-fuds-catering-service.iam.gserviceaccount.com&Expires=4824937900&Signature=TbIfl44dgxo03A8EBy6WAMuou9u51MNetotjZ0cNU8IOdb0ZZKAf1YbJ3hL131dAh8bA%2B1TapqleN0ujRfT6wCeyfLyjHSGvHw4BsgyhR641Y6W9e1vF8UPLxCMmWDCGVC%2F%2FV56bt%2FOXOeJ6VVY7NKFqP6j14Kf%2FAZ1NSbt7OVukxjL%2Bz7TVUdl%2BdPlGcnv9SeX%2F8ouEQiPy5HOKA9Yw2fZn4WO3z2aZP8VkmfOBhTZlp2P32olqa%2B9hjstze8%2FpaJ8HbHjteIFqeHoEh60FGtb9GuTR%2BCuCW%2FFmwOU7a5y3%2FEA6VS0LmRcHJRKLFOvQ%2FkSKOGsLoUO1aZe6f7PUag%3D%3D");
            $table->string('item');
            $table->integer('quantity')->nullable()->default(0);
            $table->decimal('price', 8, 2)->nullable()->default(0);
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
        Schema::dropIfExists('stocks');
    }
};

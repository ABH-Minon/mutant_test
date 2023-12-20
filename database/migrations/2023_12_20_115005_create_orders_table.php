<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration {
    public function up() {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('productID');
            $table->string('productName');
            $table->decimal('productPrice', 8, 2);
            $table->unsignedBigInteger('userID');
            $table->integer('quantity');
            $table->string('status')->default('1');
            $table->timestamps();

            $table->foreign('productID')->references('id')->on('products');
            $table->foreign('userID')->references('id')->on('users');
        });
    }

    public function down() {
        Schema::dropIfExists('orders');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShoppingItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopping_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('shopping_item_id')->unique();
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('shopping_id')->constrained('shoppings');
            $table->double('total')->default(0);
            $table->integer('cardboard')->nullable();
            $table->double('wholesaleprice')->nullable();
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
        Schema::dropIfExists('shopping_items');
    }
}

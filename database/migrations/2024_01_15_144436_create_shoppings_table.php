<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShoppingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shoppings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('shopping_id')->unique();
            $table->string('shopping_number')->nullable();
            $table->string('product_name');
            $table->double('buying_price');
            $table->double('selling_price');
            $table->bigInteger('quantity')->default(0);
            $table->double('total')->default(0);
            $table->text('description')->nullable();
            $table->date('shopping_date')->default(now());
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
        Schema::dropIfExists('shoppings');
    }
}

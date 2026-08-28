<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->longText('description');
            $table->bigInteger('product_quantity')->nullable();
            $table->bigInteger('product_mrp');
            $table->foreignId('category_id');
            $table->foreignId('subcategory_id');
            $table->longText('slug');
            $table->string('brands');
            $table->string('types');
            $table->string('unit');
            $table->integer('peices_per_pack');
            $table->integer('carton_size');
            $table->string('main_category')->nullable();
            $table->string('sub_category')->nullable();
            $table->string('varieties');
            $table->integer('cost_per_item');
            $table->integer('gst');
            $table->integer('sale_price_loose_pcs');
            $table->integer('half_carton_price');
            $table->integer('sale_price_carton');
            $table->integer('product_weight_grams');
            $table->string('status');
            $table->string('tags');
            $table->longText('image')->nullable();
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
        Schema::dropIfExists('products');
    }
}

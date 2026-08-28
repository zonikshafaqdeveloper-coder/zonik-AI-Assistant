<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKycCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kyc-customers', function (Blueprint $table) {
            $table->id();
            $table->string('outlet_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->unique();
            $table->string('delivery_address');
            $table->string('city');
            $table->string('state');
            $table->string('pincode');
            $table->string('delivery_time');
            $table->string('document_type');
            $table->string('document_number');
            $table->string('document_image');
            $table->rememberToken();
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
        Schema::dropIfExists('kyc-customers');
    }
}

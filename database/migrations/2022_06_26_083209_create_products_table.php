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
        Schema::create('products', function (Blueprint $table) {
            //product id
            $table->id();
            //product name with length 100
            $table->string('name',100);
            //product price in double
            $table->double('price');
            //product category id foreign key from categories table
            $table->string('category',100);
            //product description field with text type and may be blank
            $table->text('description')->nullable();
            //product avatar field with string length 100 and have default value public/images/default.png if not provided
            $table->string('avatar',100)->default('public/images/default.png');
            //boolean status with default value 1 for active and inactive records
            $table->boolean('status')->default(1);
            //created and updated at timestamps
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
};

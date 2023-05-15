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
        if(!Schema::hasTable('items')){
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('user',255);
            $table->string('name',255);
            $table->string('category',128);
            $table->text('description')->nullable();
            $table->integer('quantity')->default(1)->unsigned();
            $table->timestamps();
        });
    }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
};

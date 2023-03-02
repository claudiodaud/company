<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConditionQuoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('condition_quote', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('condition_id');
            $table->foreign('condition_id')->references('id')->on('conditions')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('quote_id');
            $table->foreign('quote_id')->references('id')->on('quotes')->onUpdate('cascade')->onDelete('cascade');            
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
        Schema::dropIfExists('condition_quote');
    }
}

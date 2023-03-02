<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('customers', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('company_id');
        //     $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade')->onDelete('cascade');

        //     //Company info
        //     $table->string('social_name');
        //     $table->string('fantasy_name')->nullable();
        //     $table->string('dni')->nullable();
        //     $table->string('logo_photo_path')->nullable();
        //     $table->text('detail');

        //     $table->softDeletes();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('customers');
    }
}

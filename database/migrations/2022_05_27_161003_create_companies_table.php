<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            
            $table->id();
            
            //Company info
            $table->string('social_name');
            $table->string('fantasy_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('web')->nullable();
            $table->string('adress')->nullable();            
            $table->string('dni')->nullable();
            $table->string('logo')->nullable();

            //Bank account info
            $table->string('headline_name')->nullable();            
            $table->string('bank_name')->nullable();            
            $table->string('type_account')->nullable();
            $table->string('account_number')->nullable();
            $table->string('notification_email')->nullable();

            $table->string('detail')->nullable();

            $table->softDeletes();
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
        Schema::dropIfExists('companies');
    }
}

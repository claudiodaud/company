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
            $table->boolean('type')->default(0);
            $table->string('social_name');
            $table->string('fantasy_name')->nullable();
            $table->string('dni')->nullable();
            $table->string('logo_photo_path')->nullable();
            $table->text('detail');

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

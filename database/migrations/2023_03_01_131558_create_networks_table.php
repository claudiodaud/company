<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNetworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('networks', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('net_id');
            $table->foreign('net_id')->references('id')->on('nets')->onUpdate('cascade')->onDelete('cascade');
                    
            $table->string('link',500);
            $table->text('detail');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('networkable', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('network_id');
            $table->unsignedBigInteger('networkable_id');
            $table->string('networkable_type');
    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('networks');
        Schema::dropIfExists('networkable');
    }
}

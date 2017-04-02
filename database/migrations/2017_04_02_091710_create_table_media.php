<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMedia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media', function($table) {
            $table->increments('id');
            $table->string('label')->nullable();
            $table->string('type')->nullable();
            $table->string('source')->nullable();
            $table->string('length')->nullable();
            $table->string('path')->nullable();
            $table->timestamps();
        });

        Schema::create('resource_containers_media', function($table) {
            $table->increments('id');
            $table->integer('media_id');
            $table->integer('resource_container_id');
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
        //
    }
}

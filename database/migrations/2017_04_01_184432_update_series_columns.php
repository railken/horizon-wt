<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSeriesColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('series', function($table) {
            $table->text('aliases')->nullable();
            $table->string('network')->nullable();
            $table->float('runtime')->nullable();
            $table->integer('rating')->default(0);
            $table->integer('rating_count')->default(0);
        });

        Schema::create('tags', function($table) {
            $table->increments('id');
            $table->string('name');
            $table->text('aliases');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('resource_containers_tags', function($table) {
            $table->increments('id');
            $table->integer('tag_id');
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

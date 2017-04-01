<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEpisodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('episodes', function($table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->text('overview')->nullable();
            $table->string('number');
            $table->string('season_number')->nullable();
            $table->integer('season_id')->nullable();
            $table->integer('series_id');
            $table->datetime('aired_at')->nullable();
            $table->timestamps();
        });

        Schema::table('resource_containers', function($table) {
            $table->datetime('database_updated_at')->nullable();
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

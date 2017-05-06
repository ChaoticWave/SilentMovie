<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTmdbEntityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::create('tmdb_entity',
            function(Blueprint $table) {
                $table->increments('id');
                $table->string('name', 255);
                $table->boolean('adult')->default(false);
                $table->string('profile_path', 255)->nullable();
                $table->string('tmdb_id', 255);
                $table->text('known_for')->nullable();
                $table->text('response')->nullable();
                $table->timestamp('ingested_at')->nullable();
                $table->timestamps();

                //  A unique index
                $table->unique('tmdb_id', 'ixu_tmdb_id');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::dropIfExists('tmdb_entity');
    }
}

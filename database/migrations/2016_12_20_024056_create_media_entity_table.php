<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaEntityTable extends Migration
{
    //******************************************************************************
    //* Methods
    //******************************************************************************

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::create('sm_entity',
            function(Blueprint $table) {
                $table->increments('id');
                $table->string('source_id_text', 255);
                $table->smallInteger('source_nbr')->default(0);
                $table->string('name_text', 255)->nullable();
                $table->string('desc_text', 255)->nullable();
                $table->string('episode_title_text', 255)->nullable();
                $table->string('title_text', 255)->nullable();
                $table->string('title_desc_text', 1024)->nullable();
                $table->text('extra_text')->nullable();
                $table->string('response_type_text', 64);

                $table->timestamps();

                //  A unique index
                $table->unique(['source_nbr', 'source_id_text'], 'ixu_source_source_id');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::dropIfExists('sm_people');
    }
}

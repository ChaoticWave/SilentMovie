<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaQueryTable extends Migration
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
        \Schema::create('sm_query',
            function(Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->string('query_text', 255);
                /** @noinspection PhpUndefinedMethodInspection */
                $table->smallInteger('source_nbr')->default(0);
                /** @noinspection PhpUndefinedMethodInspection */
                $table->string('index_id_text', 128)->nullable();
                $table->string('response_type_text', 64);
                /** @noinspection PhpUndefinedMethodInspection */
                $table->text('response_text')->nullable();
                $table->timestamp('response_date');
                $table->timestamps();

                //  A unique index
                $table->unique(['user_id', 'source_nbr', 'query_text'], 'ixu_user_source_query');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::dropIfExists('sm_query');
    }
}

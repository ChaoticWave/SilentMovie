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
                $table->string('response_type_text', 64);
                /** @noinspection PhpUndefinedMethodInspection */
                $table->text('response_text')->nullable();
                $table->timestamp('response_date');
                $table->timestamps();

                //  A unique index
                $table->unique(['user_id', 'source_nbr'], 'ixu_source_source_id');
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

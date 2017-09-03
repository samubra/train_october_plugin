<?php namespace Samubra\Train\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSamubraTrainLookup extends Migration
{
    public function up()
    {
        Schema::create('samubra_train_lookup', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name', 200)->nullable();
            $table->string('code', 150)->nullable();
            $table->string('type')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('samubra_train_lookup');
    }
}

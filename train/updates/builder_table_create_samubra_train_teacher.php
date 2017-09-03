<?php namespace Samubra\Train\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSamubraTrainTeacher extends Migration
{
    public function up()
    {
        Schema::create('samubra_train_teacher', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name', 50);
            $table->string('identity', 30);
            $table->string('certificate_no', 50);
            $table->string('job_title');
            $table->string('phone');
            $table->string('company');
            $table->integer('edu_id')->unsigned();
            $table->string('photo')->nullable();
            $table->integer('type_id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('samubra_train_teacher');
    }
}

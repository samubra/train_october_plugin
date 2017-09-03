<?php namespace Samubra\Train\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSamubraTrainCourse extends Migration
{
    public function up()
    {
        Schema::create('samubra_train_course', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('title');
            $table->smallInteger('course_type')->unsigned()->default(1);
            $table->integer('default_teacher_id')->nullable()->unsigned();
            $table->decimal('default_hours', 10, 1)->default(4.0);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('samubra_train_course');
    }
}
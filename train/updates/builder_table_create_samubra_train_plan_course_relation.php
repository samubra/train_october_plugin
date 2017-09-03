<?php namespace Samubra\Train\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSamubraTrainPlanCourseRelation extends Migration
{
    public function up()
    {
        Schema::create('samubra_train_plan_course_relation', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('plan_id')->unsigned();
            $table->integer('course_id')->unsigned();
            $table->integer('teacher_id')->nullable()->unsigned();
            $table->decimal('hours', 10, 1)->default(4);
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->timestamp('created_at')->nullable()->nullable();
            $table->timestamp('updated_at')->nullable()->nullable();
            $table->primary(['plan_id','course_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('samubra_train_plan_course_relation');
    }
}

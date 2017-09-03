<?php namespace Samubra\Train\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSamubraTrainPlan extends Migration
{
    public function up()
    {
        Schema::create('samubra_train_plan', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('type_id')->unsigned();
            $table->smallInteger('is_review')->unsigned()->default(0);
            $table->integer('status_id')->unsigned();
            $table->boolean('can_apply');
            $table->date('end_apply_date');
            $table->date('start_date');
            $table->date('end_date');
            $table->date('exam_date')->nullable();
            $table->string('contact');
            $table->string('phone');
            $table->string('title');
            $table->text('description');
            $table->text('other_info');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('samubra_train_plan');
    }
}

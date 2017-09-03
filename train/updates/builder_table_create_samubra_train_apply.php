<?php namespace Samubra\Train\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSamubraTrainApply extends Migration
{
    public function up()
    {
        Schema::create('samubra_train_apply', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('plan_id')->unsigned();
            $table->integer('record_id')->unsigned();
            $table->smallInteger('is_review')->unsigned()->default(0);
            $table->integer('user_id')->unsigned()->nullable();
            $table->string('name')->nullable();
            $table->string('identity')->nullable();
            $table->integer('edu_id')->unsigned()->nullable();
            $table->integer('health_id')->unsigned()->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('company')->nullable();
            $table->integer('status_id')->unsigned();
            $table->smallInteger('pay')->default(0);
            $table->string('remark')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('samubra_train_apply');
    }
}

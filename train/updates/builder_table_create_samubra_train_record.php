<?php namespace Samubra\Train\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSamubraTrainRecord extends Migration
{
    public function up()
    {
        Schema::create('samubra_train_record', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('identity');
            $table->integer('user_id')->nullable()->unsigned();
            $table->integer('type_id')->unsigned();
            $table->integer('edu_id')->unsigned()->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('company')->nullable();
            $table->date('first_get_date')->nullable();
            $table->date('print_date')->nullable();
            $table->date('review_date')->nullable();
            $table->date('reprint_date')->nullable();
            $table->boolean('is_reviewed')->default(0);
            $table->boolean('is_valid')->default(0);
            $table->text('remark')->nullable()->default(null);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('samubra_train_record');
    }
}

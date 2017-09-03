<?php namespace Samubra\Train\Models;

use October\Rain\Database\Pivot;

class PlanCoursePivot extends Pivot
{
    public $belongsTo = [
        'teacher' => [Teacher::class,'key'=>'teacher_id']
    ];

    protected $appends = ['teacher_name'];

    public function getTeacherIdOptions()
    {
        return Teacher::lists('name','id');
    }

    public function getTeacherNameAttribute()
    {
        return $this->teacher->name;
    }

}
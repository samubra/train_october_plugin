<?php namespace Samubra\Train\Models;

use Model;

/**
 * Model
 */
class Course extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Validation
     */
    public $rules = [
    ];
    protected $appends = ['course_type_text'];

    public $belongsToMany = [
        'plans' => [
            Plan::class,
            'table'=>'samubra_train_plan_course_relation',
            'otherKey'=>'plan_id',
            'key'=>'course_id',
            'timestamps'=>true,
            'pivot'=>['teacher_id','hours','start_time','end_time'],
            'pivotModel'=>PlanCoursePivot::class,
        ]
    ];

    public $belongsTo = [
        'default_teacher' => [Teacher::class, 'key' => 'default_teacher_id'],
        'teacher' => [Teacher::class, 'key' => 'default_teacher_id'],
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_train_course';

    public function getCourseTypeOptions()
    {
        return [
            '0' => '理论课',
            '1' => '实操课'
        ];
    }

    public function getCourseTypeTextAttribute()
    {
        $arr = $this->getCourseTypeOptions();

        return isset($arr[$this->course_type])?$arr[$this->course_type]:'未设置';
    }
}
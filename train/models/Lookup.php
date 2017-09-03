<?php namespace Samubra\Train\Models;

use Model;

/**
 * Model
 */
class Lookup extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /*
     * Validation
     */
    public $rules = [
        'name' => 'required',
        'code' => 'required|alpha_dash|between:2,50',
        'type' => 'required',
    ];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_train_lookup';


    protected $fillable = ['name','code','type'];

    public function getTypeOptions()
    {
        return [
            'plan_status'   =>  '培训计划状态',
            'apply_status'  =>  '培训申请受理状态',
            'edu_type'      =>  '文化程度',
            'health_type' =>  '健康状况',
            'teacher_type' => '教师类别'
        ];
    }

    public function scopeType($query,$type)
    {
        if(is_array($type))
        {
            return $query->whereIn('type',$type);
        }else{
            return $query->where('type',$type);
        }
    }

    public function scopePlanStatus($query)
    {
        return $query->type('plan_status');
    }
    public function scopeTeacherType($query)
    {
        return $query->type('teacher_type');
    }
    public function scopeEduType($query)
    {
        return $query->type('edu_type');
    }

    public function scopeHealthType($query)
    {
        return $query->type('health_type');
    }
    public function scopeApplyStatus($query)
    {
        return $query->type('apply_status');
    }
}

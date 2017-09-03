<?php namespace Samubra\Train\Models;

use Model;
use Carbon\Carbon;

/**
 * Model
 */
class Plan extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;



    protected $dates = ['deleted_at'];

    protected $casts = [
        'other_info' => 'array',
        'can_apply' => 'boolean'
    ];
    /*
     * Validation
     */
    public $rules = [
        'type_id' => 'required|exists:samubra_train_category,id',
        'is_review' => 'required|in:0,1,2',
        'status_id' => 'required|exists:samubra_train_lookup,id',
        'can_apply' => 'boolean',
        'end_apply_date' => 'required|date',
        'start_date' => 'required|date',
        'end_date' => 'required|date',
        'exam_date' => 'date    ',
        'contact' => 'required',
        'phone' => 'required|digits:11',
        'description' => 'required',
        'other_info' => 'json'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_train_plan';

    protected $append = ['is_review_text'];
    public $belongsTo = [
        'train_type' => [Category::class, 'key' => 'type_id'],
        'plan_status' => [Lookup::class, 'key' => 'status_id','scope'=>'planStatus'],
    ];
    public $belongsToMany = [
            'courses' => [
                Course::class,
                'table'=>'samubra_train_plan_course_relation',
                'key'=>'plan_id',
                'otherKey'=>'course_id',
                'pivot'=>['teacher_id','hours','start_time','end_time'],
                'pivotModel'=>PlanCoursePivot::class,
                'timestamps'=>true,
            ],
        'records' => [
            Record::class,
            'table'=>'samubra_train_apply',
            'key'=>'plan_id',
            'otherKey'=>'record_id',
            'pivot'=>['is_review','user_id','name','identity','edu_id','health_id','phone','address','company','status_id','pay','remark'],
            'pivotModel'=>Apply::class,
            'timestamps'=>true,
        ],
    ];
    public $attachMany = [
        'photos' => 'System\Models\File'
    ];



    public function getIsReviewOptions()
    {
        return self::isReviewList();
    }

    public static function isReviewList()
    {
        return [
            '0' => '初训',
            '1' => '复训',
            '2' => '换证'
        ];
    }
    /**
     * Generate a URL slug for this model
     */
    public function beforeSave()
    {
        list($year, $month, $day) = explode("-", $this->start_date);
        $title = $year.'年'.$month.'月巫溪安协';
        $planList = self::where('type_id' ,$this->type_id)->where('status_id',$this->status_id)->whereRaw('YEAR(start_date) = '.$year);
        if(isset($this->id))
            $planList = $planList->where('id','!=',$this->id);
        $planCount = $planList->count();
        $title.= Category::find($this->type_id)->name;
        $title.= $this->getIsReviewTextAttribute();
        $title.= ($planCount+1);
        $title.= '期次';
        $this->title = $title;

    }

    public function getIsReviewTextAttribute()
    {
        $arr = $this->getIsReviewOptions();

        return isset($arr[$this->is_review]) ? $arr[$this->is_review]: '未设置';
    }
}
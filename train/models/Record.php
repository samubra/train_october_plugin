<?php namespace Samubra\Train\Models;

use Model;
use Carbon\Carbon;
use Samubra\Train\Classes\Indentity;

/**
 * Model
 */
class Record extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    use \October\Rain\Database\Traits\Nullable;


    protected $dates = ['deleted_at'];
    protected $fillable = ['name','type_id','first_get_date','print_date','review_date','reprint_date','remark','is_reviewed','identity','edu_id','is_valid','phone','address','company'];
    protected $nullable = ['first_get_date','print_date','review_date','reprint_date','remark'];

    protected $casts = [
      //'remark' => 'array',
      'is_reviewed' => 'boolean',
      'is_valid' => 'boolean',
    ];

    protected $jsonable = ['remark'];

    /*
     * Validation
     */
    public $rules = [
        'name' => ['required','min:2'],
        'type_id' => 'required|exists:samubra_train_category,id',
        'first_get_date' => 'date|date_format:Y-m-d H:i:s',
        'print_date' => 'date',
        'review_date' => 'date',
        'reprint_date' => 'date',
        'is_reviewed' => 'boolean',
        'is_valid' => 'boolean',
        'edu_id' => 'required_with:name,identity,health_id,phone,address,company,status_id,pay|exists:samubra_train_lookup,id',
        'phone' => 'phone',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_train_record';

    public $belongsTo = [
        'record_type' => [Category::class,'key' => 'type_id'],
        'edu' => [Lookup::class,'key'=>'edu_id','scope'=>'eduType'],
    ];
    public $attachOne = [
        'photo' => 'System\Models\File'
    ];
    public $belongsToMany = [
        //'plans' => [
        //    Plan::class,
        //    'table'=>'samubra_train_apply',
        //    'otherKey'=>'plan_id',
        //    'key'=>'record_id',
        //    'pivot'=>['is_review','user_id','name','identity','edu_id','health_id','phone','address','company','status_id','pay','remark'],
        //    'pivotModel'=>Apply::class,
        //    'timestamps'=>true,
        //],
    ];
    public $hasMany = [
        'applies' => [Apply::class]
    ];


    public function beforeValidate()
    {
        $rules = $this->rules;//$rules['identity']
        $indentity = new Indentity;
        $rules['identity'] =['required','identity','unique:samubra_train_record,identity,NULL,id,type_id,'.$this->type_id];

        if(isset($this->print_date)){
            list($print_year,$print_month,$print_day) = explode('-',$this->print_date);
            //
            $print_date = Carbon::createFromDate($print_year,$print_month,substr($print_day,0,2));
            //var_dump($print_date->addYears(6)->addDay()->toDateString().' 00:00:00');
            // $print_date 基础上+1天
            $rules['first_get_date'] = 'before:'.$print_date->addDay()->toDateString();
            $rules['reprint_date'] = 'before:'.$print_date->addYears(6)->toDateString().'|after:'.$print_date->subDays(2)->toDateString();
            $rules['review_date'] = 'after:'.$print_date->addDay()->startOfMonth()->subYears(3)->subDay()->toDateString().'|before:'.$print_date->addDay()->endOfMonth()->addDay()->toDateString();
        }
        //var_dump($rules);

        $this->rules = $rules;
    }
}

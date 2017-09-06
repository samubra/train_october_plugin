<?php namespace Samubra\Train\Models;

use Carbon\Carbon;
use October\Rain\Database\Pivot;
use ApplicationException;
use Samubra\Train\Classes\IndentityRule;

class ApplyBack extends Pivot
{
    use \October\Rain\Database\Traits\SoftDelete;
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Nullable;


    protected $dates = ['deleted_at'];
    protected $casts = [
        'remark' => 'array',
    ];
    protected $nullable = ['user_id','name','identity','edu_id','health_id','phone','address','company','status_id','pay','remark'];
    public $rules = [
        'plan_id' => 'required',
        'record_id' => 'required',
        'is_review' => 'in:0,1,2',
        'name' => 'min:2',
        'identity' => 'required|identity',
        'edu_id' => 'required_with:name,identity,health_id,phone,address,company,status_id,pay|exists:samubra_train_lookup,id',
        'health_id' => 'required_with:name,identity,edu_id,phone,address,company,status_id,pay|exists:samubra_train_lookup,id',
        'phone' => 'phone',
        'status_id' => 'required_with:name,identity,edu_id,health_id,phone,address,company,pay|exists:samubra_train_lookup,id',
        'pay' => 'numeric',
    ];
    public $belongsTo = [
        'health' => [Lookup::class,'key'=>'health_id','scope'=>'healthType'],
        'edu' => [Lookup::class,'key'=>'edu_id','scope'=>'eduType'],
        'apply_status' => [Lookup::class,'key'=>'status_id','scope'=>'applyStatus']
    ];


    protected $appends = ['health_name','eud_name','apply_status_name'];

    public function getIsReviewOptions()
    {
        return Plan::isReviewList();
    }

    public function getHealthNameAttribute()
    {
        return $this->health->name;
    }
    public function getEduNameAttribute()
    {
        return $this->edu->name;
    }
    public function getApplyStatusNameAttribute()
    {
        return $this->status_id ? $this->apply_status->name : '未填写';
    }

    public function beforeSave()
    {
        $planModel = Plan::findOrFail($this->plan_id);
        if(!$planModel->can_apply)
        {
            throw new ApplicationException('该培训计划已停止报名受理！');
            return false;
        }
        $recordModel = Record::findOrFail($this->record_id);

        if($planModel->type_id != $recordModel->type_id)
        {
            throw new ApplicationException('所选操作证的操作项目和该培训计划不一致，请重新选择添加！');
            return false;
        }

        if($planModel->is_review === 0)
        {
            if(!is_null($recordModel->first_get_date) || !is_null($recordModel->print_date) || !is_null($recordModel->review_date) || !is_null($recordModel->reprint_date))
            {
                throw new ApplicationException('该培训计划为新训，不能选择该操作证，请重新选择添加！');
                return false;
            }

        }elseif ($planModel->is_review >= 1)
        {
            if(is_null($recordModel->first_get_date) || is_null($recordModel->print_date) || is_null($recordModel->review_date) || is_null($recordModel->reprint_date))
            {
                throw new ApplicationException('该培训计划为复训，但该操作证无发证，请重新选择添加！');
                return false;
            }

            list($plan_start_year,$plan_start_month,$plan_start_day) = explode('-',$planModel->start_date);
            list($review_date_year,$review_date_month,$review_date_day) = explode('-',$recordModel->review_date);
            list($reprint_date_year,$reprint_date_month,$reprint_date_day) = explode('-',$recordModel->reprint_date);

            $planStartDate = Carbon::createFromDate($plan_start_year,$plan_start_month,$plan_start_day);
            $recordReviewDate = Carbon::createFromDate($review_date_year,$review_date_month,$review_date_day);
            $recordReprintDate = Carbon::createFromDate($reprint_date_year,$reprint_date_month,$reprint_date_day);
            if($planModel->is_review === 1)
            {
                $startDate = $planStartDate->copy();
                $reviewDate = $recordReviewDate->copy();
                $reprintDate = $recordReprintDate->copy();

                if(
                    !(($startDate->greaterThanOrEqualTo($reviewDate->subMonths(2)) && $startDate->lessThanOrEqualTo($reviewDate->addMonths(2)->endOfMonth()) && !$recordModel->is_reviewed)
                    || ($startDate->greaterThanOrEqualTo($reprintDate->subMonths(2)) && $startDate->lessThanOrEqualTo($reprintDate->addMonths(2)) && $recordModel->is_reviewed))
                )
                {
                    //var_dump($reprintDate->toDateString());
                    throw new ApplicationException('该操作证不在允许的复审或换证日期范围内，请重新选择添加！');
                    return false;
                }
            }elseif(!($planStartDate->greaterThanOrEqualTo($recordReprintDate->subMonths(2))&& $planStartDate->lessThanOrEqualTo($recordReprintDate->addMonths(2)) && $recordModel->is_reviewed)){
                throw new ApplicationException('该操作证不在允许的换证日期范围内，请重新选择添加！');
                return false;
            }
        }

    }

}

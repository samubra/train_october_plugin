<?php namespace Samubra\Train\Models;

use Carbon\Carbon;
use Model;
use ApplicationException;
use Flash;
use Illuminate\Validation\Rule;

class Apply extends Model
{
    use \October\Rain\Database\Traits\SoftDelete;
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Nullable;

    public $table = 'samubra_train_apply';

    protected $dates = ['deleted_at'];
    protected $casts = [
      //  'remark' => 'array',
    ];
    protected $jsonable = ['remark'];
    protected $nullable = ['user_id','name','identity','edu_id','health_id','phone','address','company','status_id','pay','remark','operate_score','record_id'];
    protected $fillable = ['plan_id','user_id','name','identity','edu_id','health_id','phone','address','company','status_id','pay','remark','operate_score','record_id'];
    public $rules = [
        'plan_id' => 'exists:samubra_train_plan,id',
        //'record_id' => 'exists:samubra_train_record,id',
        'is_review' => 'in:0,1,2',
        'name' => 'required|min:2',
        'identity' => 'required|identity',
        'edu_id' => 'required_with:name,identity,phone,address,company|exists:samubra_train_lookup,id',
        'health_id' => 'exists:samubra_train_lookup,id',
        'phone' => 'phone',
        'status_id' => 'exists:samubra_train_lookup,id',
        'pay' => 'numeric',
        'theory_score' =>'numeric',
        'operate_score' => 'numeric'
    ];
    public $belongsTo = [
        'health' => [Lookup::class,'key'=>'health_id','scope'=>'healthType'],
        'edu' => [Lookup::class,'key'=>'edu_id','scope'=>'eduType'],
        'apply_status' => [Lookup::class,'key'=>'status_id','scope'=>'applyStatus'],
        'plan' =>[Plan::class,'key'=>'plan_id'],
        'record' => [Record::class,'key'=>'record_id']
    ];


    protected $appends = ['is_review_text'];

    protected $planModel;
    public function getIsReviewOptions()
    {
        return Plan::isReviewList();
    }
    public function getIsReviewTextAttribute()
    {
        $arr = $this->getIsReviewOptions();

        return isset($arr[$this->is_review]) ? $arr[$this->is_review]: '未设置';
    }
    /**
     * 在验证之前添加去重复的验证规则
     *
     */
    public function beforeValidate()
    {
      //$this->getPlanModel();
      $rules = $this->rules;
      $planId = $this->plan_id;
        if(!is_null($this->record_id)){
         // $recordIdRule = Rule::unique($this->table)->where(function ($query) use($planId){
          //    $query->wherePlanId( $planId);
          //});
	$recordIdRule = 'unique:samubra_train_apply,record_id,';
          if(!is_null($this->id)){
            $recordIdRule = $recordIdRule.$this->id;
          }
          //$rules['record_id'] = ['exists:samubra_train_record,id',$recordIdRule];
	$rules['record_id'] = ['exists:samubra_train_record,id',$recordIdRule.',id,plan_id,'.$planId];
        }else{
          $indentity = $this->indentity;
          //$indentityRule = Rule::unique($this->table)->where(function ($query) use($planId,$indentity){
          //    $query->where('plan_id', $planId)->where('indentity',$indentity);
          //});
	$indentityRule = 'unique:samubra_train_apply,identity,';
          if(!is_null($this->id)){
            $indentityRule = $indentityRule.$this->id;
          }
          $rules['identity'] = ['required','identity',$indentityRule.',id,plan_id,'.$planId.',identity,'.$indentity];
        }
        $this->rules = $rules;
    }
    /**
     * 保存实例前检查申请是否符合培训计划限制条件
     * @return [type] [description]
     */
    public function beforeSave()
    {
        $this->getPlanModel();
        if(!$this->status_id)
            $this->status_id = 4;
        $this->is_review = $this->planModel->is_review;
        //Flash::error('Error saving settings');
        if(is_null($this->id))
          $this->canNotApply();//新添加时，检查培训计划是否允许报名
        switch ($this->planModel->is_review) {
          case 0://新训时不需要提供操作证
            if(!is_null($this->record_id))
                {
                  throw new ApplicationException('该培训计划为新训，不能选择操作证！');
                  return false;
                }
            break;
          default://复训或换证时需要检查包括时间是否允许，是否有效等！
            $this->checkPlanIsReview();
            break;
        }

    }
    protected function getPlanModel()
    {
      $this->planModel = Plan::findOrFail($this->plan_id);
    }
    /**
     * 培训计划报名申请已被关闭时，抛出错误信息
     * @return boolean
     */
    protected function canNotApply()
    {
      if(!$this->planModel->can_apply)
      {
        throw new ApplicationException('该培训计划不能申请培训报名！');
        return false;
      }
    }

    /**
     * 检测当前培训计划是复训时，操作证是否满足要求
     * @return boolean true则符合复审要求
     */
    protected function checkPlanIsReview()
    {
      if(is_null($this->record_id))
        {
          throw new ApplicationException('该培训计划是复训，请选择与之相符的操作证！');
          return false;
        }
        $recordModel = Record::findOrFail($this->record_id);
        if($this->planModel->type_id != $recordModel->type_id)
        {
            throw new ApplicationException('所选操作证的操作项目和该培训计划不一致，请重新选择添加！');
            return false;
        }
        $planStartDate = $this->getDateCarbon($this->planModel->end_apply_date);
        $reviewDate = $this->getDateCarbon($recordModel->review_date);
        $reprintDate = $this->getDateCarbon($recordModel->reprint_date);
        switch ($this->planModel->is_review) {
          case '1':
            if(!$this->checkReviewData($reviewDate,$planStartDate))
            {
              throw new ApplicationException('所选操作证不应该在当前时间复审！');
              return false;
            }
            break;
          case '2':
            if(!$this->checkReviewData($reprintDate,$planStartDate) || !$recordModel->is_reviewed)
            {
              throw new ApplicationException('所选操作证不应该在当前时间换证,或者当前操作证已失效！');
              return false;
            }
        }
        if($recordModel->name != $this->name || $recordModel->identity != $this->identity)
        {
          throw new ApplicationException('操作证的信息与所填信息不一致，请核对后再提交！');
          return false;
        }
        return true;
    }
    /**
     * 检查复审日期是否在允许的培训日期内，复审日期必须在培训日期前2个月
     * @param  Carbon $date          复审或换证日期对象
     * @param  Carbon $planStartDate 培训开始日期对象
     * @return boolean             true则当前操作证在复审日期范围内
     */
    protected function checkReviewData(Carbon $date, Carbon $planStartDate)
    {
      return $planStartDate->lessThanOrEqualTo($date) && $planStartDate->greaterThanOrEqualTo($date->subMonths(2));

    }
    /**
     * 返回日期对象
     * @param  init $date 日期
     * @return subject       Carbon日期对象
     */
    protected function getDateCarbon($date)
    {
      list($year,$month,$day) = explode('-',$date);
      return Carbon::createFromDate($year,$month,$day);
    }

}

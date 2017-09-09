<?php namespace Samubra\Train\Models;

use ApplicationException;
class AppliesExport extends \Backend\Models\ExportModel
{
  public $plan_or_record = null;
  protected $appends = ['plan_or_record','plan_select','record_select','record_type'];
  protected $fillable = [
        'plan_or_record','plan_select','record_select'
    ];
    public $rules = [
        'plan_or_record' => 'required|boolean',
        'plan_select' => 'required_if:plan_or_record,0|exists:samubra_train_plan,id',
        //'record_id' => 'exists:samubra_train_record,id',
        'record_select' => 'required_if:plan_or_record,1|identity',
        'record_type' => 'required_if:plan_or_record,1|exists:samubra_train_category,id',
    ];
    public function exportData($columns, $sessionKey = null)
    {
        //throw new ApplicationException($this->plan_or_record);
        if($this->plan_or_record)
          $subscribers = $this->getApplyByRecord();
        else
          $subscribers = $this->getApplyByPlan();
        $subscribers->each(function($subscriber) use ($columns) {
            $subscriber->addVisible($columns);
        });
        return $subscribers->toArray();
    }

    public function getApplyByRecord()
    {
      $recordModel = Record::whereId($this->record_select)->whereTypeId($this->record_type)->first();
        return $recordModel->applies;
    }
    public function getApplyByPlan()
    {
      $planModel = Plan::find($this->plan_select);
        return $planModel->applies;
    }
    public function getPlanSelectOptions()
    {
      return Plan::lists('title','id');
    }
    public function getRecordTypeOptions()
    {
      return Category::where('depth','2')->lists('name','id');
    }
}

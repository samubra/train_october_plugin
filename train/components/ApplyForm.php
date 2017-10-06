<?php namespace Samubra\Train\Components;

use Carbon\Carbon;
use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use October\Rain\Support\Facades\Flash;
use Samubra\Train\Controllers\Applies as ApplyController;
use Samubra\Train\Models\Apply as ApplyModel;
use Samubra\Train\Models\Lookup;
use Samubra\Train\Models\Plan;
use Samubra\Train\Models\Record;
use Auth;
use ApplicationException;
use Lang;
use Input;
use Request;
use Redirect;
use Validator;
use ValidationException;
use Exception;
use AjaxException;

class ApplyForm extends ComponentBase
{
    public $submitPage = null;
    public $plan_id = null;
    public $plan = null;
    public $new_record = false;
    public function componentDetails()
    {
        return [
            'name'        => '操作证申请表单',
            'description' => '选择或添加操作证'
        ];
    }

    public function defineProperties()
    {
        return [
            'planId' => [
                'title'       => '培训计划',
                'description' => 'URL中传入培训计划ID',
                'type'        => 'string',
                'default'     => '{{ :plan_id }}',
                'validation'  => [
                    'required' => [
                        'message' => '必须填写'
                    ]
                ]
            ],
            'submitPage' => [
                'title'       => '提交页面',
                'description' => '将表单提交的页面',
                'type'        => 'dropdown',
                'showExternalParam' => false,
            ],
        ];
    }

    public function getSubmitPageOptions()
    {
        $pages = Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');

        $pages = [
                '-' => '无提交页面'
            ] + $pages;

        return $pages;
    }

    public function onRun()
    {
        $this->prepareVars();
        $this->checkPlanDate();
        $this->loadEduOptions();
    }


    /**
     * @throws Exception
     */
    public function onLoadRecords()
    {

        try{
            $postData = post();
            $rules = [
                'identity' => ['required','identity']
            ];
            $messages = [
                'identity'=> '身份证号码格式不正确。',
                'required' => '身份证号码必须填写。'
            ];


            $validation = $this->validation($postData,$rules,$messages);

            if( $validation) {
                $this->prepareVars();
                $this->loadEduOptions();
                if(array_key_exists('name', $postData))
                    $this->saveRecord($postData);
                else
                    $this->findRecords($postData);
                $this->page['new_record'] = $this->new_record;
            }else{
                Flash::error(sprintf('没有找到 %s 的操作证', $postData['identity']));
                throw new ValidationException($validation);
                //throw new ApplicationException(sprintf('没有找到 %s 的操作证', $postData['identity']));
            }

        }catch (Exception $ex) {
            if (Request::ajax()) throw $ex;
            else Flash::error($ex->getMessage());
        }
    }

    public function onLoadRecord()
    {
        $this->prepareVars();

        $record_id = post('record_id');

        $this->page['record'] = Record::findOrFail($record_id);
        $this->loadEduOptions();
        $this->page['new_record'] = true;

    }
    public function onSaveApply()
    {
        try {
            $apply = post();
            $rules = [
                'phone' => 'required',
                'address' => 'required',
                'company' => 'required'
            ];

            $messages = [
                'required' => '必填项',
            ];
            //var_dump($apply);

            $validation = $this->validation($apply,$rules,$messages);
            if( $validation)
            {
                //var_dump($recordModel->id);
                if(!isset($apply['record_id']))
                  $apply['record_id'] =null;
                //$apply['plan_id'] = post('plan_id');
                //var_dump($apply['plan_id']);
                $applyModel = ApplyModel::create($apply);
                //$planModel->records()->attach($recordModel->id,$apply);
                //var_dump($applyModel);
                //trace_sql();
                return Redirect::to('/plan-list');
            }else{
              throw new ValidationException($validation);
            }
            //else
                //throw new ApplicationException('你填写的部分内容不符合要求，请仔细阅读培训计划相关内容并按照实际要求填写！');

        }catch (Exception $ex) {
            if (Request::ajax()) throw new ApplicationException($ex->getMessage());
            else Flash::error($ex->getMessage());
            }
    }
    protected function loadPlan( $plan_id = null )
    {
        if (!strlen($this->plan_id)) {
            if(!is_null($plan_id))
                $this->plan_id = $plan_id;
            else
                return ;
        }
        return Plan::with('train_type','plan_status')->find($this->plan_id);
    }
    protected function loadEduOptions()
    {
        $this->page['eduOptions'] = Lookup::eduType()->lists('name','id');
        trace_sql();
    }
    protected function saveRecord($data)
    {
        try{
            $recordModel = new Record;
            $recordModel->name = $data['name'];
            $recordModel->identity = $data['identity'];
            $recordModel->type_id = $data['type_id'];
            $this->page['record'] = $recordModel->save();
            $this->new_record = true;
        }catch (Exception $ex) {
            if (Request::ajax()) throw $ex;
            else Flash::error($ex->getMessage());
        }
    }
    protected function findRecords($data)
    {
        try {
            $plan = $this->loadPlan($this->plan_id);
            //throw new ApplicationException($identity);
            //var_dump($plan->records_count);
            $query = Record::where('identity', $data['identity'])->where('type_id', $data['type_id']);
            if($plan->applies_count)
                $query->whereNotIn('id', $plan->applies->lists('record_id'));
            $record = $query->with('record_type')->get();
            trace_sql();
            if ($record->count()) {// && $record->checkCanApply($plan->is_review)
                $this->page['records'] = $record;
            } else {
                throw new ApplicationException('没有找到你的可以申请该培训计划的操作证，或你已申请培训，请勿重复操作！');
            }
        }catch (Exception $ex) {
            if (Request::ajax()) throw $ex;
            else Flash::error($ex->getMessage());
        }

    }
    protected function validation($data,$rules,$message = null)
    {
        $validation = Validator::make($data, $rules, $message);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }
        return !$validation->fails();
    }

    protected function checkPlanDate()
    {
        $today = Carbon::create();
        list($endApplyDateYear,$endApplyDateMonth,$endApplyDateDay) = explode('-',$this->plan->end_apply_date);
        $date_enable_apply = Carbon::create($endApplyDateYear,$endApplyDateMonth,$endApplyDateDay)->greaterThanOrEqualTo($today);
        if(!$date_enable_apply || !$this->plan->can_apply)
        {
            throw new ApplicationException('该培训计划已过期或已停止受理申请，请重新选择其他培训计划！');
            if (Request::ajax())
            {
                throw new ApplicationException('该培训计划已过期或已停止受理申请，请重新选择其他培训计划！');
            //}else{
              //  Flash::error('该培训计划已过期或已停止受理申请，请重新选择其他培训计划！');
                //return Redirect::to('/plan/list');
            }
        }
    }
    protected function prepareVars()
    {

        $submitPage = $this->property('submitPage');
        if ($submitPage == '-') {
            $submitPage = null;
        }

        $this->submitPage = $this->page['submitPage'] = $submitPage;

        $this->plan_id = $this->page['plan_id'] = $this->property('planId');
        $this->plan = $this->page['plan'] = $this->loadPlan();

    }
}

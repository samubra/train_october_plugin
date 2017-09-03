<?php namespace Samubra\Train\Updates;

use Seeder;
use Samubra\Train\Models\Lookup;

class SeedLookupTable extends Seeder
{
  public $datas = [
    [
        'name'  => '接受报名申请',
        'code'  => 'plan_status_accept_apply',
        'type'  => 'plan_status',
    ],
    [
        'name'  => '已停止报名申请',
        'code'  => 'plan_status_not_accept_apply',
        'type'  => 'plan_status',
    ],
    [
        'name'  => '培训资料已存档',
        'code'  => 'plan_status_cundang',
        'type'  => 'plan_status',
    ],
    [
        'name'  => '等待受理',
        'code'  => 'apply_status_waiting_accept_apply',
        'type'  => 'apply_status',
    ],
    [
        'name'  => '正在受理',
        'code'  => 'apply_status_accepting_apply',
        'type'  => 'apply_status',
    ],
    [
        'name'  => '申请审核已通过，等待考试',
        'code'  => 'apply_status_apply_success',
        'type'  => 'apply_status',
    ],
    [
        'name'  => '申请未审核通过',
        'code'  => 'apply_status_apply_not_success',
        'type'  => 'apply_status',
    ],

    [
        'name'  => '考试未通过',
        'code'  => 'apply_status_exam_not',
        'type'  => 'apply_status',
    ],
    [
        'name'  => '考试通过，等待领证',
        'code'  => 'apply_status_waiting_record',
        'type'  => 'apply_status',
    ],
    [
        'name'  => '已领证',
        'code'  => 'apply_status_record_over',
        'type'  => 'apply_status',
    ],
    [
        'name'  => '初中',
        'code'  => 'edu_type_chuzhong',
        'type'  => 'edu_type',
    ],
    [
        'name'  => '中专或同等学历',
        'code'  => 'edu_type_zhongzhuan',
        'type'  => 'edu_type',
    ],
    [
        'name'  => '高中或同等学历',
        'code'  => 'edu_type_gaozhong',
        'type'  => 'edu_type',
    ],
    [
        'name'  => '大专或同等学历',
        'code'  => 'edu_type_dazhuan',
        'type'  => 'edu_type',
    ],
    [
        'name'  => '本科及其以上',
        'code'  => 'edu_type_benke',
        'type'  => 'edu_type',
    ],
    [
        'name'  => '无需体检',
        'code'  => 'health_type_not_need',
        'type'  => 'health_type',
    ],
    [
        'name'  => '无意见性结论',
        'code'  => 'health_type_wujielun',
        'type'  => 'health_type',
    ],
    [
        'name'  => '体检合格',
        'code'  => 'health_type_hege',
        'type'  => 'health_type',
    ],
    [
        'name'  => '无',
        'code'  => 'teacher_type_wu',
        'type'  => 'teacher_type',
    ],
    [
        'name'  => '助理讲师',
        'code'  => 'teacher_type_zhulijiangshi',
        'type'  => 'teacher_type',
    ],
    [
        'name'  => '讲师',
        'code'  => 'teacher_type_jiangshi',
        'type'  => 'teacher_type',
    ],
    [
        'name'  => '高级讲师',
        'code'  => 'teacher_type_gaojijiangshi',
        'type'  => 'teacher_type',
    ],
    [
        'name'  => '教授',
        'code'  => 'teacher_type_jiaoshou',
        'type'  => 'teacher_type',
    ],
  ];
    public function run()
    {
      foreach ($this->datas as $data) {
        Lookup::create($data);
        trace_sql();
      }

    }
}

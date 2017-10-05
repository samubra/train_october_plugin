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
        'name'  => '电工作业',
        'code'  => 'teacher_type_diangong',
        'type'  => 'teacher_type',
    ],
    [
        'name'  => '登高架设作业',
        'code'  => 'teacher_type_denggao',
        'type'  => 'teacher_type',
    ],
    [
        'name'  => '焊接与热切割作业',
        'code'  => 'teacher_type_hanjie',
        'type'  => 'teacher_type',
    ],
    [
        'name'  => '企业内机动车辆',
        'code'  => 'teacher_type_jidongcheliang',
        'type'  => 'teacher_type',
    ],
    [
        'name'  => '非煤矿山',
        'code'  => 'teacher_type_feimeikuangshan',
        'type'  => 'teacher_type',
    ],
    [
        'name'  => '法律法规及案例分析',
        'code'  => 'teacher_type_falvfagui',
        'type'  => 'teacher_type',
    ],
    [
        'name'  => '烟花爆竹',
        'code'  => 'teacher_type_yanbao',
        'type'  => 'teacher_type',
    ],
    [
        'name'  => '危险化学品',
        'code'  => 'teacher_type_weihua',
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

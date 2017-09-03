<?php namespace Samubra\Train\Updates;

use Seeder;
use Samubra\Train\Models\Category;

class SeedCategoryTable extends Seeder
{
  public $datas = [
    [
      'name'=>'特种作业培训',
      'parent_id'=>NULL,
    ],[
      'name'=>'电工特种作业培训',
      'parent_id'=>'1',
    ],[
      'name'=>'低压电工特种作业',
      'parent_id'=>'2',
    ],[
      'name'=>'高压电工作业',
      'parent_id'=>'2',
    ],[
      'name'=>'焊接与热切割作业培训',
      'parent_id'=>'1',
    ],[
      'name'=>'熔化焊接与热切割作业',
      'parent_id'=>'5',
    ],[
      'name'=>'高处作业培训',
      'parent_id'=>'1',
    ],[
      'name'=>'高处安装、维护、拆除作业',
      'parent_id'=>'7',
    ],[
      'name'=>'登高架设作业',
      'parent_id'=>'7',
    ]
  ];
    public function run()
    {
      foreach ($this->datas as $row=>$data) {
        $category = Category::create($data);
        trace_sql();
      }

    }
}

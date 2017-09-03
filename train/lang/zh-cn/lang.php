<?php return [
    'plugin' => [
        'name' => 'train',
        'description' => '培训在线管理系统，允许发布培训信息和接受培训申请',
        'created_at' => '创建时间',
        'updated_at' => '修改时间',
        'remark' => '备注',
        'deleted_at' => '删除'
    ],
    'lookup' => [
        'name' => '名称',
        'code' => '代码',
        'type' => '类别',
        'manage' => '数据管理'
    ],
    'category' => [
        'name' => '类别名称',
        'parent_id' => '父级分类',
        'manage' => '培训类别管理',
        'depth' => '级别'
    ],
    'apply' => [
        'plan_id' => '培训计划',
        'record_id' => '操作证',
        'is_review' => '是否复训',
        'user_id' => '用户',
        'name' => '姓名',
        'identity' => '身份证号码',
        'edu_id' => '文化程度',
        'health_id' => '健康状况',
        'phone' => '联系电话',
        'address' => '联系地址',
        'company' => '单位名称',
        'status_id' => '受理状态',
        'pay' => '支付金额',

    ],
    'course' => [
        'title' => '课程名称',
        'course_type' => '课程类型',
        'default_teacher_id' => '默认教师',
        'default_hours' => '默认课时',
        'manage' => '课程管理'
    ],
    'plan' => [
        'type_id' => '培训类别',
        'status_id' => '培训计划状态',
        'can_apply' => '允许申请',
        'end_apply_date' => '申请截止日期',
        'start_date' => '培训开始日期',
        'end_date' => '培训结束日期',
        'exam_date' => '考试日期',
        'contact' => '联系人',
        'phone' => '联系电话',
        'title' => '培训计划名称',
        'description' => '描述',
        'other_info' => '其他信息',
        'manage' => '培训计划管理'
    ],
    'plan_course_relation' => [
        'course_id' => '课程',
        'teacher_id' => '授课教师',
        'hours' => '课时',
        'start_time' => '开始时间',
        'end_time' => '结束时间'
    ],
    'record' => [
        'first_get_date' => '初领证日期',
        'print_date' => '发证日期',
        'review_date' => '复审日期',
        'reprint_date' => '换证日期',
        'is_reviewed' => '是否已复审',
        'manage' => '操作证管理'
    ],
    'teacher' => [
        'name' => '教师姓名',
        'identity' => '身份证号码',
        'certificate_no' => '资格证号码',
        'job_title' => '职称',
        'phone' => '联系电话',
        'company' => '所在单位',
        'edu_id' => '文化程度',
        'type_id' => '资格类别',
        'manage' => '教师管理'
    ]
];
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
define('TIMENOW', time());

/** 管理员的几种当前状态 */
// 未登录/离线
define('WORK_STATUS_OFFLINE', 0);
// 在线，空闲的，联机
define('WORK_STATUS_IDLE', 1);
// 在线，忙碌的
define('WORK_STATUS_BUSY', 2);
// 退出系统
define('WORK_STATUS_LOGOUT', 3);
// 离开状态
define('WORK_STATUS_GOAWAY', 4);

/** 新建表类型 新建表类型，记录在线建表、ada自建表、市场建表、入学建表等方式 */
//在线建表
define('CREATETYPE_ZX', 1);
//CRM建表
define('CREATETYPE_CRM', 100);      //之前是9
//教育顾问自建表类型
define('CREATETYPE_ADA', 10);
//市场建表类型
define('CREATETYPE_SC', 20);
//入学辅导建表类型
define('CREATETYPE_RX', 30);
//重新建表类型
define('CREATETYPE_REBUILT', 40);
//更改在线建表人类型
define('CREATETYPE_CHANGE', 50);
//渠道建表类型
define('CREATETYPE_QD', 60);
//学院网类型
define('CREATETYPE_XYW', 70);
//钉钉微应用转介绍
define('CREATETYPE_DING', 80);
//小程序建表
define('CREATETYPE_MINI', 90);
//学院网M站
define('CREATETYPE_XYW_M', 110);
//PC火星网
define('CREATETYPE_MARS', 120);
//M火星网
define('CREATETYPE_MARS_M', 130);
//特训营
define('CREATETYPE_TRANING_STU', 140);
//在线渠道报名
define('CREATETYPE_COMPONENT', 152);


/** 学生的建表方式（旧的分类，远程表用该分类字段 category） */
// 第一次咨询的联系人，一般称为：在线顾问
define('CONSULTANT_FIRST', 1);
// 联系后，由另外一个人负责，一般称为：教育顾问
define('CONSULTANT_SECOND', 2);
//客服可以负责自己的学生
//define('CONSULTANT_THIRD', 3); 
//试听建表
define('CONSULTANT_FOURTH', 4); 


/** 学生分配方式 */
//在线建表分配
define('ASSORTTYPE_ZX', 1);
//指定分配
define('ASSORTTYPE_ZD', 2);
//线下转分分配
define('ASSORTTYPE_XXZF', 3);
//申请使用分配
define('ASSORTTYPE_SQ', 4);
//ADA自建表分配
define('ASSORTTYPE_ADA', 10);
//预约分配
define('ASSORTTYPE_BESPOKE', 20);
//使用旧量分配
define('ASSORTTYPE_OLD', 30);
//15分钟为反馈分配
define('ASSORTTYPE_NF', 40);
//到访分配
define('ASSORTTYPE_DF', 50);
//特训营分配
define('ASSORTTYPE_TX', 60);
//代理商分配
define('AGENTTYPE_DL', 70);

/** 预约回收类型 */
//15分钟未反馈回收预约通道
define('BESPOKE_NOFEEDBACK', 1);
//在线建表分配未找到负责人进入预约
define('BESPOKE_ZXSTUDENT', 2);
//转分分配未找到负责人进入预约
define('BESPOKE_CHANGE', 3);

/** 旧量回收 */
//距离上次反馈30天未报名
define('RECOVER_NOENROLLED', 1);
//48小时C量掉量
define('RECOVER_TWODAY', 2);
//强制收回离职人员的量
define('RECOVER_DIMISSION', 3);
//挤出的C量
define('RECOVER_OUT', 4);
//转分网校掉量
define('RECOVER_CHANGEWX', 5);
//建表直接分网校掉旧量
define('RECOVER_AUTOASSIGNWX', 6);
/** 用户离开状态时间设置 分钟*/
//define('GOAWAY_MINUTELIMIT', 30);

/** 请求操作类型 */
//用户请求
define('REQUESTTYPE_USER', 1);
//系统请求
define('REQUESTTYPE_SYSTEM', 2);

/* 列表类型：在线顾问/教育顾问类型 */
//在线顾问列表类型
define('CONSULTANTTYPE_ONLINE', 1);
//教育顾问列表类型
define('CONSULTANTTYPE_ECUSER', 2);

/** 分页 */
//每页显示
define('PAGESIZE', 20);

/** 缓存时间 分钟 */
//缓存5分钟
define('CACHE_MINUTE_FIVE', 5);
//缓存20分钟
define('CACHE_MINUTE_TWENTY', 20);
//缓存一个小时
define('CACHE_HOUR', 60);
//缓存一天
define('CACHE_DAY', 1440);
//缓存一周
define('CACHE_WEEK', 10080);
//缓存一月 30天
define('CACHE_MONTH', 43200);

/** 课程时间 */
//短期班
define('SPECIALTYTIME_SHORT', 1);
// 长期班
define('SPECIALTYTIME_LONG', 2);

/** 反馈类型 */
//面访
define('FEEDBACK_INTERVIEW', 5);
//接待
define('FEEDBACK_RECEPTION', 2);

/** 目录分隔符 */
// 目录分隔符
define('DS', DIRECTORY_SEPARATOR);

/** 建表分配类型*/
//在线建表只保存
define('ASSIGNTYPE_ONLYSAVE', 1);
//在线建表分配
define('ASSIGNTYPE_COMMON', 2);
//在线建表紧急分配
define('ASSIGNTYPE_EMERGENCY', 3);
//教育顾问保存
define('ASSIGNTYPE_ASSIGNEDME', 4);
//渠道保存
define('ASSIGNTYPE_CHANNEL', 5);

/** 负责人类型*/
//建表人
define('ONLINECONSULTANT', 1);
//当前负责人（ADA）
define('EDUCATIONALCONSULTANT', 2);

/** 学生缴费的费用类型 */
//管理证件押金
//define(' MONEYTYPE_CERTIFICATE', 7);


////////////API接口///////////////////////////
/** 课程时间 */
// 在线班级
define('SPECIALTYTIME_ONLINE', 4);

// 教务助理
//define('USERGROUPID_ACADEMIC_ASSISTANT', 22);


// 无限期，系统办理“延期”手续时，“期数选择”新增‘无限期’选项
define('UNLIMITED_TERM', 99999999);
define('UNLIMITED_TERM_CLAZZID', 458);

//不确定期数，初次报名和延班的时候增加
define('UNCERTAIN_TERM', 8888);

/** 学生的几种报名状态 */
// 虚报，交了一部分学费
define('STUDENT_STATUS_PARTIALLYPAID', 0);
// 实报，交齐全部学费
define('STUDENT_STATUS_COMPLETELYPAID', 1); 
// 延期
define('STUDENT_STATUS_POSTPONED', 2);
// 转班
define('STUDENT_STATUS_TRANSFER', 3);
// 休学，变成2个步骤：先标记为休学，然后再进行复学，复学后，则要选择目的班级，和目前的休学处理方式完全相同
define('STUDENT_STATUS_SUSPENDED', 4);
// 标记为休学
define('STUDENT_STATUS_SUSPENDING', 44);
// 退学
define('STUDENT_STATUS_QUITTED', 5);
// 毕业
define('STUDENT_STATUS_GRADUATED', 6);
// 复学
define('STUDENT_STATUS_RETURN', 99);

// 等待空位
define('STUDENT_QUEUE_STATUS_WAITING', 0);

// 获取有效的报名数据的SQL条件
define('STUDENT_ENROLLMENT_CONDITION', 'AND enrollment.status IN (' . STUDENT_STATUS_PARTIALLYPAID . ',' . STUDENT_STATUS_COMPLETELYPAID . ',' . STUDENT_STATUS_GRADUATED . ')');

/** 费用管理 */
//自动冲红
define('TRANSFERTYPE_AUTO', 1);
//手动冲红
define('TRANSFERTYPE_MANUAL', 2);

//火星网校ID
define('CAMPUSID_WANGXIAO', 28);
// 定义一个不存在的学生ID
define('DOES_NOT_EXIST_STUDENTID',-999);
//网校顾问虚拟账户userid
define('ROBOT_USERID', 4738);
//讲座虚拟账户userid
define('ROBOT_LECTURE_USERID', 5120);
//钉钉消息相关
//王佳钉钉用户id
define('DINGDING_WANGJIA', '101445432921084233');//测试环境为王继冲112613472729453430，正式梁甜1045063606859323  韩玉萍040853261438323021 阳小凤104506363637702952 刘洪君101445432921084233
//王佳e系统用户id
define('WANGJIA_USERID', 2111);//测试环境为王继冲3282，正式梁甜2111
//钉钉消息发送类型（模板）
//发给推荐人，推荐学员分配信息
define('DING_AUTO_ASSIGN', 1);
//发给推荐人，推荐学员报名信息
define('DING_ENROLLMENT', 2);
//报名成功抄送王佳
define('DING_ENROLLMENT_TO_WANGJIA', 3);
//发给ada,推荐的学员分配给ada，抄送王佳
define('DING_ADA_AUTO_ASSIGN', 4);
//分配给ada，抄送王佳
define('DING_ASSIGN_WANGJIA', 5);

#无效自动审批id
define('INVALID_USERID', 6710);//系统销售经理

# 教育顾问用户组id
define('STUDENT_ADA', '72a8403b-4426-11e6-ad8d-0050569ffab9');
define('STUDENT_ADA_LEADER', 'c9f09d54-97fa-11e6-99d4-0050569ffab9');

# 付款状态
define('PAY_STATUS_ZERO', 0);// 未支付
define('PAY_STATUS_FULL', 1);// 全款
define('PAY_STATUS_PART', 2);// 部分支付

# 赠送网课状态
define('HANDSEL_STATUS_ZERO', 0);// 未赠送网课
define('HANDSEL_STATUS_ONE', 1);// 已赠送网课

#付款方式
define('WECHAT_FEEPAY', 15);// 微信
define('ALIPAY_FEEPAY', 10);// 支付宝
define('YINLIANSHANGWU_FEEPAY', 24);// 银联商务

#转介绍取消类型
define('CANCEL_RECOMMEND_ONE', 1);
define('CANCEL_RECOMMEND_TWO', 2);
define('CANCEL_RECOMMEND_THREE', 3);

#转介绍类型
define('RECOMMEND_TYPE_ONE', 1);            // 钉钉转介绍
define('RECOMMEND_TYPE_TWO', 2);            // 入学建表学员推荐
define('RECOMMEND_TYPE_THREE', 3);          // 教育顾问
define('RECOMMEND_TYPE_FOUR', 4);           // 班主任
define('RECOMMEND_TYPE_FIVE', 5);           // 小程序
define('RECOMMEND_TYPE_SIX', 6);            // 待定
define('RECOMMEND_TYPE_SEVEN', 7);            // 学员转介绍

#单据类型,1缴费，2转班，3退班，4退费
define('RECEIPT_TYPE_ONE', 1);
define('RECEIPT_TYPE_TWO', 2);
define('RECEIPT_TYPE_THREE', 3);
define('RECEIPT_TYPE_FOUR', 4);

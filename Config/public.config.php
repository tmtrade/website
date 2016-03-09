<?
/**
 * 定义应用所需常量
 */
$define = array(
    'SecondStatus' => array (
        1     => '申请中',
        2     => '已注册',
        3     => '已无效',
        4     => '驳回中',
        5     => '驳回复审中',
        6     => '部分驳回',
        7     => '公告中',
        8     => '异议中',
        9     => '异议复审中',
        10    => '需续展',
        11    => '续展中',
        12    => '开具优先权证明中',
        13    => '开具注册证明中',
        14    => '撤销中',
        15    => '撤销复审中',
        16    => '撤回撤销中',
        17    => '变更中',
        18    => '变更代理人中',
        19    => '补证中',
        20    => '补变转续证中',
        21    => '转让中',
        22    => '更正中',
        23    => '许可备案中',
        24    => '许可备案变更中',
        25    => '删减商品中',
        26    => '冻结中',
        27    => '注销中',
        28    => '无效宣告中',
    ),
	//1-45分类
    'CLASSES' => array(
		'1'   => '化工原料',
        '2'   => '油漆涂料',
		'3'   => '化妆清洁',
		'4'   => '工业油脂',
		'5'   => '药品制剂',
		'6'   => '五金器具',
		'7'   => '机械机器',
		'8'   => '手工用具',
		'9'   => '电子电器',
		'10'  => '医疗器械',
		'11'  => '家用电器',
		'12'  => '车船配件',
		'13'  => '火器烟花',
		'14'  => '珠宝饰品',
		'15'  => '乐器乐辅',
		'16'  => '文具办公',
		'17'  => '橡塑制品',
		'18'  => '皮具制品',
		'19'  => '建筑材料',
		'20'  => '家具工艺',
		'21'  => '厨具日用',
		'22'  => '缆绳帐篷',
		'23'  => '线纱丝纺',
		'24'  => '家用纺品',
		'25'  => '服装鞋帽',
		'26'  => '缝纫用品',
		'27'  => '地毯席垫',
		'28'  => '运动器械',
		'29'  => '食品鱼肉',
		'30'  => '食品佐料',
		'31'  => '生鲜农产',
		'32'  => '啤酒饮料',
		'33'  => '酒精饮料',
		'34'  => '烟草制品',
		'35'  => '广告商业',
		'36'  => '金融经纪',
		'37'  => '修理安装',
		'38'  => '通讯服务',
		'39'  => '运输旅行',
		'40'  => '加工服务',
		'41'  => '教育娱乐',
		'42'  => '科技研究',
		'43'  => '餐饮住宿',
		'44'  => '医疗美容',
		'45'  => '法律安全',
    ),

	
    'ISSURE' => array(
		'1'  => '否',
        '2'  => '是',
    ),
    'AREA' => array(
		'1'  => '国内',
        '2'  => '国外',
    ),

    //商标搜索项
    'SBSEARCH' => array(
        '1'  => '商标名称',
        '2'  => '商标号',
        '3'  => '适用服务',
    ),

    //商标名称搜索项
    'SBNAME' => array(
        '1'  => '完全相同',
        '2'  => '前包含',
        //'3'  => '后包含',
        //'4'  => '全包含',
        '9'  => '近似查询',
    ),
	
	//商标类型
    'TYPES' => array(
        '1'  => '纯中文',
        '2'  => '纯英文',
        '3'  => '纯图形',
        '4'  => '中+英',
        '5'  => '中+图',
        '6'  => '英+图',
        '7'  => '中+英+图',
        '8'  => '纯数字',
    ),
    //商标字数
    'SBNUMBER' => array(
        '1,2'  => '1-2个字',
        '3'  => '3个字',
        '4'  => '4个字',
        '5'  => '5个字',
        '6'  => '5个字及以上',
    ),

    /*平台入驻*/
    'PLATFORM_IN' => array(
     //   '99'  => '自营',
        '1'  => '京东',
        '2'  => '天猫',
        '7'  => '大型超市',
        '3'  => '亚马逊',
        '4'  => '1号店',  
        '5'  => '美丽说',
        '6'  => '聚美优品',
    ),
    'PLATFORM_URL' => array(
        //'99'  => '/faq/rule/',
        '1'  => '/faq/rule/#r6',
        '2'  => '/faq/rule/#r7',
        '7'  => '/faq/rule/#r1',
        '3'  => '/faq/rule/#r2',
        '4'  => '/faq/rule/#r5',  
        '5'  => '/faq/rule/#r3',
        '6'  => '/faq/rule/#r4',
    ),

    /*平台入驻*/
    'PLATFORM_ITEMS' => array(
        '1'         => array( 1,2,3,5,6,9,11,12,14,15,16,18,20,21,24,25,26,27,28,29,30,31,32,33,35,36,37,39,41,42,43,44,45, ),
        '2'         => array( 1,2,3,5,6,8,9,11,12,14,15,16,17,18,19,20,21,24,25,26,27,28,29,30,31,32,33,35,36,37,39,41,42,43,44,45, ),
        '3'         => array( 3,5,9,10,16,28,29,30,31,32,33,34,41,44, ),
        '4'         => array( 2,3,5,6,7,8,9,10,11,12,14,15,16,17,18,19,20,21,23,24,25,26,27,28,29,30,31,32,33,35,36,37,38,39,41,42,43,44, ),
        '5'         => array( 18,24,25,26,29,30,31,32,33, ),
        '6'         => array( 3,5,8,9,10,11,14,15,16,18,21,24,25,26,28,29,30,31,32,33, ),
        '7'         => array( 1,2,3,5,6,7,8,9,11,12,13,14,15,16,17,18,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34, ),
    ),
	
	//指导价格浮动比例
    'FLAOT_RATIO' => array(
		'1'  => 1,
        '2'  => 0.8,
        '3'  => 0.6,
		'4'  => 0.5,
		'5'  => 0.4,
		'6'  => 0.3,
		'7'  => 0.1,
		'8'  => 0,
    ),
	
	//出售搜索底价
    'SEARCH_PRICE' => array(
		'1'  => array(1,20000,'2万以下'),
        '2'  => array(20000,50000,'2-5万'),
		'3'  => array(50000,100000,'5-10万'),
		'4'  => array(100000,200000,'10-20万'),
		'5'  => array(200000,500000,'20-50万'),
		'6'  => array(500000,1000000,'50-100万'),
		'7'  => array(1000000,10000000000000,'100万以上'),
		'8'  => array(0,0,'面议'),
    ),

    'COOKIE_PREFIX' => 'tw_',//网站cookie前缀
    'PUBLIC_USER' => 'ChOfNuSeR',//公用用户登录信息标识
    'PUBLIC_USER_TIME' => 1800,//用户登录信息有效时间

    'MSG_TEMPLATE' => array(
        'valid'     => "验证码：%s，有效期为10分钟，请尽快使用。",
        'register'  => "%s（登录密码），系统已为您开通手机账户，登陆可查看求购进展，工作人员不会向你索要，请勿向任何人泄露。",
        'newValid'  => "%s（动态登录密码），仅本次有效，请在10分钟内使用。工作人员不会向你索要，请勿向任何人泄露。",
        'validBind' => "%s（手机绑定校验码），仅本次有效请在10分钟内使用。工作人员不会向你索要，请勿向任何人泄露。如非本人操作，请忽略。",
        ),
    //'HTMLTOPDF' 	=> 'D:\wkhtmltopdf\bin\wkhtmltopdf.exe'    
	'HTMLTOPDF' 	=> '/usr/bin/wkhtmltopdf',
	'VIEW_HISTORY'  =>'view_history',
);


return $define;

?>
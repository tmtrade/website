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
	
	//行业大分类
    'CATEGORY' => array(
		'1'  => '服装箱包',
        '2'  => '日用化妆',
        '3'  => '食品商标',
		'4'  => '酒水饮料',
		'5'  => '餐饮教育',
		'6'  => '家电卫浴',
		'7'  => '电子产品',
        '8'  => '珠宝工艺',
        '9'  => '家具家纺',
        '10'  => '医用药品',
        '11'  => '广告销售',
        '12'  => '油漆燃料',
    ),

    'CATEGORY_ITEMS' => array(
        1 => array(
            '2501' => '服装、男女装、童装',
            '2507' => '鞋',
            '2502' => '婴儿装',
            '2508' => '帽',
            '2509' => '袜',
            '2510' => '手套',
            '2511' => '围巾领带',
            '2513' => '婚纱',
            '1802' => '皮具、皮包、箱包',
        ),
        2 => array(
            '0301' => '肥皂、洗衣用清洁物品',
            '0302' => '清洁去渍制剂',
            '0305' => '香料精油',
            '0306' => '化妆品',
            '0307' => '牙膏',
            '0308' => '熏料',
        ),
        3 => array(
            '2901' => '肉',
            '2902' => '非活水产品',
            '2904' => '腌制干制水果',
            '2906' => '蛋',
            '3001' => '咖啡',
            '3002' => '茶、茶饮料',
            '3004' => '糖果',
            '3005' => '营养保健品',
            '3014' => '食盐',
            '3015' => '酱油、醋',
            '3016' => '芥末、味精、沙司、酱等调味品',
        ),
        4 => array(
            '3201' => '啤酒',
            '3202' => '饮料',
            '3301' => '白酒、黄酒、葡萄酒',
        ),
        5 => array(
            '4301' => '餐厅、酒店',
            '4101' => '学校教育、培训',
            '4102' => '组织安排会议',
            '4103' => '俱乐部、KTV',
        ),
        6 => array(
            '0724' => '洗衣机',
            '1101' => '照明设备、器具',
            '1104' => '加热设备',
            '1105' => '制冷、冷藏设备',
            '1106' => '干燥、通风、空调设备',
            '1108' => '水暖管件',
            '1110' => '消毒设备',
            '1109' => '卫生设备',
        ),
        7 => array(
            '0901' => '计算机及设备',
            '0907' => '手机配件、通讯设备、导航、手机膜套',
            '0913' => '开关、插座',
            '0908' => '音响音像设备',
            '0909' => '摄影仪器',
            '0912' => '电线、电缆',
        ),
        8 => array(
            '1404' => '钟、表、计时器',
            '1403' => '珠宝、首饰',
            '2104' => '陶瓷',
        ),
        9 => array(
            '2001' => '家具',
            '2013' => '枕头',
            '2401' => '纺织品、布料',
            '2405' => '毛巾浴巾',
            '2406' => '床上用品',
            '2407' => '窗帘',
            '2701' => '地毯',
            '2702' => '席类',
            '2704' => '墙纸',
        ),
        10 => array(
            '0501' => '药品、消毒剂、中药材',
            '0502' => '婴儿食品、医用营养品',
            '0504' => '兽药',
            '0505' => '农药、杀虫剂',
            '0506' => '卫生用品',
        ),
        11 => array(
            '3501' => '广告',
            '3202' => '消费者建议机构、商业管理辅助',
            '3506' => '谈话记录（办公事务）',
        ),
        12 => array(
            '0205' => '涂料、油漆',
            '0401' => '工业用油和油脂、润滑剂',
            '0406' => '吸收灰尘用合成物、清扫用粘结灰尘合成物',
            '0402' => '发动机燃料非化学添加剂、和照明材料',
            '0405' => '照明用蜡',
        ),
    ),

	
    'ISSURE' => array(
		'1'  => '否',
        '2'  => '是',
    ),
    'AREA' => array(
		'1'  => '国内',
        '2'  => '国外',
    ),

	
	//商标类型
    'TYPES' => array(
        '1'  => '中文',
        '2'  => '英文',
        '3'  => '图形',
        '4'  => '中+英',
        '5'  => '中+图',
        '6'  => '英+图',
        '7'  => '中+英+图',
        '8'  => '数字',
    ),
    //商标字数
    'SBNUMBER' => array(
        '1'  => '一个字母或中文',
        '2'  => '两个字母或中文',
        '3'  => '三个字母或中文',
        '4'  => '四个字母或中文',
        '5'  => '五个字母或中文',
        '6'  => '五个以上字母或中文',
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
        '99'  => '/faq/rule/',
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

);


return $define;

?>
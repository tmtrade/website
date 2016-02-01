<?
/**
 * 商标详情界面
 *
 * 商标详情界面
 *
 * @package Action
 * @author  Jeany
 * @since   2015-11-10
 */
class DetailAction extends AppAction
{
	public function getTitle($data,$goods)
	{
		$title = $data['name']."_".$data['class']."类_".$goods."商标转让|买卖|交易 – 一只蝉";
		return $title;
	}
	
	//商品详情
	public function view()
	{
		$tag = $this->input('short', 'string', '');
		if ( $tag ){
			if ( strpos($tag, '-') === false ) $this->redirect('未找到页面1', '/index/error');
			list($tid, $class) = explode('-', $tag);
		}else{
			$this->redirect('未找到页面2', '/index/error');
		}
		$_info 	= $this->load('trademark')->getInfo($tid,array('`id` as `number`','class'));
		$number = $_info['number'];
		if ( empty($_info) || empty($number) ){
			$this->redirect('未找到页面3', '/index/error');
		}elseif( !in_array($class, range(1,45)) ){
			$this->redirect('未找到页面4', '/index/error');
		}
		//if ( empty($number) || !preg_match('/^[0-9a-zA-Z]+$/',$number) ){
		//	$this->redirect('未找到页面1', '/index/error');
		//}
		$info 	= $this->load('trademark')->getTmInfo($number);
		if ( empty($info) ){
			$this->redirect('未找到页面5', '/index/error');
		}
		if ( !in_array($class, $info['class']) ){
			$this->redirect('未找到页面6', '/index/error');
		}

		$this->set("platformIn", C('PLATFORM_IN'));
		$this->set("platformUrl", C('PLATFORM_URL'));
		$this->set("platformItems", C('PLATFORM_ITEMS'));

		$issale = 0;
		$tid 	= $info['tid'];
		$class 	= current($info['class']);
		$_class = implode(',', $info['class']);
		$other 	= $this->load('trademark')->getTmOther($number);//商标其他信息
		$saleId = $this->load('internal')->existSale($number);//是否出售
		if ( $saleId <= 0 ){//不是出售信息中的
			$platform 	= $other['platform'];
			$isSale 	= $this->load('blacklist')->isBlack($number) ? false : true;
			$tips = $sale = array();
			
			$contact['name']	= '蝉妹妹';
			$contact['phone']	= $this->getPhoneName($tid, $class, 0);
		}else{
			$sale 		= $this->load('internal')->getSaleInfo($saleId, 0, 1);
			$platform 	= explode(',', $sale['platform']);
			$isSale  	= $sale['status'] == 1 ? true : false;
			$tips 		= $this->load('search')->getTips($sale);

			$contact['name']	= $this->getPhoneName($tid, $class, 1);//获取联系人信息
			$contact['phone']	= empty($sale['viewPhone']) ? '18602868321' : $sale['viewPhone'];
		}

		$title['name'] 	= $info['name'];
		$title['class']	= $class;
		$goods 			= current( explode(',', $info['goods']) );
		//设置页面TITLE
		$this->set('TITLE', $this->getTitle($title,$goods));

		//读取推荐商标
		$refer 	= $this->load("internal")->getReferrer($_class, 6, $number);
		$tj 	= $this->load('search')->getListTips($refer);
		
		//查询订单是否存在
		if($this->isLogin){
			$baystate = $this->load("buy")->isBuy($info['name'], $this->userId);
		}else{
			$baystate = 0;
		}

		//电话旁边联系人信息
		$this->set("contact", $contact); 
		$this->set("info", $info);
		$this->set("sale", $sale);
		$this->set("tips", $tips);
		$this->set("tid", $tid);
		$this->set("class", $class);
		$this->set("isSale", $isSale);
		$this->set("userMobile", $this->userMobile);
		$this->set("platform", $platform);
		$this->set("tj", $tj);
		$this->set("baystate", $baystate);
		$this->display();
	}
	
	
	/**
	 * 详情界面添加我要买
	 * 
	 * @author	JEANY
	 * @since	2015-11-12
	 *
	 * @access	public
	 * @return	void
	 */
	public function addBuy()
	{
		$saleid = $this->input('saleid','int');
		$phone  = $this->input('phone','string');
		$sid    = $this->input('sid','string');
		$sidArea  = $this->input('sidArea','string');
		
		if($this->isLogin){
			//查询商标是否存在
			$sale = $this->load("internal")->getSaleInfo($saleid,0,0);
			if(!$sale){
				$result = -4; //商标数据不存在
				echo $result;
				exit;
			}
			if ( empty($phone) ){
				$_phone = empty($this->userMobile) ? $this->userEmail : $this->userMobile;
			}else{
				$_phone = $phone;
			}
			$isExist = array(
				'loginUserId'		=> $this->userId,
				'source'			=> 10, //来源展示页
				'name'				=> $sale['name'],
				'class'				=> $sale['class'],
				'contact'			=> $this->nickname,
				'phone'				=> $_phone,
				'buyType'			=> 1,
				'need'				=> "商标号:".$sale['number'].",类别:".$sale['class'],
			);

			//查询订单是否存在
			$buyData = $this->load("buy")->isExist($isExist);
			if($buyData){
				$result = -2; //数据已经存在
			}else{
				$isExist['date']	= time();
				$result				= $this->load("buy")->create($isExist);
				/**写入分配系统**/
				$need = $isExist['need']."联系人：". $user['nickname']."联系电话：".$_phone;
				$this->load('temp')->pushTrack($need, $user['nickname'], $_phone, $sid, $sidArea, 1);
				/**写入分配系统 END**/		
			}
		}else{
			$result = -3; //未登录
		}
		echo $result;
	}
	
	/**
	 * 检查是够购买过商标
	 * 
	 * @author	JEANY
	 * @since	2015-11-23
	 *
	 * @access	public
	 * @return	void
	 */
	public function getOrderState()
	{
		$result = 1;
		$phone  = $this->input('phone','string');
		$tid    = $this->input('tid','string');

		$col 	= array('`trademark` as `name`','id','`class`');
		$sale 	= $this->load("trademark")->getInfo($tid,$col);
		
		if ( empty($phone) ){
			$_phone = empty($this->userMobile) ? $this->userEmail : $this->userMobile;
		}else{
			$_phone = $phone;
		}
		
		$isExist = array(
			'phone'					=> $_phone,
			'source'				=> 10,//来源一只禅
			'name'					=> $sale['name'],
			'class'					=> $sale['class'],
			'memo'					=> $sale['id'],
			'buyType'				=> 1,
			'need'					=> "商标号:".$sale['id'].",类别:".$sale['class'],
		);
		
		if( $this->isLogin ){
			$isExist['loginUserId'] = $this->userId;
			$isExist['contact']		= $this->nickname;
		}else{
			$isExist['loginUserId'] = 0;
			$isExist['contact']		= '';
		}
		$buyData = $this->load("buy")->isExist($isExist);
		if($buyData){
			$result = -2; //数据已经存在
		}
		echo $result;
	}
	
	/**
	 * 检查是够购买过商标
	 * 
	 * @author	JEANY
	 * @since	2015-11-23
	 *
	 * @access	public
	 * @return	void
	 */
	public function addBuyByPhone()
	{
		$result 	= 1;
		$tid 		= $this->input('tid','int');
		$phone  	= $this->input('phone','string');
		$sid    	= $this->input('sid','string');
		$sidArea  	= $this->input('sidArea','string');
		
		$col 	= array('`trademark` as `name`','id','`class`');
		$sale 	= $this->load("trademark")->getInfo($tid,$col);
		
		if(!$sale){
			$result = -4; //商标数据不存在
			echo $result;
			exit;
		}

		if ( empty($phone) ){
			$_phone = empty($this->userMobile) ? $this->userEmail : $this->userMobile;
		}else{
			$_phone = $phone;
		}
		
		$isExist = array(
			'phone'					=> $_phone,
			'source'				=> 10,//来源展示页
			'name'					=> $sale['name'],
			'class'					=> $sale['class'],
			'memo'					=> $sale['id'],
			'buyType'				=> 1,
			'need'					=> "商标号:".$sale['id'].",类别:".$sale['class'],
		);
		
		if(!empty($this->userId)){
			$isExist['loginUserId'] = $this->userId;
			$isExist['contact']		= $this->nickname;
		}else{
			$isExist['loginUserId'] = 0;
			$isExist['contact']		= '';
		}
		//查询订单是否存在
		$buyData = $this->load("buy")->isExist($isExist);
		if($buyData){
			$result = -2; //数据已经存在
		}else{
			$isExist['date']	= time();
			$result				= $this->load("buy")->create($isExist);
			/**写入分配系统**/
			$need = $isExist['need']."联系电话：".$_phone;
			$this->load('temp')->pushTrack($need, "", $_phone, $sid, $sidArea, 1);
			/**写入分配系统 END**/		
		}
		echo $result;
	}
	
	
	
	
	/* 
	* 电话号码姓氏挑选
	*/ 
	public function getPhoneName($tid,$class,$issale = 0) 
	{ 
		if ( $issale ){
			$name = "赵 钱 孙 李 周 吴 郑 王 冯 陈 褚 卫 蒋 沈 韩 杨 朱 秦 尤 许 何 吕 施 张 孔 曹 严 华 金 魏 陶 姜 戚 谢 邹 喻 
			柏 水 窦 章 云 苏 潘 葛 奚 范 彭 郎 鲁 韦 昌 马 苗 凤 花 方 俞 任 袁 柳 酆 鲍 史 唐 费 廉 岑 薛 雷 贺 倪 汤 刘 母 白 
			滕 殷 罗 毕 郝 邬 安 常 乐 于 时 傅 皮 卞 齐 康 伍 余 元 卜 顾 孟 平 黄 和 穆 萧 尹 姚 邵 湛 汪 祁 毛 禹 狄 欧阳 慕容  
			米 贝 明 臧 计 伏 成 戴 谈 宋 茅 庞 熊 纪 舒 屈 项 祝 董 梁 杜 阮 蓝 闵 席 季 麻 强 贾 路 娄 危 江 童 颜 郭 蒲 崔 沙 
			梅 盛 林 刁 锺 徐 邱 骆 高 夏 蔡 田 樊 胡 凌 霍 虞 万 支 柯 昝 管 卢 莫 经 房 裘 缪 干 解 应 宗 丁 宣 贲 邓";
			
			$lastTid 	= ceil(substr($tid,-1)/3)*$class;
			$sex 		= ($class % 2 == 0) ? 1 : 2;
			$gender 	= array(1 => '先生', 2 => '女士');
			$nameArr 	= explode(" ", $name);
			$name 		= $nameArr[$lastTid].$gender[$sex];
			return $name;
		}

		$phoneArr 	= $this->load('phone')->getSexPhone();
		$phoneid 	= ceil(substr($tid,-1)/3*2) > 5 ? 5 : ceil(substr($tid,-1)/3*2);
		$phone 		= empty($phoneArr[$phoneid]) ? '18602868321' : $phoneArr[$phoneid];
		return $phone;
	}
	
	
	
}
?>
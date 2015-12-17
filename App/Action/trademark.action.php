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
class TrademarkAction extends AppAction
{
	
	public function index()
	{
		$this->display();
	}

	public function getTitle($data,$goods)
	{
		$title = $data['name']."_".$data['class']."类_".$goods."商标转让|买卖|交易 – 一只蝉";
		return $title;
	}
	
	public function view()
	{
		$tid 	= $this->input("tid","int");
		$class 	= $this->input("class","int");
		$issale = 0;
		if ( $tid <= 0 || $class <= 0 || !in_array($class, range(1, 45)) ){
			MessageBox::halt('未找到相关数据2！');
		}

		$this->set("platformIn",C('PLATFORM_IN'));
		$this->set("platformUrl",C('PLATFORM_URL'));
		$this->set("platformItems",C('PLATFORM_ITEMS'));
	
		//出售列表里面有的数据
		$data   = $detail = array();
        $data 	= $this->load("sale")->getDetail($tid,$class,true);
		/**如果是点击过来不存在出售的商标，那就查询下架商标是否存在**/
		if(!$data){
			$data 	= $this->load("sale")->getDetail($tid,$class);
		}
		
		if ( $data ){
			$detail  		= $this->load("sale")->getTrademarkDetail($data['id'], $tid);
			$detail['tid'] 	= $data['tid'];
			$detail 		= $this->load('search')->getTips($detail);
			$platform		= $this->load("sale")->getPlatform($data);
		}else{
			$issale = 1;
			//原始商标数据里面的数据
			$info = $this->load('trademark')->trademarks($tid,$class);
			if($info){
				$infoDetail = $this->load('trademark')->trademarkDetail($info);
				$classes 			= C('CLASSES') ;
				$info['name'] 		= $info['trademark'];
				$info['classValue'] = $classes[$info['class']];
				$info['number'] 	= $info['id'];
				if ( $infoDetail ){
					$infoDetail['tid'] = $info['auto'];
					$infoDetail = $this->load('search')->getTips($infoDetail);
				}
			}
		}
		if ( empty($info)  && empty($data) ){
			MessageBox::halt('未找到相关数据3！');
		}
		$baystate = 0;
		

		//读取推荐商标
		$tj  	= $this->load("sale")->getDetailtj($class,6,$data['tid']);
		$data  	= empty($data) ? $info : $data;
		$detail = (empty($data) || empty($detail)) ? $infoDetail : $detail;
		
		//查询订单是否存在
		if($this->userInfo){
			$user 		= $this->userInfo;
			$buyData 	= $this->load("buy")->getDataBySale($data['name'],$data['class'],$user['userId']);
			$baystate 	= $buyData ? 1 : 0;
		}
		$title 	= empty($data['trademark']) ? $data['name'] : $data['trademark'];
		//设置页面TITLE
		$this->set('TITLE', $this->getTitle($data,$detail['goods']));

		//电话旁边联系人信息
		$this->set("contact",$this->getPhoneName($tid,$class,$issale)); 
		$data['group'] = $this->emptyreplace($data['group']);
		$this->set("info",empty($info)?array():$info);
		$this->set("data",$data);
		$this->set("tid",$tid);
		$this->set("userMobile",$this->userMobile);
		$this->set("detail",$detail);
		$this->set("platform",$platform);
		$this->set("tj",$tj);
		$this->set("baystate",$baystate);	
		$this->display();
	}
	
	
	
	
	/* 
	* 群组字符串替换处理
	*/ 
	public function emptyreplace($str) 
	{ 
		$str = str_replace('　', ' ', $str); //替换全角空格为半角 
		$str = str_replace('<br>', ' ', $str); //替换BR
		$str = str_replace('&lt;br&gt;', ' ', $str); //替换BR
		$str = str_replace('*', '', $str);  //替换*
		$str = preg_replace('/\(.*?\)/', ' ', $str);//替换括号里面的
		$result = '';
		$strArr = explode(" ",$str);
		$strArr = array_unique(array_filter($strArr)); //去掉空字符串
		$result = implode(',', $strArr);
		return $result; 
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
		
		if($this->userInfo){
			//查询商标是否存在
			$sale = $this->load("sale")->getSaleById($saleid);
			if(!$sale){
				$result = -4; //商标数据不存在
				echo $result;
				exit;
			}
			$user = $this->userInfo;
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
				$need = $isExist['need']."联系人：". $user['nickname']."联系电话：".$user['mobile'];
				$this->load('temp')->pushTrack($need, $user['nickname'], $user['mobile'], $sid, $sidArea, 1);
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
		//$saleid = $this->input('saleid','int');
		//$sale	= $this->load("sale")->getSaleById($saleid);
		$phone  = $this->input('phone','string');
		$tid    = $this->input('tid','int');
		$sale = $this->load("trademark")->getInfo($tid,'name,class');
		
		if ( empty($phone) ){
			$_phone = empty($this->userMobile) ? $this->userEmail : $this->userMobile;
		}else{
			$_phone = $phone;
		}
		
		$isExist = array(
			'phone'					=> $_phone,
			'source'				=> 10,//来源一只禅
			'name'					=> $sale['trademark'],
			'class'					=> $sale['class'],
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
		
		//查询商标是否存在
		//$sale = $this->load("sale")->getSaleById($saleid);
		$sale = $this->load("trademark")->getInfo($tid,'name,class,id');
		
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
			'name'					=> $sale['trademark'],
			'class'					=> $sale['class'],
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
			$need = $isExist['need']."联系电话：".$phone;
			$this->load('temp')->pushTrack($need, "", $phone, $sid, $sidArea, 1);
			/**写入分配系统 END**/		
		}
		echo $result;
	}
	
	
	
	
	/* 
	* 电话号码姓氏挑选
	*/ 
	public function getPhoneName($tid,$class,$issale = 0) 
	{ 
		
		if($issale){
			
			$contact['name'] = "蝉妹妹";
		}else{
			$name = "赵 钱 孙 李 周 吴 郑 王 冯 陈 褚 卫 蒋 沈 韩 杨 朱 秦 尤 许 何 吕 施 张 孔 曹 严 华 金 魏 陶 姜 戚 谢 邹 喻 
			柏 水 窦 章 云 苏 潘 葛 奚 范 彭 郎 鲁 韦 昌 马 苗 凤 花 方 俞 任 袁 柳 酆 鲍 史 唐 费 廉 岑 薛 雷 贺 倪 汤 刘 母 白 
			滕 殷 罗 毕 郝 邬 安 常 乐 于 时 傅 皮 卞 齐 康 伍 余 元 卜 顾 孟 平 黄 和 穆 萧 尹 姚 邵 湛 汪 祁 毛 禹 狄 欧阳 慕容  
			米 贝 明 臧 计 伏 成 戴 谈 宋 茅 庞 熊 纪 舒 屈 项 祝 董 梁 杜 阮 蓝 闵 席 季 麻 强 贾 路 娄 危 江 童 颜 郭 蒲 崔 沙 
			梅 盛 林 刁 锺 徐 邱 骆 高 夏 蔡 田 樊 胡 凌 霍 虞 万 支 柯 昝 管 卢 莫 经 房 裘 缪 干 解 应 宗 丁 宣 贲 邓";
			$lastTid = ceil(substr($tid,-1)/3)*$class;
			if($class%2==0){$sex=1;}else{$sex=2;}
			$gender = array(1=>'先生',2=>'女士');
			$nameArr = explode(" ",$name);
			$contact['name']  = $nameArr[$lastTid].$gender[$sex];
		}
		$phoneArr = array('15811270065','15811378137','15810761501','15811132605','15810113823','15811137365');
		$phoneid = ceil(substr($tid,-1)/3*2) > 5 ? 5 : ceil(substr($tid,-1)/3*2);
		$phone = $phoneArr[$phoneid];
		$contact['phone'] = substr($phone,0,3).'*****'.substr($phone,-3);
		return $contact;
	}
	
	
	
}
?>
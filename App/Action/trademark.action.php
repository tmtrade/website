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
    public $pageTitle   = '%s - 一只蝉';

	public function index()
	{
		$this->display();
	}
	
	public function view()
	{
		$tid 	= $this->input("tid","int");
		$class 	= $this->input("class","int");

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
		//查询订单是否存在
		if($this->userInfo && $data['id']){
			$user 		= $this->userInfo;
			$buyData 	= $this->load("buy")->getDataBySale($data['name'],$data['class'],$user['userId']);
			$baystate 	= $buyData ? 1 : 0;
		}
		//读取推荐商标
		$tj  	= $this->load("sale")->getDetailtj($class,6,$data['tid']);
		$data  	= empty($data) ? $info : $data;
		$detail = (empty($data) || empty($detail)) ? $infoDetail : $detail;

		$title 	= $data['trademark'] ? $data['trademark'] : $data['name'];
		//设置页面TITLE
		$this->set('TITLE', sprintf($this->pageTitle, $title));
		
		$data['group'] = $this->emptyreplace($data['group']);
		$this->set("info",$info);
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
		
		if($this->userInfo){
			//查询商标是否存在
			
			$sale = $this->load("sale")->getSaleById($saleid);
			if(!$sale){
				$result = -4; //商标数据不存在
				echo $result;
				exit;
			}
			$user = $this->userInfo;
			$isExist = array(
				'loginUserId'		=> $this->userId,
				'source'			=> 4, //来源展示页
				'name'				=> $sale['name'],
				'class'				=> $sale['class'],
				'contact'			=> $this->nickname,
				'phone'				=> $this->userMobile,
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
		
		$isExist = array(
			'phone'					=> $phone,
			'source'				=> 4,//来源展示页
			'name'					=> $sale['name'],
			'class'					=> $sale['class'],
			'buyType'				=> 1,
			'need'					=> "商标号:".$sale['number'].",类别:".$sale['class'],
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
		$result = 1;
		$tid = $this->input('tid','int');
		$phone  = $this->input('phone','string');
		
		//查询商标是否存在
		//$sale = $this->load("sale")->getSaleById($saleid);
		$sale = $this->load("trademark")->getInfo($tid,'name,class');
		
		if(!$sale){
			$result = -4; //商标数据不存在
			echo $result;
			exit;
		}
		
		$isExist = array(
			'phone'					=> $phone,
			'source'				=> 4,//来源展示页
			'name'					=> $sale['name'],
			'class'					=> $sale['class'],
			'buyType'				=> 1,
			'need'					=> "商标号:".$sale['number'].",类别:".$sale['class'],
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
			$result					= $this->load("buy")->create($isExist);
		}
		echo $result;
	}
	
	
	
	
}
?>
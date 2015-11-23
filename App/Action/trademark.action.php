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
	
	public function view()
	{
		$tid = $this->input("tid","int");
		$class = $this->input("class","int");

		if ( $tid <= 0 || $class <= 0 || !in_array($class, range(1, 45)) ){
			MessageBox::halt('未找到相关数据2！');
		}
		//出售列表里面有的数据
		$data   =  $detail  = array();
        $data    = $this->load("sale")->getDetail($tid);
		$detail  = $this->load("sale")->getTrademarkDetail($data['id'], $tid);
		
		$this->set("platformIn",C('PLATFORM_IN'));
		$this->set("platformItems",C('PLATFORM_ITEMS'));
	
		//原始商标数据里面的数据
		$info = $this->load('trademark')->trademarks($tid,$class);
		$infoDetail = $this->load('trademark')->trademarkDetail($info);
		if($info){
			$classes = C('CLASSES') ;
			$info['name'] = $info['trademark'];
			$info['classValue'] = $classes[$info['class']];
			$info['number'] = $info['id'];
		}
		if ( empty($info)  && empty($data) ){
			MessageBox::halt('未找到相关数据3！');
		}
		$baystate = 0;
		//查询订单是否存在
		if($this->userInfo && $data['id']){
			$user = $this->userInfo;
			$buyData = $this->load("buy")->getDataBySaleId($data['id'],$user['userId']);
			$baystate = $buyData ? 1 : 0;
		}
		
		//读取推荐商标
		$tj  = $this->load("sale")->getDetailtj($class,6,$data['tid']);
		$data    = empty($data) ? $info : $data;
		$detail  = empty($detail) ? $infoDetail : $detail;
		
		$data['group'] = $this->emptyreplace($data['group']);
		$this->set("data",$data);
		$this->set("detail",$detail);
		$this->set("info",$info);
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
		
		if($this->userInfo){
			//查询商标是否存在
			$sale = $this->load("sale")->getSaleById($saleid);
			if(!$sale){
				$result = -4; //商标数据不存在
				echo $result;
				exit;
			}
			$user = $this->userInfo;
			//查询订单是否存在
			$buyData = $this->load("buy")->getDataBySaleId($saleid,$user['userId']);
			if($buyData){
				$result = -2; //数据已经存在
			}else{
				$buy['loginUserId'] = $user['userId'];
				$buy['source'] = 4; //来源展示页
				$buy['name']   = $sale['name'];
				$buy['class']  = $sale['class'];
				$buy['contact']= $user['nickname'];
				$buy['phone']  = $user['mobile'];
				$buy['date']   = time();
				$buy['saleId'] = $saleid;
				$buy['need']   = "商标号:".$sale['number'].",类别:".$sale['class']."";
				$result = $this->load("buy")->create($buy);
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
		$saleid = $this->input('saleid','int');
		$phone  = $this->input('phone','string');
		$buyData = $this->load("buy")->getDataByContact($saleid,$phone);
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
		$saleid = $this->input('saleid','int');
		$phone  = $this->input('phone','string');
		
		//查询商标是否存在
		$sale = $this->load("sale")->getSaleById($saleid);
		if(!$sale){
			$result = -4; //商标数据不存在
			echo $result;
			exit;
		}
		//查询订单是否存在
		$buyData = $this->load("buy")->getDataByContact($saleid,$phone);
		if($buyData){
			$result = -2; //数据已经存在
		}else{
			$buy['source'] = 4; //来源展示页
			$buy['name']   = $sale['name'];
			$buy['class']  = $sale['class'];
			$buy['phone']  = $phone;
			$buy['date']   = time();
			$buy['saleId'] = $saleid;
			$buy['need']   = "商标号:".$sale['number'].",类别:".$sale['class']."";
			$result = $this->load("buy")->create($buy);
		}
		echo $result;
	}
	
	
	
	
}
?>
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
		$detail  = $this->load("sale")->getTrademarkDetail($data['id']);
		$this->set("platformIn",C('PLATFORM_IN'));
		$this->set("platformItems",C('PLATFORM_ITEMS'));
	
		//原始商标数据里面的数据
		$info = $this->load('trademark')->trademarks($tid,$class);
		$infoDetail = $this->load('trademark')->trademarkDetail($info,$info['auto']);
		if($info){
			$classes = C('CLASSES') ;
			$info['name'] = $info['trademark'];
			$info['classValue'] = $classes[$info['class']];
			$info['number'] = $info['id'];
		}
		if ( empty($info)  && empty($data) ){
			MessageBox::halt('未找到相关数据3！');
		}
		//读取推荐商标
		$tj  = $this->load("sale")->getDetailtj($class,6,$data['id']);
		$data    = empty($data) ? $info : $data;
		$detail  = empty($detail) ? $infoDetail : $detail;
		$data['group'] = $this->emptyreplace($data['group']);
		$this->set("data",$data);
		$this->set("detail",$detail);
		$this->set("info",$info);
		$this->set("tj",$tj);
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
	
	
	public function sell()
	{
		$this->display();
	}
	
	/**
	 * 获取商标信息
	 * 
	 * @author	JEANY
	 * @since	2015-09-16
	 *
	 * @access	public
	 * @return	void
	 */
	public function getselldata()
	{
		$number	= $this->input("number","string");
		$data   = $this->load('trademark')->trademarks($number);
		$result = array();
		if($data){
			$detail = $this->load('trademark')->trademarkDetail($data,$data['auto']);
			$status = array('申请中','已无效','冻结中');
			if(in_array($data['rows'][0]['status'],$status)){
				//不能出售的商标
				$result['status'] = "0";
				$result['statusValue'] = strip_tags($data['rows'][0]['status']);
			}else{
				$result['sbname']=$data['trademark'];
				$result['proposer']=$detail['proposer'];
				$result['imgurl']=$detail['imgurl'];
			}
		}
		echo json_encode($result);
	}
	
	/**
	 * 出售商标数据处理存储
	 * 
	 * @author	JEANY
	 * @since	2015-11-12
	 *
	 * @access	public
	 * @return	void
	 */
	public function addsell()
	{
		$data = $this->getFormData();	
		$url = '/trademark/sell/';
		if($data['name']){
			foreach($data['name'] as $key => $item){
				$sales['name']     = $item;
				$sales['phone']    = $data[$key];
				$sales['contact']  = $data['contact'];
				$sales['price']    = $data['price'];
				//检查传过来的数据
				$this->checkselldata($sales);
				$sales['guideprice'] = $this->getSalePrice($sales['price']);
				//商标信息
				$result   = $this->load('trademark')->trademarks($sales['number']);
				if($result['rows']){
					$sales['source']     = 4; //来源，前台展示页
					$sales['status']     = 5; //销售状态
					$sales['memo']       = $result['total'] > 1 ? "该商标为一标多类，必须捆绑出售".$result['rows'][0]['auto'] : ""; 
					$sales['name']       = $result['rows'][0]['trademark'];
					$sales['proposerId'] = $result['rows'][0]['proposer_id'];
					$sales['newId']      = $result['rows'][0]['newId'];
					/*认证状态*/
					$approves  = $this->getApprove($sales);
					$sales['type']           = $approves['type'];
					$sales['approveStatus']  = $approves['status'];
					$sales['date']  	     = time();
					//商标详细信息
					$detail['number']        = $sales['number']; 
					$detail['area']          = 1;
					//添加数据
					$tradeId = $this->addSellDataToSql($result['rows'],$sales,$detail);
					if($tradeId){
						$url  =  "/trademark/sellok/";
						header("Location:".$url);
					}else{
						$msg = '操作失败,请重试';
						$this->redirect($msg, $url);
					}
				}else{
					$msg = '没有相应的商标数据';
					$this->redirect($msg, $url);
				}
			}
		}
	}
	
	/**
	 * 检验出售数据的添加
	 * 
	 * @author	JEANY
	 * @since	2015-11-12
	 *
	 * @access	public
	 * @return	void
	 */
	public function checkselldata($data)
	{
		if ( empty($data['input-number']) ){
			$msg = '您还未输入商标号，请填写';exit();
		}elseif(!preg_match('/^\d*$/',$data['number'])){
			$msg = '请输入正确商标号！';exit();
		}
		
		if ( empty($data['input-iphone']) ){
			$msg = '您还未输入电话，请填写';exit();
		}elseif(!preg_match("/^1[3,4,5,7,8]{1}[0-9]{9}$/",$data['iphone'])){
			$msg = '请输入正确手机号！';exit();
		}
		
		if ( empty($data['input-price']) ){
			$msg = '您还未输入底价，请填写';exit();
		}elseif(!preg_match('/^\d*$/',$data['price'])){
			$msg = '请输入正确价格！';exit();
		}
	}
	
	/**
	 * 指导价格(出售价格) 出售价格系统自动添加，出售价格=底价+浮动比例(不同底价对应不同的浮动比例)
	 * 
	 * @author	Jeany
	 * @since	2015-11-12
	 * @access	public
	 * @return	int
	 */
	 public function getSalePrice($price)
	{	
		$ratio = C("FLAOT_RATIO");
		switch($price){
			case ($price <= 10000):
				$key = 1;
				break;
			case ($price > 10000 && $price <= 20000):
				$key = 2;
				break;
			case ($price > 20000 && $price <= 30000):
				$key = 3;
				break;
			case ($price > 30000 && $price <= 50000):
				$key = 4;
				break;
			case ($price > 50000 && $price <= 60000):
				$key = 5;
				break;
			case ($price > 60000 && $price <= 100000):
				$key = 6;
				break;
			case ($price > 100000 && $price <= 200000):
				$key = 7;
				break;
			case ($price > 200000):
				$key = 8;
				break;
		}
		$salePrice = $price*(1+$ratio[$key]);
		return $salePrice;
	}
	
	
	/**
	 * 检验出售数据的添加
	 * 
	 * @author	JEANY
	 * @since	2015-11-12
	 *
	 * @access	public
	 * @return	void
	 */
	public function getApprove($sales)
	{
		$param = array('proposerId' => $sales['proposerId'],'phone' => $sales['phone'] ,'newId' => $sales['newId']);
		$approves = $this->load('approve')->getApprove($sales); 
		return $approves;
	}
	
	/**
	 * 出售数据数据库添加
	 * 
	 * @author	JEANY
	 * @since	2015-11-12
	 *
	 * @access	public
	 * @return	void
	 */
	public function addSellDataToSql($rows,$sales,$detail)
	{
		$tradeId = '';
		foreach($rows as $key => $item){
			$sales['class']      = $item['class'];
			$sales['tid']        = $item['auto'];
			$tradeId = $this->load('sale')->addSale($sales);
			$detail['saleId']    = $tradeId;
			$detail['name']      = $item['trademark'];
			$detail['class']     = $item['class'];
			$detail['imgurl']    = $item['imgUrl'];
			$detail['group']     = $item['group'];
			$detail['goods']     = $item['goods'];
			$detail['proposer']  = $item['proposerName'];
			$detail['status']    = $item['status'];
			$detail['validEnd']  = $item['valid_end'];
			$boolsaletrademark = $this->load('saletrademark')->Saletrademark($detail);
		}
		return $tradeId;
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
		$thisid = $this->input('id','int');
		
	}
}
?>
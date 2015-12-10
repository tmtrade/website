<?
/**
 * 商标购买界面
 *
 * 商标购买界面
 *
 * @package Action
 * @author  Jeany
 * @since   2015-11-14
 */
class SellAction extends AppAction
{
	public $pageTitle   = '我要卖 - 一只蝉';
	
	public function index()
	{
		$number	= $this->input("m","string");
		$this->set("number",$number);
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
		$data   = $this->load('trademark')->getTrademarkById($number);
		$result = array();
		
		
		//检查商标是否存在了
		if($this->userInfo && $number){
			$user = $this->userInfo;
			$saleData = $this->load("sale")->getSaleByNum($number,$user['userId']);
			if($saleData){
				$result['status'] = "-1";
				echo json_encode($result);
				exit;	
			}	
		}
			
		if($data){
			$detail = $this->load('trademark')->trademarkDetail($data);
			$status = array('已无效','冻结中');
			if(in_array($data['status'],$status)){
				//不能出售的商标
				$result['status'] = "0";
				$result['statusValue'] = strip_tags($data['status']);
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
		$num['old'] = 0;
		$num['state'] = 1;
		$num['num'] = 0;
		if($data['number']){
			
			if($this->userInfo){
				$userinfo = $this->userInfo;
				$sales['userId'] = $userinfo['userId'];
			}else{
				$userinfo = $this->load('passport')->get($data['phone']);
				//没有账号自动创建该手机账号
				// if ( empty($userinfo) ){
					// $userId = $this->register($data['phone']);//注册并发密码短信
					// if ( !$userId ) $this->returnAjax(array('code'=>3)); //未成功
					// $sales['userId'] = $userId;
				// }else{
					// $sales['userId'] = $userinfo['id'];
				// }
				
				if ( !empty($userinfo) ){
					$sales['userId'] = $userinfo['id'];
				}
			}
			foreach($data['number'] as $key => $item){
				$item = trim($item);
				$saleData = $this->load("sale")->getSaleByNum($item,$sales['userId'],$data['phone']);
				
				if($saleData){
					$num['old'] ++;
					continue;
				}
				
				$sales['number']   = $item;
				$sales['phone']    = $data['phone'];
				$sales['contact']  = $data['contact'];
				$sales['price']    = $data['price'][$key];
				//检查传过来的数据
				$this->checkselldata($sales);
				//商标信息
				$result   = $this->load('trademark')->getTrademarks($sales['number']);
				if($result){
					$sales['guideprice'] = $this->getSalePrice($sales['price']);
					$sales['source']     = 10; //来源，前台展示页
					$sales['status']     = 5; //销售状态
					$sales['memo']       = count($result) > 1 ? "该商标为一标多类，必须捆绑出售".$result['rows'][0]['auto'] : ""; 
					$sales['name']       = $result[0]['trademark'];
					$sales['proposerId'] = $result[0]['proposer_id'];
					$sales['newId']      = $result[0]['newId'];
					$sales['group']      = $this->load('sale')->emptyreplace($result[0]['group']);
					/*认证状态*/
					$approves  = $this->getApprove($sales);
					$sales['type']           = $approves['type'];
					$sales['approveStatus']  = $approves['status'];
					$sales['date']  	     = time();
					//商标详细信息
					$detail['number']        = $sales['number']; 
					$detail['area']          = 1;
					//添加数据
					$tradeId = $this->addSellDataToSql($result,$sales,$detail);
					if($tradeId){ 
						$num['num'] ++;
						/**写入分配系统**/
						$need = "商标号:".$item." 价格：".$sales['price']." 联系人：".$sales['contact']."  联系电话：".$sales['phone'];
						$this->load('temp')->pushTrack($need, $sales['contact'], $sales['phone'], $data['sid'], $data['sidArea'], 2);
						/**写入分配系统 END**/
					}
				}else{
					$num['state'] = -2; //商标数据不存在
				}
			}
		}else{
			$num['state'] = -2; //商标数据不存在
		}
		
		$num['all'] = $num['num'] + $num['old'];
		echo json_encode($num);
	}
	
	/**
     * 注册手机账号
     *
     * 通过手机号进行注册并发送随机的8位密码
     * 
     * @author  Xuni
     * @since   2015-11-14
     *
     * @return  bool
     */
    private function register($mobile)
    {
       return $this->load('passport')->autoRegMoblie($mobile);
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
		if ( empty($data['number']) ){
			$msg = '您还未输入商标号，请填写';exit(-3);
		}elseif(!preg_match('/^[0-9a-zA-Z]*$/',$data['number'])){
			$msg = '请输入正确商标号！';exit(-3);
		}
		
		if ( empty($data['phone']) ){
			$msg = '您还未输入电话，请填写';exit(-3);
		}elseif(!preg_match("/^1[3,4,5,7,8]{1}[0-9]{9}$/",$data['phone'])){
			$msg = '请输入正确手机号！';exit(-3);
		}
		
		if ( empty($data['price']) ){
			$msg = '您还未输入底价，请填写';exit(-3);
		}elseif(!preg_match('/^\d*$/',$data['price'])){
			$msg = '请输入正确价格！';exit(-3);
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
			$sales['sblength']  = $this->load('sale')->getTrademarkLength($item['trademark']);
			$sales['platform']  = $this->load('sale')->getTrademarkPlatform($item['class']);
			$sales['types']     = $this->load('sale')->getTrademarkType($item['trademark']);
			$tradeId = $this->load('sale')->addSale($sales);
			$detail['saleId']    = $tradeId;
			$detail['name']      = $item['trademark'];
			$detail['class']     = $item['class'];
			$detail['imgurl']    = $item['imgUrl'];
			$detail['goods']     = $item['goods'];
			$detail['proposer']  = $item['proposerName'];
			$detail['status']    = $item['status'];
			$detail['validEnd']  = $item['valid_end'];
			$detail['intro']     = '';
			$detail['introTwo']  = '';
			$boolsaletrademark   = $this->load('saletrademark')->Saletrademark($detail);
		}
		return $tradeId;
	}
}
?>
<?
/**
 * 交易商标
 *
 * 交易商标
 * 
 * @package	Module
 * @author	Xuni
 * @since	2015-11-05
 */
class SaleModule extends AppModule
{
	public $source = array();
    public $status   = array();
	public $area   = array();
	public $type   = array();
	public $class   = array();
	public $offPrice   = array();
	public $saletype   = array();
	public $platform   = array();
	public $label   = array();
	public $sblength   = array();
	
	/**
     * 引用业务模型
     */
    public $models = array(
        'sale'			=> 'sale',
		'member'		=> 'member',
		'proposer'		=> 'proposer',
        'saletrademark' => 'saletrademark',
		'group'			=> 'group',
    );
	
	/**
     * 初始化变量值
     * @author	Jeany
     * @since	2015-07-22
     */
    public function __construct()
    {
		$this->type   = C('APPROVE_TYPE');
		$this->approveStatus   = C('APPROVE_STATUS');
		$this->types = C("TYPES"); //商标分类
		$this->saletype = array(1 => '出售',2 => '许可'); //底价查询分类
		$this->platform = C("PLATFORM_IN"); //入驻平台
		$this->classes = C('CLASSES');
    }

    /**
     * 获取首页特价商标显示数据，暂时为4条记录
     * 
     * @author  Xuni
     * @since   2015-11-18
     *
     * @access  public
     * @return  array   $list       数据列表
     */
    public function getIndexOffprice()
    {
        $r['raw']   = " tjpic != '' ";
        $r['limit'] = 1000;
        $r['col']   = array('saleId', 'tjpic');
        $res        = $this->load('saletrademark')->find($r);
        $items      = arrayColumn($res, 'tjpic', 'saleId');
        $ids        = array_keys($items);
		if(!empty($ids)){
			$role['in']     = array('id'=>$ids);
		}
        $role['group']  = array('tid'=>'asc');
        $role['limit']  = 4;
        $role['col']    = array('id', 'tid', 'number', 'class', 'name');
        $role['order']  = array('date'=>'desc');
        $role['notIn']  = array('status'=>array(2,3,4,6));
        $role['raw']    = ' ( `salePrice` > 0 and (`salePriceDate` = 0  OR `salePriceDate` > unix_timestamp(now())) )';
        $list = $this->import('sale')->find($role);
        foreach ($list as $k => $v) {
            $list[$k]['imgurl'] = TRADE_URL.$items[$v['id']];
        }
        return $list;
    }
		
    /**
     * 通过条件查询商标信息--首页使用
     * 
     * @author  Jeany
     * @since   2015-11-09
     *
     * @access  public
     * @param   string  $param      查询条件
     * @param   int     $num        查询数量
     *
     * @return  array   $data       查询数据
     */
	public function getSaleList($param, $num , $page=1)
	{
		if($param){
			foreach($param as $key => $val){
				if($key == 'notId'){
					$r['notIn']    = array('id'=>$val);
				}else{
					$r['ft'][$key] = $val;
				}	
			}
		}
		
		$r['eq']['area']  = 1;//可出售商标
		$r['in']          = array('status' => array('1','5'));
		$r['page']        = $page;
        $r['limit']       = $num;
		$r['col']         = array('name,class,id,source,number,tid');
		$r['group']       = array('tid'=>'asc');
        $r['order']       = array('isTop' => 'desc','type' => 'asc','date' => 'desc');
        $data = $this->import('sale')->findAll($r);
		$data['notId'] = array();
        foreach($data['rows'] as $k => $item){
			//$data['rows'][$k]['imgurl'] = $this->getImg($item['id']); 
			$data['rows'][$k]['url']    = "/trademark/view/?tid=".$item['tid']."&class=".$item['class']; 
			$data['rows'][$k]['name']   = mbSub($item['name'],0,4); 
            $data['rows'][$k]['source'] = isset( $this->source[$item['source']] ) ? $this->source[$item['source']] : $item['source'];
			$data['rows'][$k]['classes']  = isset( $this->classes[$item['class']] ) ? $this->classes[$item['class']] : $item['class'];
			
			$img	= $this->load('saletrademark')->getOffpriceImg($item['id']);
            $imgUrl	= empty($img['bzpic']) ? $img['tjpic'] : $img['bzpic'];
			if ( empty($imgUrl) ) {
				$data['rows'][$k]['imgurl'] = $this->load('trademark')->getImg($item['tid']);
			}else{
				$data['rows'][$k]['imgurl'] = TRADE_URL.$imgUrl;
			}
			
			array_push($data['notId'], $item['id']);
        }
		return $data;
	}
	
	/**
	 * 出售详细信息图片信息
	 * @author	Jeany
	 * @since	2015-09-15
	 *
	 * @access	public
	 * @param	array		$param  用户名称
	 * @return	array
	 */
	public function getImg($id)
	{	
		$r['limit']           = 1;
        $r['eq']['saleId']    = $id;
		$r['col']             = array('imgurl');
        $data                 = $this->import("saletrademark")->find($r);
		if($data['imgurl']){
			if(false===strpos(strtolower($data['imgurl']), 'http://')){
				$data['imgurl'] = C('IMAGE_HOST').$data['imgurl']; 
			}
		}
        return $data['imgurl'];
	}
	
	/**
	 * 出售详细信息
	 * @author	Jeany
	 * @since	2015-07-23
	 *
	 * @access	public
	 * @param	array		$param  用户名称
	 * @return	array
	 */
	public function getDetail($tid,$class,$status = false)
	{	
		$r['limit'] = 1;
		$r['eq']['tid'] = $tid;
		$r['eq']['class'] = $class;
		$r['order']         = array('salePrice' => 'desc','date' => 'desc');
		if($status)$r['in']   = array('status' => array('1','5')); //状态
        $data = $this->import("sale")->find($r);
        if(empty($data)) {return array();}
        $data['classValue']     = isset( $this->classes[$data['class']] ) ? $this->classes[$data['class']] : $data['class'];
		$data['date']      = date('Y-m-d',$data['date']);
        return $data;
	}
	
	/**
	 * 通过商标号和登录账号判断是否存在
	 * @author	Jeany
	 * @since	2015-07-23
	 *
	 * @access	public
	 * @param	array		$param  用户名称
	 * @return	array
	 */
	public function getSaleByNum($number,$userid)
	{	
		$r['limit'] = 1;
		$r['eq']['number'] = $number;
		$r['eq']['userId'] = $userid;
		$r['in']        = array('status' => array('1','5'));
        $data = $this->import("sale")->find($r);
        return $data;
	}
	
	/**
	 * 出售详细信息
	 * @author	Jeany
	 * @since	2015-07-23
	 *
	 * @access	public
	 * @param	array		$param  用户名称
	 * @return	array
	 */
	public function getTrademarkDetail($id, $tid)
	{
        $r['eq']['saleId']  = $id;
        $data               = $this->import("saletrademark")->find($r);
        //$data['status']     = implode(',', $this->load('trademark')->getSecond($tid));
		$status	 	= $this->load('trademark')->getSecond($tid);
		$data['status'] = $status[0];
		$data['bzpic']     =  $data['bzpic'] ? C('IMAGE_HOST').$data['bzpic'] : "";
        return $data;
	}
	
	/**
	 * 出售详细信息
	 * @author	Jeany
	 * @since	2015-07-23
	 *
	 * @access	public
	 * @param	array		$param  用户名称
	 * @return	array
	 */
	public function getPlatform($data)
    {
        if ( empty($data['tid']) ) return $data;

        $r['limit'] = 100;
        $r['eq'] = array(
            'area'  => 1,
            'tid'   => $data['tid'],
        );
		$r['col']   = array('platform');
        $r['notIn'] = array('status'=>array(2,3,4,6));
        $res = $this->import('sale')->find($r);
        if ( empty($res) ) return $data;
        $arr = $arrAll = array();
        foreach ($res as $k => $v) {
			if($v['platform']){
				$arr = explode(",",$v['platform']);
				foreach($arr as $key => $item){
					if(!in_array($arrAll,$item)){
						
						$arrAll[] = $item;
					}
				}
			}
        }
		$arrAll=array_unique($arrAll);
        return $arrAll;
    }
	
	
	 /**
     * 通过条件查询商标信息--首页使用
     * 
     * @author  Jeany
     * @since   2015-11-09
     *
     * @access  public
     * @param   string  $param      查询条件
     * @param   int     $num        查询数量
     *
     * @return  array   $data       查询数据
     */
	public function getDetailtj($class, $num , $tid)
	{
		$r['eq']['class']  = $class;//可出售商标
		$r['eq']['area']  = 1;//可出售商标
		$r['raw']  = "status not in(2,3,4,6) ";
		$r['raw']  .=  $tid ? " and tid <> $tid " : "";
        $r['limit']         = $num;
		$r['col']           = array('name,class,id,source,number,tid,platform');
        $r['order']         = array('type' => 'asc','date' => 'desc');
		$r['group']       = array('tid'=>'asc');
        $data = $this->import('sale')->findAll($r);
        foreach($data['rows'] as $k => $item){
			$data['rows'][$k]['imgurl'] = $this->getImg($item['id']); 
			$data['rows'][$k]['url']    = "/trademark/view/?tid=".$item['tid']."&class=".$item['class']; 
			$data['rows'][$k]['name']   = mbSub($item['name'],0,4); 
            $data['rows'][$k]['source'] = isset( $this->source[$item['source']] ) ? $this->source[$item['source']] : $item['source'];
			$data['rows'][$k]['classes']  = isset( $this->classes[$item['class']] ) ? $this->classes[$item['class']] : $item['class'];
			$data['notId'][] = $item['id'];
        }
		return $data;
	}
	
	/**
	 * 通过出售详情
	 * @author	Jeany
	 * @since	2015-07-23
	 *
	 * @access	public
	 * @param	array		$param  用户名称
	 * @return	array
	 */
	public function getSaleById($id)
	{	
		$r['limit'] = 1;
		$r['eq']['id'] = $id;
        $data = $this->import("sale")->find($r);
        return $data;
	}
	
	/**
	 * 添加出售信息
	 * @author	Jeany
	 * @since	2015-07-23
	 *
	 * @access	public
	 * @param	array		$param  用户名称
	 * @return	array
	 */
	public function addSale($param)
	{

		if($param['type']){
			if($param['type'] == 1 || $param['type'] == 2){
				$param['approveStatus'] = 2 ;
			}
			if($param['type'] == 3){
				$param['approveStatus'] = 4 ;
			}
		}
		$param['date'] = time();
		return $this->import('sale')->add($param);
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
	 * 获取商标名称类型
	 * 
	 * @author	Jeany
	 * @since	2015-11-5
	 * @access	public
	 * @return	void
	 */
	public function getTrademarkType($trademark)
	{
		
		$pregtx = "图形";//图形验证正则
		$pregZW = "/^[\x7f-\xff]+$/"; //中文验证正则
		$pregYW = "/^[a-zA-Z]+$/";//英文验证正则
		$pregSZ = "/^[0-9]+$/";//数字
		
		$pregZWBH = "/[\x7f-\xff]/"; //包含中文
		$pregYWBH = "/[a-zA-Z]+/";//包含英文验证正则
		$pregSZBH = "/[0-9]+/";//包含数字
		
		if(preg_match($pregZW,$trademark)  && !strstr($trademark,$pregtx)){	
			$value = 1;//中文
		}
		
		if(preg_match($pregYW,$trademark)){
			$value = 2;//英文
		}
		
		if($pregtx == $trademark){
			$value = 3;//图形
		}
		
		if(preg_match($pregZWBH,$trademark) && preg_match($pregYWBH,$trademark) && !strstr($trademark,$pregtx) && !preg_match($pregSZBH,$trademark)){
			$value = 4;//中+英
		}
		
		if(preg_match($pregZWBH,$trademark)  && strstr($trademark,$pregtx) && strlen($trademark) != 6 && !preg_match($pregSZBH,$trademark)){
			$value = 5;//中+图
		}
		
		$str = str_replace("图形","",$trademark);
		if(preg_match($pregYWBH,$trademark)  && strstr($trademark,$pregtx) && !preg_match($pregZWBH,$str)){
			$value = 6;//英+图
		}
		
		
		if(preg_match($pregZWBH,$trademark) && preg_match($pregYWBH,$trademark) && strstr($trademark,$pregtx)){
			$value = 7;//中+英+图
		}
		
		
		if(preg_match($pregSZ,$trademark)){
			$value = 8;//数字
		}
		
		return $value;
	}
	
	
	/**
	 * 获取商标名称字数
	 * 
	 * @author	Jeany
	 * @since	2015-11-5
	 * @access	public
	 * @return	void
	 */
	public function getTrademarkLength($trademark)
	{
	
		$pregZWBH = "/[\x{4e00}-\x{9fa5}]+/u"; //包含中文
		$pregSZBH = "/[0-9]/"; //包含数字
		$pregYWBH = "/[a-zA-Z]/u";//包含英文验证 
		//包含中文的，都按照中文计算
		if((preg_match($pregZWBH,$trademark)  && preg_match($pregYWBH,$trademark)) || (preg_match($pregZWBH,$trademark)  && !preg_match($pregYWBH,$trademark))){
			preg_match_all($pregZWBH, $trademark, $match);
			$str = implode(" ",$match[0]);
			$str = str_replace(" ","" ,$str);
			$strlen = strlen($str)/3;
		}
		
		//不包含中文的，按照英文字母计算
		if(!preg_match($pregZWBH,$trademark)  && preg_match($pregYWBH,$trademark)){
			preg_match_all($pregYWBH, $trademark, $matchYW);
			$strlen = count($matchYW[0]);
		}
	
		return $strlen;
	}
	
	
	/**
	 * 获取入驻平台
	 * 
	 * @author	Jeany
	 * @since	2015-11-5
	 * @access	public
	 * @return	void
	 */
	public function getTrademarkPlatform($class)
	{
		$str = '';
		$platform = C("PLATFORM_ITEMS");
		foreach($platform as $key => $val){
			if(in_array($class,$val)){
				$str .= $key."," ;  
			}
		}
		return $str ? substr($str,0,-1) : '';
	}
	
}
?>
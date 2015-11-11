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
					$r['notIn']  = array('id'=>$val);//可出售商标
				}else{
					$r['ft'][$key] = $val;
				}	
			}
		}
		$r['eq']['area']  = 1;//可出售商标
		$r['raw']  = "status not in(2,3,4,6)";
		$r['page']        	= $page;
        $r['limit']         = $num;
		$r['col']           = array('name,class,id,source,number');
        $r['order']         = array('type' => 'asc','date' => 'desc');
        $data = $this->import('sale')->findAll($r);
        foreach($data['rows'] as $k => $item){
			$data['rows'][$k]['imgurl'] = $this->getImg($item['id']); 
			$data['rows'][$k]['url']    = "/trademark/view/?number=".$item['number']."&class=".$item['class']; 
			$data['rows'][$k]['name']   = mbSub($item['name'],0,4); 
            $data['rows'][$k]['source'] = isset( $this->source[$item['source']] ) ? $this->source[$item['source']] : $item['source'];
			$data['rows'][$k]['classes']  = isset( $this->classes[$item['class']] ) ? $this->classes[$item['class']] : $item['class'];
			$data['notId'][] = $item['id'];
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
	public function getDetail($id)
	{	
        $data = $this->import("sale")->get($id);
        if(empty($data)) {return array();}
        $data['classValue']     = isset( $this->classes[$data['class']] ) ? $this->classes[$data['class']] : $data['class'];
		$data['date']      = date('Y-m-d',$data['date']);
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
	public function getTrademarkDetail($id)
	{	
		$r['limit']             = 1;
        $r['eq']['saleId']      = $id;
        $data                   = $this->import("saletrademark")->find($r);
        return $data;
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
	public function getDetailtj($class, $num , $id)
	{
		$r['eq']['class']  = $class;//可出售商标
		$r['eq']['area']  = 1;//可出售商标
		$r['raw']  = "status not in(2,3,4,6) and id <> $id";
        $r['limit']         = $num;
		$r['col']           = array('name,class,id,source,number');
        $r['order']         = array('type' => 'asc','date' => 'desc');
        $data = $this->import('sale')->findAll($r);
        foreach($data['rows'] as $k => $item){
			$data['rows'][$k]['imgurl'] = $this->getImg($item['id']); 
			$data['rows'][$k]['url']    = "/trademark/view/?number=".$item['number']."&class=".$item['class']; 
			$data['rows'][$k]['name']   = mbSub($item['name'],0,4); 
            $data['rows'][$k]['source'] = isset( $this->source[$item['source']] ) ? $this->source[$item['source']] : $item['source'];
			$data['rows'][$k]['classes']  = isset( $this->classes[$item['class']] ) ? $this->classes[$item['class']] : $item['class'];
			$data['notId'][] = $item['id'];
        }
		return $data;
	}

    public function getListTips($data)
    {
        if ( empty($data) ) return array();
        if ( !is_array(current($data)) ){
            $_tmp = array($data);
        }else{
            $_tmp = $data;
        }
        
        foreach ($_tmp as $k => $v) {
            $data[$k] = $this->getTips($v);
        }
        return $data;
    }

    public function getTips($data)
    {
        $data['isOffprice'] = false;
        $data['isBest']     = false;
        $data['isLicence']  = false;

        if ( empty($data['tid']) ) return $data;
        
        $r['limit'] = 100;
        $r['eq'] = array(
            'area'  => 1,
            'tid'   => $v['tid'],
        );
        $r['notIn']   = array('status'=>array(2,3,4,6));
        $res = $this->import('sale')->find($r);
        if ( empty($res) ) return $data;
        
        foreach ($res as $k => $v) {
            if ( !$data['isOffprice'] && $v['salePrice'] > 0 ){
                $data['isOffprice'] = true;
            }
            if ( !$data['isBest'] && strpos($v['label'], '1') !== false ){
                $data['isBest'] = true;
            }
            if ( !$data['isLicence'] && $v['saleType'] == 2 ){
                $data['isLicence'] = true;
            }
        }
        return $data;
    }


}
?>
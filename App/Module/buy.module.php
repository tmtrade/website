<?php 
class BuyModule extends AppModule
{

    public $source = array();
    public $deal   = array();
	public $buytype= array();

    /**
	* 引用业务模型
	*/
	public $models = array(
		'buy'			=> 'tbuy',
        'temp'          => 'ttempbuy',
		'sale'			=> 'tsale',
		'saletrademark' => 'tsaletrademark',
	);

    /**
     * 基础获取求购信息的方法
     *
     * @author  Xuni
     * @since   2015-11-14
     *
     * @access  public
     * @param   array     $r     条件
     *
     * @return  array
     */
    public function find($r)
    {
        return $this->import('buy')->find($r);
    }

    /**
     * 基础获取临时求购信息的方法
     *
     * @author  Xuni
     * @since   2015-11-25
     *
     * @access  public
     * @param   array     $r     条件
     *
     * @return  array
     */
    public function findTemp($r)
    {
        return $this->import('temp')->find($r);
    }
	
    /**
     * 创建数据
     * @author  Xuni
     * @since   2015-11-25
     *
     * @access  public
     * @param   array   $data   数据
     * @return  void
     */
    public function addTempBuy($data)
    {
        return $this->import("temp")->create($data);
    }

    /**
     * 删除数据
     * @author  Xuni
     * @since   2015-12-8
     *
     * @access  public
     * @param   array     $r     条件
     * @return  void
     */
    public function removeTemp($r)
    {
        return $this->import('temp')->remove($r);
    }

	/**
     * 通过saleId获取数据
     * @author	Jeany
     * @since	2015-07-27
     *
     * @access	public
     * @param	int 	$saleId	    出售编号
     * @return	array
     */	 
    public function getDataBySale( $name ,$class ,$userId)
    {
		$r['limit']         = 1;
		$r['eq']['name']  = $name;
		$r['eq']['class'] = $class;
		$r['eq']['loginUserId']  = $userId;
        $data = $this->import("buy")->find($r);
        return $data;
    }
	
	/**
     * 判断求购信息是否存在
	 * 
	 * @author	martin
	 * @since	2015/11/24
	 *
	 * @access	public
     * @param	array	$data	数组
	 * @return	array
	 */
	public function isExist( $data)
	{
		foreach($data as $key => $val){
			$r['eq'][$key] = $val;
		}
        $r['limit']         = 1;
        $data               = $this->import('buy')->find($r);
		return $data;
	}

	/**
     * 通过saleId和联系电话获取数据
     * @author	Jeany
     * @since	2015-11-26
     *
     * @access	public
     * @param	int 	$saleId	    出售编号
     * @return	array
     */	 
    public function getDataByContact( $saleId ,$phone)
    {
		$r['limit']         = 1;
		$r['eq']['saleId']  = $saleId;
		$r['eq']['phone']  = $phone;
        $data = $this->import("buy")->find($r);
        return $data;
    }

    /**
     * 创建数据
     * @author	martin
     * @since	2015-07-23
     *
     * @access	public
     * @param	array	$data	数据
     * @return	void
     */
    public function create($data)
    {
        return $this->import("buy")->create($data);
    }
	
	/**
     * 最新购买需求
     * @author	Jeany
     * @since	2015-11-13
     *
     * @access	public
     * @param	array 	$param	   
     * @return	void
     */	
	public function getNewsBuy($num)
	{
        $r['limit']         = $num;
        $r['order']         = array('dealDate' => 'desc', 'id'=>'desc');
		$r['col']           = array("contact","need","phone");
		$r['in']            = array('status' => array("2","3","6") );
		$r['raw']           = " phone != '' and need != ''";
        $data               = $this->import('buy')->find($r);
        foreach($data as $key => $item){
			$star = "****";
			$data[$key]['phone']  = substr(trim($item['phone']),0,3).$star.substr(trim($item['phone']),7);
			$data[$key]['contact'] = trim($item['contact']) ? substr(trim($item['contact']),0,3)."**" : "佚名" ;
			$data[$key]['shortneed']    = mbSub($item['need'],0,12); 
        }
		return $data;
	}
	
	/**
     * 最新交易记录
     * @author	Jeany
     * @since	2015-11-13
     *
     * @access	public
     * @return	void
     */	
	public function getNewsTrade($num)
	{
		$r['limit']         = $num;
        $r['order']         = array('dealDate' => 'desc', 'id'=>'desc');
		$r['in']            = array('status' => array("2","3") );
		$r['col']           = array("contact","name","class","dealDate");
		$r['raw']           = " class > 0";
        $data               = $this->import('buy')->find($r);
        foreach($data as $key => $item){
			$data[$key]['contact'] = trim($item['contact']) ? substr(trim($item['contact']),0,3)."**" : "佚名" ;
			$data[$key]['name']    = mbSub($item['name'],0,12); 
			$data[$key]['dealDate']    = $this->tradeTime($item['dealDate']);
        }
		return $data;
	}
	
	//交易时间换算
	public function tradeTime($time)
	{
		$nowTime = time();
		$times = ($nowTime-$time)/60;
		if($times <= 10){
			$strtime = "1分钟之前";
		}elseif($times > 10 && $times <= 30){
			$strtime = "10分钟之前";
		}elseif($times > 30 && $times <= 60){
			$strtime = "30分钟之前";
		}elseif($times > 60){
			$strtime = "1小时之前";
		}
		return $strtime;
	}
	
	
	
	
}
?>

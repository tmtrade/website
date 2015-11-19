<?
/**
 * 出售详细信息
 *
 * 获取出售信息，添加出售信息
 * 
 * @package	Module
 * @author	Jeany
 * @since	2015-07-24
 */
class SaletrademarkModule extends AppModule
{
	/**
	 * 引用业务模型
	 */
	public $models = array(
		'saletrademark' => 'Saletrademark',
	);

	/**
	 * 出售详情表基础查询
	 *
	 * @author	Xuni
	 * @since	2015-11-18
	 *
	 * @access	public
	 * @param	array		$r  	条件
	 *
	 * @return	array
	 */
	public function find($r)
	{
		return $this->import('saletrademark')->find($r);
	}

	/**
	 * 添加出售详细信息
	 * @author	Jeany
	 * @since	2015-07-23
	 *
	 * @access	public
	 * @param	array		$param  用户名称
	 * @return	array
	 */
	public function Saletrademark($param)
	{
		return $this->import('saletrademark')->create($param);
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
		$r['limit']             = 1;
        $r['eq']['saleId']      = $id;
        $data                   = $this->import("saletrademark")->find($r);
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
	public function getGroup($id)
	{	
		$r['limit']           = 1;
        $r['eq']['saleId']    = $id;
		$r['col']             = array('group');
        $data                 = $this->import("saletrademark")->find($r);
        return $data['group'];
	}
	
	/**
	 * 编辑出售详细信息
	 * @author	Jeany
	 * @since	2015-07-23
	 *
	 * @access	public
	 * @param	array		$param  用户名称
	 * @return	array
	 */
	public function editSaletrademark($id,$param)
	{
		$r['eq'] = array( "saleId" => $id );
		return $this->import('saletrademark')->modify($param,$r);
	}

	public function getOffpriceImg($saleId)
	{
		$r['eq']['saleId'] 	= $saleId;
		$r['col']			= array('tjpic');
		$res = $this->import('saletrademark')->find($r);
		return empty($res['tjpic']) ? '' : $res['tjpic'];
	}
	
}
?>
<?
/**
 * 工作日志
 *
 * 获取工作日志id分页列表、添加工作日志、编辑工作日志、删除工作日志、删除所有工作日志
 * 
 * @package	Model
 * @author	Jeany
 * @since	2015-07-22
 */
class SaleModel extends AppModel
{
	/**
	 * 获取信息
	 * @author	Jeany
	 * @since	2015-07-22
	 *
	 * @access	public
	 * @rule	array 查询参数
	 * @return	array
	 */
	public function getRows($rule)
	{
		return $this->find($rule);
	}
	
	/**
	 * 添加出售信息
	 * @author	Jeany
	 * @since	2015-07-22
	 *
	 * @access	public
	 * @data	array	提交数据
	 * @return	bool
	 */
	public function add($data)
	{
		if($data['name']){
			$data['name'] = symbol($data['name']); 
		}
		return $this->create($data);
	}


	/**
	 * 获取出售商标的关联人数量
	 * @author	martin
	 * @since	2015-07-28
	 *
	 * @access	public
	 * @param	int		$userId		用户id
	 * @param	int		$saleId		出售商标ID
	 * @return	int
	 */
	public function getSaleCount($saleId)
	{
		$r['eq']	= array("id" => $saleId);
		$r['limit'] = 100;
		$r['in']    = array('status' => array(2,3,4));
		return $this->count($r);

	}
	
	
	/**
	 * 修改资质状态
	 * @author	garrett
	 * @since	2015-07-22
	 *
	 * @access	public
	 * @param	array  $data
	 * @param   int $status 状态信息
	 * @return	bool
	 */
	public function update( $data ,$r )
	{
		return $this->modify($data, $r);
	}
}
?>
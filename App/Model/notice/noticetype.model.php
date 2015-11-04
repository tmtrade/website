<?
/**
 * 公告信息
 * 
 * @package	Model
 * @author	Jeany
 * @since	2015-07-22
 */
class NoticeTypeModel extends AppModel
{
	/**
	 * 获取信息
	 * @author	Jeany
	 * @since	2015-10-19
	 *
	 * @access	public
	 * @rule	array 查询参数
	 * @return	array
	 */
	public function getRows($rule)
	{
		return $this->find($rule);
	}
	
}
?>
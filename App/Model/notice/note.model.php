<?
/**
 * 消息中心
 *
 * 添加信息、获取信息、编辑信息
 * 
 * @package	Model
 * @author	void
 * @since	2015-07-22
 */
class NoteModel extends AppModel
{
	/**
	 * 添加用户信息
	 * @author	garrett
	 * @since	2015-07-22
	 *
	 * @access	public
	 * @param	array	$data	信息数据
	 * @return	bool
	 */
	public function add($data)
	{
		return $this->create($data);
	}
	
}
?>
<?
/**
 * 商品查询组件
 *
 * 商品查询
 * 
 * @package	Module
 * @author	void
 * @since	2016-03-14
 */
class GoodsModule extends AppModule
{

	/**
	 * 商品查询
	 *
	 * @author	void
	 * @since	2016-03-14
	 *
	 * @access	public
	 * @param	string	$groupGoodsCode	群组、商品编码【多个用逗号分隔】
	 * @param	int		$num			每页多少条
	 * @param	int		$page			当前页码
	 *
	 * @return	array
	 */
	public function search($groupGoodsCode, $num, $page)
	{
		return $this->importBi('Goods')->search($groupGoodsCode, $num, $page);
	}
}
?>
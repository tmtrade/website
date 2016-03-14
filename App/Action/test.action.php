<?
/**
 * 测试控制器
 *
 * 商品查询
 *
 * @package	Action
 * @author	void
 * @since	2016-02-03
 */
class TestAction extends AppAction
{
	/**
	 * 商品查询
	 * @author	void
	 * @since	2016-02-03
	 *
	 * @access	public
	 * @return	void
	 */
	public function index()
	{
		$groupGoodsCode = "2801,2803,1403";
		$list           = $this->load('Goods')->search($groupGoodsCode, 20, 1);
		print_r($list);
	}
}
?>
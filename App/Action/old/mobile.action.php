<?
/**
 * 专题信息
 *
 * 专题界面
 *
 * @package	Action
 * @author	Jeany
 * @since	2016-1-26
 */
class MobileAction extends AppAction
{
    /**
     * 全额赔付
     * @author  Jeany
     * @since   2016-1-26
     */
    public function index()
    {
		$title		 = "一只蝉：百万注册商标转让平台网-独家100%签定风险赔付协议";
		//$keywords	 = "一只蝉,商标转让,商标转让网,注册商标转让,转让商标,商标买卖,商标交易,商标交易网";
		//$description = "一只蝉是超凡集团资产交易平台：13年积累约200余万商标转让信息-也是中国独家签订交易损失赔付协议保障风险平台-商标转让-专利转让";
		$this->set('TITLE', $title);
        $this->display();
    }

}
?>
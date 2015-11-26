<?
/**
 * 快速成交
 *
 * 网站首页
 *
 * @package Action
 * @author  Xuni
 * @since   2015-11-05
 */
class FaqAction extends AppAction
{
    public $pageTitle   = '交易常见问题 - 一只蝉';
	
	//公司介绍
	public function protocol()
    {
        $this->display();
    }

	//常见问题
	public function question()
    {
        $this->display();
    }

	//流程
	public function process()
    {
        $this->display();
    }

	//规则
	public function rule()
    {
        $this->display();
    }
}
?>
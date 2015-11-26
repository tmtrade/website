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
	public $seotime = '一只蝉';
	//公司介绍
	public function index()
    {
		$this->redirect("","/faq/protocol/");
    }
	//公司介绍
	public function protocol()
    {
		$pageTitle   = '用户服务协议 - '.$this->seotime;
		$this->set("TITLE",$pageTitle);
        $this->display();
    }

	//常见问题
	public function question()
    {
		$pageTitle   = '常见问题 - '.$this->seotime;
		$this->set("TITLE",$pageTitle);
        $this->display();
    }

	//流程
	public function process()
    {
		$pageTitle   = '操作流程 - '.$this->seotime;
		$this->set("TITLE",$pageTitle);
        $this->display();
    }

	//规则
	public function rule()
    {
		$pageTitle   = '入驻平台规则 - '.$this->seotime;
		$this->set("TITLE",$pageTitle);
        $this->display();
    }
}
?>
<?
/**
 * 专利页面
 *
 * @package Action
 * @author  Martin
 * @since   2015/12/2
 */
class PtAction extends AppAction
{
    public $pageTitle = '';

    //我要买
	public function index()
    {
        $this->pageTitle = '专利购买 - 一只蝉';
        $this->set('title', $this->pageTitle);//页面title
        $this->display();
    }
    
    //我要卖
    public function sell()
    {
        $this->pageTitle = '专利出售 - 一只蝉';
        $this->set('title', $this->pageTitle);//页面title
        $this->display();
    }
}
?>
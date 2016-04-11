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
    

    //我要买
    public function index()
    {
        $this->pageTitle 	= '专利购买,专利转让,买卖专利信息网-- 一只蝉专利转让网';

        $this->pageKey          = '专利购买,专利转让,专利转让信息,买卖专利,专利买卖信息';

        $this->pageDescription  = '一只蝉为你提供购买专利,专利转让信息,买卖专利信息,专利申请等服务，一只蝉是超凡集团资产交易平台。多年专利行业经验，为你提供专业的专利转让买卖服务。';
        $this->set('title', $this->pageTitle);//页面title
        $this->set('keywords', $this->pageKey);//页面keywords
        $this->set('description', $this->pageDescription);//页面description
        $this->setSeo(4);
        $this->display();
    }
    
    //我要卖
    public function sell()
    {
        $this->pageTitle 	= '专利出售,专利转让,卖专利-- 一只蝉专利转让网';

        $this->pageKey          = '专利出售,专利转让,卖专利,专利买卖,买卖专利信息';

        $this->pageDescription  = '卖专利去哪里？一只蝉专利转让网,是超凡集团旗下知产交易平台,多年买卖专利经验。为你提供最专业的买卖专利服务。';
        $this->set('title', $this->pageTitle);//页面title
        $this->set('keywords', $this->pageKey);//页面keywords
        $this->set('description', $this->pageDescription);//页面description
        $this->setSeo(5);
        $this->display();
    }
}
?>
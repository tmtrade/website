<?
/**
 * 专题信息
 *
 * 专题界面
 *
 * @package	Action
 * @author  far
 * @since   2016-3-08
 */
class TopicAction extends AppAction
{
    public $caches      = array('lesson_one','tmall_jd');
    public $cacheId     = 'redisHtml';
    public $expire      = 3600;//1小时
    
    //第一课
    public function lesson_one()
    {
        $this->pageTitle 	= '如何选择商标名称_商标类别选择-商标转让知识学习-一只蝉商标转让平台网';
        $this->pageKey          = '如何选择商标名称,如何挑选商标，商标类别选择,商标注册类别选择,商标注册商 品选择技巧';
        $this->pageDescription  = '如何选择商标名称？如何挑选商标？一只蝉商标转让平台手把手教你如何挑 选价值百万的商标。为你在挑选商标过程中遇到的如：商标注册类别选择,商标注册商标选 择技巧,商标名称选择提供最实用的知识';
        $this->set('title', $this->pageTitle);//页面title
        $this->set('keywords', $this->pageKey);//页面keywords
        $this->set('description', $this->pageDescription);//页面description
        $this->setSeoByTopic("/topic/lesson_one");
        $this->display();
    }
    //第二课
    public function lesson_two()
    {
        $this->pageTitle 	= '商标转让价格_商标值多少钱_一个商标能卖多少钱_商标转让费用_商标转让知识-一 只蝉商标转让平台网';
        $this->pageKey          = '商标转让价格,商标值多少钱,一个商标能卖多少钱,商标价值评估,商标转让费用
,r商标值多少钱';
        $this->pageDescription  = '如何判断一个商标值不值钱?一个商标能卖多少钱？一只蝉商标转让平台网手 把手教你如何判断一个商标值不值钱，一个商标值多少钱？为你解决：商标转让价格,商标 转让费用,r商标值多少钱等等问题。为你解决在商标转让价格的问题。';
        $this->set('title', $this->pageTitle);//页面title
        $this->set('keywords', $this->pageKey);//页面keywords
        $this->set('description', $this->pageDescription);//页面description
        $this->setSeoByTopic("/topic/lesson_two");
        $this->display();
    }
    //天猫与京东
    public function tmall_jd()
    {
        $this->pageTitle 	= '买商标入驻天猫京东,哪些商标可以入驻天猫京东- 一只蝉商标转让网';
        $this->pageKey          = '买商标入驻天猫京东,哪些商标可以入驻天猫京东,入驻天猫京东资质资料';
        $this->pageDescription  = '在一只蝉买商标入驻天猫京东,哪些商标可以入驻天猫京东?入驻天猫京东资质资料需要哪些？一只蝉给你想要的答案,这些商标都是可以入驻天猫京东的。一只蝉商标转让网';
        $this->set('title', $this->pageTitle);//页面title
        $this->set('keywords', $this->pageKey);//页面keywords
        $this->set('description', $this->pageDescription);//页面description
        $this->setSeoByTopic("/topic/tmall_jd");
        $this->display();
    }
    
    //第四月 红动全场
    public function fourth()
    {
        $this->pageTitle 	= '商标转让价格_商标值多少钱_一个商标能卖多少钱_商标转让费用_商标转让知识-一 只蝉商标转让平台网';
        $this->pageKey          = '商标转让价格,商标值多少钱,一个商标能卖多少钱,商标价值评估,商标转让费用,r商标值多少钱';
        $this->pageDescription  = '如何判断一个商标值不值钱?一个商标能卖多少钱？一只蝉商标转让平台网手 把手教你如何判断一个商标值不值钱，一个商标值多少钱？为你解决：商标转让价格,商标 转让费用,r商标值多少钱等等问题。为你解决在商标转让价格的问题。';
        $this->set('title', $this->pageTitle);//页面title
        $this->set('keywords', $this->pageKey);//页面keywords
        $this->set('description', $this->pageDescription);//页面description
        $this->setSeoByTopic("/topic/fourth");
        $this->display();
    }
    
     //买一送一
    public function buyone_getone()
    {
        $this->pageTitle 	= '购买商标送注册商标,注册商标怎么转让,注册商标转让好处|一只蝉商标转让平台网';
        $this->pageDescription  = '活动期间在一只蝉商标转让网购买商标者,就送价值2100元的商标注册。注册商标怎么转让？注册商标有哪些好处？上一只蝉商标转让平台,一只蝉是超凡集团商标交易平台-中国独家签订交易损失赔付协议保障风险平台。提供专业的商标交易,商标买卖等服务';
        $this->set('title', $this->pageTitle);//页面title
        $this->set('description', $this->pageDescription);//页面description
        $this->setSeoByTopic("/topic/buyone_getone");
        $this->display();
    }
}
?>
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
        public $caches      = array('index','yyzz','kxwz','ryzz','rule');
        public $cacheId     = 'redisHtml';
        public $expire      = 3600;//1小时

	public $categoryId	= array(45, 50, 51, 52, 53,54);
	public $category	= array(
						50	=> '商标新闻',
						51	=> '商标法律',
						45	=> '商标转让问答',
						53	=> '商标交易百科',
						52	=> '商标求购信息',
						);
        public $seotime      = "一只蝉商标转让平台网";
    //公司介绍
    public function index()
    {
		$this->redirect("","/faq/protocol/");
    }
    
    //营业执照
	public function yyzz()
    {
		$pageTitle   = '营业执照 - '.$this->seotime;
		$this->set("title",$pageTitle);
                $this->display();
    }
    
     //可信网站
    public function kxwz()
    {
		$pageTitle   = '可信网站 - '.$this->seotime;
		$this->set("title",$pageTitle);
                $this->display();
    }
    
    //平台资质
    public function ryzz()
    {
		$pageTitle   = '平台资质 - '.$this->seotime;
		$this->set("title",$pageTitle);
                $this->set("cat", "ryzz");
                $this->display();
    }
    
    //规则
	public function rule()
    {
		$pageTitle   = '入驻平台规则 - '.$this->seotime;
		$this->set("TITLE",$pageTitle);
        $this->display();
    }

	//得到栏目对应的文章
	public function news()
	{
		$c			= $this->input("c","int");
		$page			= $this->input("page","int");
		$page			= $page == 0 ? 1 : $page;
		$limit			= 15;
		if(!in_array($c,$this->categoryId)){
			$c	= 45;
		}
		$list			= $this->load('faq')->newsList(array('c'=>$c,'page'=>$page,'limit'=>$limit));
                
		$total			= $this->load('faq')->newsList(array('c'=>$c,'limit'=>1000000));
		$pager			= $this->pager(count($total), $limit);
		$pageBar		= empty($list) ? '' : getPageBar($pager);

		$title   = $this->category[$c] . ' - '.$this->seotime;
		$this->set("list", $list);
		$this->set("title", $title);
		$this->set("keywords", $this->keyword);
		$this->set("nav", $c);
                $this->set("category", $this->category[$c]);
		$this->set("description", $this->description);
		$this->set("pageBar", $pageBar);
                $this->display();
	}

	//得到栏目对应的文章
	public function views()
	{
		$id			= $this->input("id","int");
		$c			= $this->input("c","int");
		
		$data		= $this->load('faq')->newsList(array('id'=>$id));
		$this->set("list", $data[0]);
                switch ($id){
                case 987: 
                    $this->pageTitle        = '商标交易常见问题-商标转让时间-商标转让流程-- 一只蝉商标转让网';
                    $this->pageKey          = '商标交易常见问题,商标转让时间,商标转让流程,商标转让需要多长时间,买商标有哪些流程,商标转让需要哪些手续';
                    $this->pageDescription  = '一只蝉商标转让网为你整理了,商标转让常见问题,如商标转让时间,商标转让流程,商标转让需要多长时间,买商标有哪些流程,商标转让需要哪些手续等。一只蝉商标转让网,你身边的知产专家。';
                    break;
                case 988: 
                    $this->pageTitle        = '用户服务协议 - 一只蝉商标转让平台网';
                    $this->pageKey          = '一只蝉用户服务协议,商标转让协议,商标转让资料';
                    $this->pageDescription  = '一只蝉用户服务协议,商标转让协议,商标转让资料。一只蝉是超凡集团资产交易平台：13年积累约200余万商标转让信息-也是中国独家签订交易损失赔付协议保障风险平台';
                    break;
                case 990: 
                    $this->pageTitle        = '商标交易流程-商标转让流程-最安全的购买商标流程 - 一只蝉商标转让平台网';
                    $this->pageKey          = '商标交易流程,商标转让流程,购买商标流程,购买商标有哪些流程';
                    $this->pageDescription  = '商标交易流程有哪些？一只蝉为你提供最详细的商标转让流程,购买商标流程介绍。为你解决你购买商标过程中的各种问题。一只蝉是超凡集团资产交易平台：13年积累约200余万商标转让信息-也是中国独家签订交易损失赔付协议保障风险平台。';
                    break;
                default :
                    if(!empty($this->category[$c])){
                        $this->pageTitle        =  $data[0]['title'].' - '.$this->category[$c] . ' - '.$this->seotime;
                    }else{
                        $this->pageTitle        =  $data[0]['title'] . ' - '.$this->seotime;
                    }
                    if(!empty($data[0]['keyword'])){
                      $this->pageKey          = $data[0]['keyword'];  
                    }
                    if(!empty($data[0]['introduction'])){
                        $this->pageDescription  = $data[0]['introduction'];
                    }
                    break;
                }
                $this->set('title', $this->pageTitle);//页面title
                $this->set('keywords', $this->pageKey);//页面keywords
                $this->set('description', $this->pageDescription);//页面description
                $this->set("id", $id);
                $this->display();
	}

}
?>
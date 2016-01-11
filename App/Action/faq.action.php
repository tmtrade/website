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
	public $seotime		= '一只蝉';
	public $keyword		= '商标转让,商标转让网,注册商标转让,转让商标,商标买卖,商标交易,商标交易网';
	public $description	= '一只蝉是超凡集团资产交易平台：13年积累约200余万商标转让信息-也是中国独家签订交易损失赔付协议保障风险平台-商标转让-专利转让';
	public $categoryId	= array(45, 50, 51, 52, 53);
	public $category	= array(
						50	=> '商标新闻',
						51	=> '商标法律',
						45	=> '商标转让问答',
						53	=> '商标交易百科',
						52	=> '商标求购信息',
						);

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



	//得到栏目对应的文章
	public function news()
	{
		$c			= $this->input("c","int");
		if(!in_array($c,$this->categoryId)){
			$c		= 45;
		}
		$list		= $this->load('faq')->newsList($c,0);


		$title   = $this->category[$c] . ' - '.$this->seotime;
		$this->set("categoryId", $this->categoryId);
		$this->set("categoryList", $this->category);
		$this->set("list", $list);
		$this->set("category", $this->category[$c]);
		$this->set("TITLE", $title);
		$this->set("keyword", $this->keyword);
		$this->set("nav", $c);
		$this->set("description", $this->description);
        $this->display();
	}

	//得到栏目对应的文章
	public function views()
	{
		$id			= $this->input("id","int");
		$c			= $this->input("c","int");
		
		$data		= $this->load('faq')->newsList($c,$id);


		$maxId		= $this->load('faq')->newsList($c, 0, 1, 0, $id);
		$minId		= $this->load('faq')->newsList($c, 0, 1, $id, 0);
		$title   = $list[0]['title'].' - '.$this->category[$c] . ' - '.$this->seotime;
		$this->set("categoryId", $this->categoryId);
		$this->set("categoryList", $this->category);
		$this->set("list", $data[0]);
		$this->set("category", $this->category[$c]);
		$this->set("TITLE", $title);
		$this->set("keyword", $this->keyword);
		$this->set("nav", $c);
		$this->set("maxId", $maxId[0]);
		$this->set("minId", $minId[0]);
        $this->display();
	}

}
?>
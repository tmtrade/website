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
	public $url			= 'http://wm.chofn.net/api/';
	public $categoryId	= array(45, 46, 47, 48, 49);
	public $category	= array(
						45	=> '商标新闻',
						46	=> '商标法律',
						47	=> '商标转让问答',
						48	=> '商标交易百科',
						49	=> '商标求购信息',
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


	//调用对应接口
	public function getList($array,$act)
	{
		foreach($array as $k => $v){
			$len 		= mb_strlen($v['title'],'utf-8');
			$thumtitle	= $len > 20 ? mb_substr($v['title'],0,20,'utf-8').'...' : $v['title'];
			$list[$k]['url'] 				= '/faq/views/?id='.$v['id'].'&act='.$act.'&c='.$v['categoryId'];	
			$list[$k]['thumtitle']		= $thumtitle;	
			$list[$k]['title']			= $v['title'];
			$list[$k]['img']			= $v['attachmentId'];
			$list[$k]['content']		= $v['content'];
			$list[$k]['title']			= $v['title'];
			$list[$k]['keyword']		= $v['keyword'];
			$list[$k]['introduction']	= $v['introduction'];
			$list[$k]['id']				= $v['id'];
			
			
		}
		return $list;
	}


	//得到栏目对应的文章
	public function news()
	{
		$c			= $this->input("c","int");
		if(!in_array($c,$this->categoryId)){
			$c		= 45;
		}
		$list		= array();
		$actionName = 'article';
		$funName 	= 'getArticleList';
		$param		= array(
			'maxId' 		=> 0,
			'minId' 		=> 0,
			'categoryId' 	=> $c,
			'limit' 		=> 20,
			'order' 		=> array('showOrder' => 'DESC'),
		);
		$client 	= new Yar_client($this->url.$actionName);
		$data 		= $client->$funName($param);
		if(!empty($data['rows'])){
			$list = $this->getList($data['rows'],'cfdt');
		}


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
		if(!in_array($c,$this->categoryId)){
			$c		= 45;
		}
		$list		= array();
		$actionName = 'article';
		$funName 	= 'getArticleList';
		$param		= array(
			'maxId' 		=> 0,
			'minId' 		=> 0,
			'categoryId' 	=> $c,
			'id' 			=> $id,
			'limit' 		=> 1,
			'order' 		=> array('showOrder' => 'DESC'),
		);
		$client 	= new Yar_client($this->url.$actionName);
		$data 		= $client->$funName($param);


		//上一条
		$param		= array(
			'maxId' 		=> 0,
			'minId' 		=> 0,
			'categoryId' 	=> $c,
			'maxId' 		=> $id,
			'limit' 		=> 1,
			'order' 		=> array('showOrder' => 'DESC'),
		);
		$maxId 		= $client->$funName($param);

		//上一条
		$param		= array(
			'maxId' 		=> 0,
			'minId' 		=> 0,
			'categoryId' 	=> $c,
			'minId' 		=> $id,
			'limit' 		=> 1,
			'order' 		=> array('showOrder' => 'DESC'),
		);

		$minId 		= $client->$funName($param);

		if(!empty($data['rows'])){
			$list = $this->getList($data['rows'],'cfdt');
		}

		$title   = $list[0]['title'].' - '.$this->category[$c] . ' - '.$this->seotime;
		$this->set("categoryId", $this->categoryId);
		$this->set("categoryList", $this->category);
		$this->set("list", $list[0]);
		$this->set("category", $this->category[$c]);
		$this->set("TITLE", $title);
		$this->set("keyword", $this->keyword);
		$this->set("nav", $c);
		$this->set("maxId", $maxId['rows'][0]);
		$this->set("minId", $minId['rows'][0]);
        $this->display();
	}

}
?>
<?
/**
 * 网站首页
 *
 * 网站首页
 *
 * @package Action
 * @author  dower
 * @since   2016-3-03
 */
class IndexAction extends AppAction
{

	public function index()
	{
		//首页标识
		$this->set('is_index',true);
		//得到首页基本基本配置信息
		list($banners,$ads,$recommendClasses) = $this->load('index')->getIndexBasic();
		$this->set('banners',$banners);
		$this->set('ads',$ads);
		$this->set('recommendClasses',$recommendClasses);
		//得到模块信息
		$modules = $this->load('index')->getModule();
		$this->set('modules',$modules);
		//最新交易记录
		$tradeInfo = $this->load('buy')->getNewsTrade(10);
		//$tradeInfo = $this->load('buy')->getNewsTradeInfo(10);
		$this->set('tradeInfo',$tradeInfo);
		//得到商标分类信息
		$allClass = $this->load('index')->getAllClass();
		$this->set('allClass',$allClass);
		//得到新闻和问答
		//$_news = $this->com('redis')->get('_news_tmp');
		if(empty($_news)){
			$news['news']	= $this->load('faq')->newsList(array('c'=>50,'limit'=>4));
			$news['faq']	= $this->load('faq')->newsList(array('c'=>45,'limit'=>4));
			$news['baike']	= $this->load('faq')->newsList(array('c'=>53,'limit'=>2));
			$news['law']	= $this->load('faq')->newsList(array('c'=>51,'limit'=>2));
			//$this->com('redis')->set('_news_tmp', $news, 3600);
		}else{
			$news = $_news;
		}
		$this->set('news',$news);
		$this->display();
	}

	public function searchLog()
	{
		$title 	= $this->input('title','string','');
		if ( empty($title) ){			
			$prefix = C('SEARCH_HISTORY');
			$log 	= (array)unserialize( Session::get($prefix) );
			$list 	= array_filter( array_map('unserialize', $log) );
			$list 	= array_slice($list, 0, 10);
		}else{
			$_title = $title;
			$list 	= array(
				array(
					'title'	=> "按商标名称“ $_title ”搜索结果",
					'url' 	=> "/search/?kw=$_title&kt=1&n=2",
					),
				array(
					'title'	=> "按商标号“ $_title ”搜索结果",
					'url' 	=> "/search/?kw=$_title&kt=2",
					),
				array(
					'title'	=> "按适用服务“ $_title ”搜索结果",
					'url' 	=> "/search/?kw=$_title&kt=3",
					),
				);
		}
		$this->set('list',$list);
		$this->display();
	}

}
?>
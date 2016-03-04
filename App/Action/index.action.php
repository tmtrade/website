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
		//得到首页基本基本配置信息
		list($banners,$hotwords,$ads,$recommendClasses) = $this->load('index')->getIndexBasic();
		$this->set('banners',$banners);
		$this->set('hotwords',$hotwords);
		$this->set('ads',$ads);
		$this->set('recommendClasses',$recommendClasses);
		//得到通栏行业菜单信息
		list($menuFirst,$menuSecond,$menuThird,$menuPic) = $this->load('index')->getIndustry();
		$this->set('menuFirst',$menuFirst);
		$this->set('menuSecond',$menuSecond);
		$this->set('menuThird',$menuThird);
		$this->set('menuPic',$menuPic);
		//得到模块信息
		$modules = $this->load('index')->getModule();
		$this->set('modules',$modules);
		//最新交易记录
		$tradeInfo = $this->load('buy')->getNewsTrade(10);
		$this->set('tradeInfo',$tradeInfo);
		//得到商标分类信息
		$allClass = $this->load('index')->getAllClass();
		$this->set('allClass',$allClass);
		$this->display();
	}
}
?>
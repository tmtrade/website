<?
/**
 * 应用公用控制器
 *
 * 存放应用的公共方法
 *
 * @package	Action
 * @author	void
 * @since	2015-01-28
 */
abstract class AppAction extends Action
{
	/**
	 * 每页显示多少行
	 */
	public $rowNum  	= 10;
	
	public $username 	= '';
	
	public $userId   	= '';
	
	public $isLogin  	= false;

	public $pageTitle 	= '商标转让-百万注册商标转让交易买卖平台- 一只蝉商标转让平台网';
	
	public $pageKey 	= '商标转让,一只蝉,商标转让网,注册商标转让,转让商标,商标买卖,商标交易,商标交易网';
	
	public $pageDescription = '商标转让上一只蝉商标转让平台网,一只蝉是超凡集团商标交易平台：13年积累约200余万商标转让信息-也是中国独家签订交易损失赔付协议保障风险平台。提供专业的商标交易,商标买卖等服务';
        
        public $seotime = "一只蝉商标转让平台网";
        /**
	 * 前置操作(框架自动调用)
	 * @author	void
	 * @since	2015-01-28
	 *
	 * @access	public
	 * @return	void
	 */
	public function before()
	{
                $this->caches = ""; //打开后关闭所有 页面缓存
		//设置访问的action(导航样式)
		$nav_name = $this->getNavType();
		$this->set('nav_name',$nav_name);
		//设置用户信息
		$this->setLoginUser();

		//获得热搜数据
		$hotwords = $this->load('index')->getHotWords();
		$this->set('hotwords',$hotwords);
		//得到通栏行业菜单信息
		list($menuFirst,$menuSecond,$menuThird,$menuPic) = $this->load('index')->getIndustry();
		$this->set('menuFirst',$menuFirst);
		$this->set('menuSecond',$menuSecond);
		$this->set('menuThird',$menuThird);
		$this->set('menuPic',$menuPic);
		//获得友情链接数据
                $frendlyLink = $this->com('redisHtml')->get('footer_link');
		if(empty($frendlyLink)){
			$frendlyLink = $this->load('faq')->newsList(array('c'=>47,'limit'=>20));
			$this->com('redisHtml')->set('footer_link', $frendlyLink, 3600);
		}
		$this->set('frendlyLink', $frendlyLink);

		$this->set('_mod_', $this->mod);
		$this->set('_action_', $this->action);
                
                $this->set('title', $this->pageTitle);//页面title
                $this->set('keywords', $this->pageKey);//页面keywords
                $this->set('description', $this->pageDescription);//页面description
		
		$this->set('static_version', 9160);//静态文件版本号>>控制js,css缓存

		// $this->set('CLASSES', C('CLASSES'));//国际分类
		// $this->set('CATEGORY', C('CATEGORY'));//分类
		// $this->set('CATEGORY_ITEMS', C('CATEGORY_ITEMS'));//分类群组
		// $this->set('PLATFORM', C('PLATFORM_IN'));//平台列表
		// $this->set('NUMBER', C('SBNUMBER'));//商标字数
		// $this->set('TYPES', C('TYPES'));//商标类型
	}

	/**
	 * 后置操作(框架自动调用)
	 * @author	void
	 * @since	2015-01-28
	 *
	 * @access	public
	 * @return	void
	 */
	public function after()
	{
		//自定义业务逻辑
	}

	/**
	 * 输出json数据
	 *
	 * @author	Xuni
	 * @since	2015-11-06
	 *
	 * @access	public
	 * @return	void
	 */
	protected function returnAjax($data=array())
	{
		$jsonStr = json_encode($data);
		exit($jsonStr);
	}

	/**
	 * 设置用户信息数据
	 *
	 * @author	Xuni
	 * @since	2015-11-06
	 *
	 * @access	public
	 * @return	void
	 */
	protected final function setLoginUser()
	{
		if ( empty($_COOKIE['uc_ukey']) ){
			$this->removeUser();
			return false;
		}else{
			$this->loginId 		= $_COOKIE['uc_ukey'];
			$this->nickname 	= $_COOKIE['uc_nickname'];
			$this->userMobile 	= $_COOKIE['uc_mobile'];
			$this->isLogin 		= true;
		}

		//设置用户信息到页面
		$this->setUserView();
	}

	/**
	 * 设置用户信息数据到页面
	 *
	 * @author	Xuni
	 * @since	2015-11-06
	 *
	 * @access	public
	 * @return	void
	 */
	protected final function setUserView()
	{
		$this->set('nickname', $this->nickname);
		$this->set('loginId', $this->loginId);
		$this->set('userMobile', $this->userMobile);
		$this->set('isLogin', $this->isLogin);
	}
	
        /**
	 * 设置页面SEO
	 *
	 * @author	Far
	 * @since	2016-4-07
	 */
	protected final function setSeo($type,$vid="")
	{
            $seoList = $this->com('redisHtml')->get("seo_".$type.$vid);
            if(empty($seoList)){
                    $seoList = $this->load('seo')->getInfo($type,$vid);
                    if(!empty($seoList)){
                        $this->com('redisHtml')->set("seo_".$type.$vid, $seoList, 300);
                    }
            }
            if(!empty($seoList['title'])){
                $this->set('title', $seoList['title']);//后台调用页面title
            }
            if(!empty($seoList['keyword'])){
                $this->set('keywords', $seoList['keyword']);//后台调用页面keywords
            }
            if(!empty($seoList['description'])){
                $this->set('description', $seoList['description']);//后台调用页面description
            }
	}
        
	/**
	 * 删除用户信息数据
	 *
	 * @author	Xuni
	 * @since	2015-11-06
	 *
	 * @access	public
	 * @return	void
	 */
	protected final function removeUser()
	{
		$this->loginId 		= '';
		$this->nickname 	= '';
		$this->userMobile 	= '';
		$this->isLogin 		= false;

		//设置用户信息到页面
		$this->setUserView();
	}

	/**
	 * 获得用户访问的url,设置状态值(用于头部导航显示)
	 *
	 * @author	dower
	 * @since	2016-3-24
	 *
	 * @access	public
	 * @return	void
	 */
	protected function getNavType(){
		$url = $_SERVER['REQUEST_URI'];
		$tmp = '';
		if($url=='/'){
			$tmp = 'index';
		}elseif(strpos($url,'search')){
			$tmp = 'search';
		}elseif(strpos($url,'offprice')){
			$tmp = 'offprice';
		}elseif(strpos($url,'offprice')){
			$tmp = 'offprice';
		}elseif(strpos($url,'pt/sell')){
			$tmp = 'sell';
		}elseif(strpos($url,'pt')){
			$tmp = 'pt';
		}
		return $tmp;
	}
}
?>
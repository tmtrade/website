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

	public $pageTitle 	= '一只蝉：百万注册商标转让平台网-独家100%签定风险赔付协议';
	
	public $pageKey 	= '一只蝉,商标转让,商标转让网,注册商标转让,转让商标,商标买卖,商标交易,商标交易网';
	
	public $pageDescription 	= '一只蝉是超凡集团资产交易平台：13年积累约200余万商标转让信息-也是中国独家签订交易损失赔付协议保障风险平台-商标转让-专利转让';

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
		/*//设置用户信息
		$this->setLoginUser();*/
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
		$frendlyLink = $this->load('faq')->newsList(array('c'=>47,'limit'=>20));
		$this->set('frendlyLink', $frendlyLink);

		$this->set('_mod_', $this->mod);
		$this->set('_action_', $this->action);

		$this->set('TITLE', $this->pageTitle);//页面title
		$this->set('keywords', $this->pageKey);//页面keywords
		$this->set('description', $this->pageDescription);//页面description
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
		$uName = C('PUBLIC_USER');
        $uTime = C('PUBLIC_USER_TIME');
		$user = @unserialize( Session::get($uName) );
		if ( empty($user) || !is_array($user) ){
			$this->removeUser();
			return false;
		}
		if ( empty($user['userId']) ){
			$this->removeUser();
			return false;
		}

		$info  = serialize($user);
        Session::set($uName, $info, $uTime);

		$this->userId 		= $user['userId'];
		$this->username 	= $user['username'];
		$this->nickname 	= $user['nickname'];
		$this->userMobile 	= $user['mobile'];
		$this->userEmail 	= $user['email'];
		$this->userInfo 	= $user;
		$this->isLogin 		= true;

		$name = empty($this->nickname) ? (empty($this->userMobile) ? $this->userEmail : $this->userMobile) : $this->nickname;	
		$this->nickname 	= $name;
		
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
		$name = empty($this->nickname) ? (empty($this->userMobile) ? $this->userEmail : $this->userMobile) : $this->nickname;
		$this->set('nickname', $name);
		$this->set('username', $this->username);
		$this->set('userMobile', $this->userMobile);
		$this->set('userEmail', $this->userEmail);
		$this->set('userInfo', $this->userInfo);
		$this->set('isLogin', $this->isLogin);
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
		$this->userId 		= '';
		$this->username 	= '';
		$this->nickname 	= '';
		$this->userMobile 	= '';
		$this->userEmail 	= '';
		$this->userInfo 	= '';
		$this->isLogin 		= false;

		//设置用户信息到页面
		$this->setUserView();
	}
}
?>
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
	public $rowNum  = 10;
	
	public $username = '';
	
	public $userId   = '';
	
	public $isLogin  = false;

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
		//设置用户信息
		$this->setLoginUser();

		$this->set('_mod_', $this->mod);
		$this->set('_action_', $this->action);

		$this->set('CLASSES', C('CLASSES'));//国际分类
		$this->set('CATEGORY', C('CATEGORY'));//分类
		$this->set('CATEGORY_ITEMS', C('CATEGORY_ITEMS'));//分类群组
		$this->set('PLATFORM', C('PLATFORM_IN'));//平台列表
		$this->set('NUMBER', C('SBNUMBER'));//商标字数
		$this->set('TYPES', C('TYPES'));//商标类型
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
		$this->userMobile 	= $user['mobile'];
		$this->userEmail 	= $user['email'];
		$this->userInfo 	= $user;
		$this->isLogin 		= true;
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
		$name = empty($this->username) ? (empty($this->userMobile) ? $this->userEmail : $this->userMobile) : $this->username;
		$this->set('username', $name);
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
		$this->userMobile 	= '';
		$this->userEmail 	= '';
		$this->userInfo 	= '';
		$this->isLogin 		= false;
	}
}
?>
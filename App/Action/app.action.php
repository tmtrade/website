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
		$this->setLoginUser();
		$name = empty($this->username) ? ( empty($this->userInfo['mobile']) ? 
			$this->userInfo['email'] : $this->userInfo['mobile']) : $this->username;
		$this->set('username', $name);
		$this->set('userInfo', $this->userInfo);
		$this->set('isLogin', $this->isLogin);
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
	
	protected function returnAjax($data=array())
	{
		$jsonStr = json_encode($data);
		exit($jsonStr);
	}

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
		$this->userInfo 	= $user;
		$this->isLogin 		= true;
	}
	
	protected final function removeUser()
	{
		$this->userId 		= '';
		$this->username 	= '';
		$this->userInfo 	= '';
		$this->isLogin 		= false;
	}
}
?>
<?
/**
 * 账号验证
 *
 * 账号登录、验证
 *
 * @package	Action
 * @author	Xuni
 * @since	2015-11-05
 */
class PassportAction extends AppAction
{
    private $codeTime   = 300;//手机验证码有效时间(秒)
    private $mvName     = 'mvCode'; //手机验证码名称
    private $mbNo       = 'mbNo'; //验证时的手机号

    /**
     * 正常登录
     *
     * 通过校手机或邮箱进行登录操作
     * 
     * @author  Xuni
     * @since   2015-11-06
     *
     * @access  public
     *
     * @return  array
     */
    public function login()
    {
        $account    = $this->input('uname', 'string', '');
        $password   = $this->input('upass', 'string', '');

        if ( empty($account) || empty($password) ){
            $this->returnAjax(array('code'=>2));//账号或密码为空
        }
        $cateId = isCheck($account);//判断账号是什么类型（1：邮箱，2：手机，3：其他）
        if ( $cateId == 3 ){
            $this->returnAjax(array('code'=>3));//账号不正确
        }
        $res = $this->load('passport')->login($account, $password, $cateId);
        if ( empty($res) || !isset($res['code']) ){
            $this->returnAjax(array('code'=>0));//登录失败
        }

        //正确,登录成功
        if ( $res['code'] == 1 ){
            //设置登录信息
            $this->setLogin($res['data']);
            $flag = array('code'=>1);
        }else if( $res['code'] == 0 ){
            $flag = array('code'=>4);//密码错误
        }else{
            $flag = array('code'=>5);//该账号不存在
        }
        $this->returnAjax($flag);
    }

    /**
     * 手机快捷登录
     *
     * 通过校验手机验证码进行登录操作
     * 
     * @author  Xuni
     * @since   2015-11-06
     *
     * @access  public
     *
     * @return  json
     */
    public function fastLogin()
    {
        $account    = $this->input('uname', 'string', '');
        $password   = $this->input('upass', 'string', '');

        if ( empty($account) || empty($password) ){
            $this->returnAjax(array('code'=>2));//账号或密码为空
        }
        if ( isCheck($account) != 2 ){
            $this->returnAjax(array('code'=>3));//手机号不正确
        }
        $userinfo = $this->load('passport')->get($account);
        if ( empty($userinfo) ) $this->returnAjax(array('code'=>0));//账号不正确或获取数据失败

        //判断验证码是否正确
        $this->checkMsgCode($account, $password, false);
        $this->setLogin($userinfo);
        $this->returnAjax(array('code'=>1));//登录成功
    }

    /**
     * 检验账号是否存在
     * 
     * @author  Xuni
     * @since   2015-11-13
     *
     * @access  public
     *
     * @return  json
     */
    public function existAccount()
    {
        $account = $this->input('account', 'string', '');
        if ( empty($account) ){
            $this->returnAjax(array('code'=>2));//账号为空
        }
        if ( isCheck($account) == 3 ){
            $this->returnAjax(array('code'=>3));//账号格式不正确
        }
        $userinfo = $this->load('passport')->exist($account, isCheck($account));
        if ( empty($userinfo) ) $this->returnAjax(array('code'=>0));//账号不正确或获取数据失败

        $this->returnAjax(array('code'=>1));//账号正确
    }

    /**
     * 发送手机验证码
     * 
     * @author  Xuni
     * @since   2015-11-06
     *
     * @access  public
     *
     * @return  json
     */
    public function sendMsgCode()
    {
        $mobile     = $this->input('m', 'string', '');
        $isRegister = $this->input('r', 'string', 'y');
        if ( empty($mobile) || isCheck($mobile) != 2 ){
            $this->returnAjax(array('code'=>2));//手机号不正确
        }
        if ( $isRegister == 'y' ){
            $userinfo = $this->load('passport')->get($mobile);
            if ( empty($userinfo) ) $this->returnAjax(array('code'=>3));//该手机号未注册
        }
        //设置验证码
        $res = $this->setMsgCode($mobile);
        if ( isset($res['code']) ){
            if ( $res['code'] == 1 ){
                $flag = array('code'=>1);//正确
            }else if( $res['code'] == 2 ){
                $flag = array('code'=>2);//手机号不正确
            }else{
                $flag = array('code'=>0);//发送失败
            }
        }else{
            $flag = array('code'=>0);//发送失败
        }
        $this->returnAjax($flag);
    }

    /**
     * 获取验证码，并发送到手机
     * 
     * @author  Xuni
     * @since   2015-11-06
     *
     * @access  public
     * @param   int         $mobile      手机号码
     * @param   string      $length      验证码
     * @param   bool        $length      是否通过Ajax访问
     *
     * @return  array
     */
    public function checkMsgCode($mobile='', $code='', $ajax=true)
    {
        if ( $ajax || empty($mobile) || empty($code) ){
            $mobile = $this->input('m', 'string', '');
            $code   = $this->input('c', 'string', '');
        }
        
        $prefix = C('COOKIE_PREFIX');
        $cname   = $prefix.$this->mvName;
        if ( $code != Session::get($cname) ){
            $this->returnAjax(array('code'=>5));//验证码不正确
        }
        $mbNo   = $prefix.$this->mbNo;
        if ( $mobile != Session::get($mbNo) ){
            $this->returnAjax(array('code'=>4));//手机号不是验证时的手机号
        }
        Session::remove($mbNo);//登录成功清除临时值
        Session::remove($cname);//登录成功清除临时值

        if ( $ajax ){
            $userinfo = $this->load('passport')->get($mobile);
            if ( empty($userinfo) ) $this->returnAjax(array('code'=>11));//验证码正确(手机已注册)
            $this->returnAjax(array('code'=>1));//验证码正确(但手机未注册)
        }
    }

    /**
     * 获取验证码，并发送到手机
     * 
     * @author  Xuni
     * @since   2015-11-06
     *
     * @access  public
     * @param   int     $mobile      手机号码
     * @param   int     $length      验证码长度
     *
     * @return  array
     */
    private function setMsgCode($mobile, $length=6)
    {
        $code   = randCode($length);
        $prefix = C('COOKIE_PREFIX');
        $cname  = $prefix.$this->mvName;
        $mbNo   = $prefix.$this->mbNo;
        Session::set($cname, $code, $this->codeTime);
        Session::set($mbNo, $mobile, $this->codeTime);
        $content = Session::get($cname);
        return $this->load('outmsg')->sendMsg($mobile, $content);
    }

    /**
     * 登出
     * 
     * @author  Xuni
     * @since   2015-11-06
     *
     * @access  public
     *
     * @return  null
     */
    public function loginout()
    {
        $this->loginClear();
        $t = $this->input('t', 'string', '');
        if ( $t ){
            $url = base64_decode($t);
            header("Location: $url"); exit;
        }
        $this->redirect('', '/index/');
    }

    /**
     * 清除用户登录信息
     * 
     * @author  Xuni
     * @since   2015-11-06
     *
     * @access  public
     *
     * @return  null
     */
    private function loginClear()
    {
        $uName = C('PUBLIC_USER');
        Session::set($uName, '');
        Session::clear();
    }

    /**
     * 设置用户登录信息
     * 
     * @author  Xuni
     * @since   2015-11-06
     *
     * @access  public
     * @param   array  $data      用户信息
     *
     * @return  null
     */
    private function setLogin($data)
    {
        $user = array(
            'userId'    => $data['id'],
            'username'  => $data['username'],
            'email'     => $data['email'],
            'mobile'    => $data['mobile'],
            'isEmail'   => $data['isEmail'],
            'isMobile'  => $data['isMobile'],
            );

        $uName = C('PUBLIC_USER');
        $uTime = C('PUBLIC_USER_TIME');
        $info  = serialize($user);
        Session::set($uName, $info, $uTime);
    }
}
?>
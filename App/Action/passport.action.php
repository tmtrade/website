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
    private $codeTime   = 600;//手机验证码有效时间(秒)
    private $mvName     = 'mvCode'; //手机验证码名称
    private $mbNo       = 'mbNo'; //验证时的手机号

    public function index()
    {
        $this->redirect('', '/');
    }

    /**
     * 登录
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
        if ( !$this->isAjax() ) $this->redirect('', '/');

        $account    = $this->input('uname', 'string', '');
        $password   = $this->input('upass', 'string', '');
        $sid        = $this->input('sid', 'string', '');

        if ( empty($account) || empty($password) ){
            $this->returnAjax(array('code'=>2));//账号或密码为空
        }
        $cateId = isCheck($account);//判断账号是什么类型（1：邮箱，2：手机，3：其他）
        if ( $cateId != 2 ){
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
            //判断验证码是否正确
            $this->checkMsgCode($account, $password, false, false);
            $userinfo = $this->load('passport')->get($account);
            if ( !empty($userinfo) ){
                $this->setLogin($userinfo);
                $this->unsetMsgCode();
                $flag = array('code'=>1);
            }else{
                $flag = array('code'=>0);
            }
        }else{
            $flag = array('code'=>6);//该账号不存在
            //判断是否在临时数据库中存在
            $user = $this->load('temp')->isExist($account);
            if ( empty($user) ) $this->returnAjax($flag);//登录失败
            
            if ($user['password'] != $password){
                //如果临时密码也不通过，会直接返回错误
                $this->checkMsgCode($account, $password, false, false);
            }
            $userId = $this->load('passport')->register($account, $user['password'], 2);//注册账号
            if ( !$userId ) $this->returnAjax(array('code'=>0));//登录失败s
            //处理临时求购信息
            $this->load('temp')->moveTempToReal($userId, $account, $sid);

            $userinfo = $this->load('passport')->get($account);
            if ( !empty($userinfo) ){
                $this->setLogin($userinfo);
                $this->unsetMsgCode();
                $flag = array('code'=>1);
            }else{
                $flag = array('code'=>0);
            }
        }
        $this->returnAjax($flag);
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
    public function sendMsgCodeNew()
    {
        if ( !$this->isAjax() ) $this->redirect('', '/');

        $mobile     = $this->input('m', 'string', '');
        if ( empty($mobile) || isCheck($mobile) != 2 ){
            $this->returnAjax(array('code'=>2));//手机号不正确
        }
        
        $userinfo = $this->load('passport')->get($mobile);
        if ( empty($userinfo) ) {
            $user = $this->load('temp')->isExist($mobile);
            if ( empty($user) ){
                $this->returnAjax(array('code'=>3));//该手机号未注册
            }
        }

        //设置验证码
        $res = $this->setMsgCode($mobile, 'newValid');
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
        if ( !$this->isAjax() ) $this->redirect('', '/');

        $account = $this->input('account', 'string', '');
        if ( empty($account) ){
            $this->returnAjax(array('code'=>2));//账号为空
        }
        if ( isCheck($account) != 2 ){
            $this->returnAjax(array('code'=>3));//账号格式不正确
        }
        $res = $this->load('passport')->exist($account, isCheck($account));
        if ( $res === false ) {
            $this->returnAjax(array('code'=>0));//获取数据失败
        }elseif ( $res['code'] == 1 ){
            $this->returnAjax(array('code'=>1));//账号正确
        }else{
            //是邮箱且未注册的
            //if ( isCheck($account) == 1 ){
            //    $this->returnAjax(array('code'=>3));
            //}
        }
        //判断是否在临时数据库中存在
        $user = $this->load('temp')->isExist($account);
        if ( $user ) $this->returnAjax(array('code'=>1));//账号正确

        $this->returnAjax(array('code'=>-1));//账号不存在
    }

    /**
     * 绑定手机号
     * 
     * @author  Xuni
     * @since   2015-11-06
     *
     * @access  public
     *
     * @return  json
     */
    public function bindMobile()
    {
        if ( !$this->isAjax() ) $this->redirect('', '/');

        if ( $this->userMobile ) $this->returnAjax(array('code'=>1));//有手机号不做操作

        if ( !$this->isLogin ) $this->returnAjax(array('code'=>0));//没登录直接返回失败

        $mobile = $this->input('mobile', 'string', '');
        $code   = $this->input('code', 'string', '');

        if ( empty($mobile) ){
            $this->returnAjax(array('code'=>2));//账号为空
        }
        if ( isCheck($mobile) != 2 ){
            $this->returnAjax(array('code'=>3));//账号格式不正确
        }
        if ( empty($code) ){
            $this->returnAjax(array('code'=>4));//账号为空
        }
        //判断验证码是否正确
        $this->checkMsgCode($mobile, $code, false, false);

        $userinfo = $this->load('passport')->get($mobile);
        if ( empty($userinfo) ) {
            $user = $this->load('temp')->isExist($mobile);
            if ( !empty($user) ){
                $this->returnAjax(array('code'=>6));//该手机号已注册
            }
        }else{
            $this->returnAjax(array('code'=>6));//该手机号已注册
        }

        $res = $this->load('passport')->changeMoblie($this->userId, $mobile);
        if ( $res ) {
            $data = $this->userInfo;
            $data['id']         = $data['userId'];
            $data['mobile']     = $mobile;
            $data['isMobile']   = 1;
            $this->load('passport')->setLogin($data);
            $this->returnAjax(array('code'=>1));//正确
        }
        $this->returnAjax(array('code'=>0));//失败
    }

     /**
     * 发送绑定手机验证码
     * 
     * @author  Xuni
     * @since   2015-11-06
     *
     * @access  public
     *
     * @return  json
     */
    public function sendBindCode()
    {
        if ( !$this->isAjax() ) $this->redirect('', '/');

        $mobile     = $this->input('m', 'string', '');
        if ( empty($mobile) || isCheck($mobile) != 2 ){
            $this->returnAjax(array('code'=>2));//手机号不正确
        }

        $userinfo = $this->load('passport')->get($mobile);
        if ( empty($userinfo) ) {
            $user = $this->load('temp')->isExist($mobile);
            if ( !empty($user) ){
                $this->returnAjax(array('code'=>3));//该手机号已注册
            }
        }else{
            $this->returnAjax(array('code'=>3));//该手机号已注册
        }
        //设置验证码
        $res = $this->setMsgCode($mobile, 'validBind');
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
        if ( !$this->isAjax() ) $this->redirect('', '/');

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
     * 发送手机密码
     * 
     * @author  Xuni
     * @since   2015-12-08
     *
     * @access  public
     *
     * @return  json
     */
    public function sendRegCode()
    {
        if ( !$this->isAjax() ) $this->redirect('', '/');

        $mobile = $this->input('m', 'string', '');
        $sid    = $this->input('s', 'string', '');

        if ( empty($mobile) || isCheck($mobile) != 2 ){
            $this->returnAjax(array('code'=>2));//手机号不正确
        }

        $userinfo = $this->load('passport')->get($mobile);
        if ( empty($userinfo) ) {
            $user = $this->load('temp')->isExist($mobile);
            if ( !empty($user) ){
                $this->returnAjax(array('code'=>3));//该手机号已注册
            }
        }else{
            $this->returnAjax(array('code'=>3));//该手机号已注册
        }

        //设置验证码
        $res = $this->load('passport')->RegTempUser($mobile, $sid);
        if (isset($res['code']) && $res['code'] == 1){
            $flag = array('code'=>1);//正确
        }elseif (isset($res['code']) && $res['code'] == 2) {
            $flag = array('code'=>2);//发送失败
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
    public function checkMsgCode($mobile='', $code='', $ajax=true, $isUnset=true)
    {
        if ( $ajax || empty($mobile) || empty($code) ){
            $mobile = $this->input('m', 'string', '');
            $code   = $this->input('c', 'string', '');
            $unset  = $this->input('r', 'string', 'y');//是否清除reset
            if ( $unset == 'n'){
                $isUnset = false;//不清除
            }
        }
        
        $prefix = C('COOKIE_PREFIX');
        $cname   = $prefix.$this->mvName;
        if ( $code != Session::get($cname) ){
            echo "<$code>"."<".Session::get($cname).'>';
            $this->returnAjax(array('code'=>4));//验证码不正确
        }
        $mbNo   = $prefix.$this->mbNo;
        if ( $mobile != Session::get($mbNo) ){
            $this->returnAjax(array('code'=>5));//手机号不是验证时的手机号
        }
        if ( $isUnset ){
            $this->unsetMsgCode();
        }

        if ( $ajax ){
            $userinfo = $this->load('passport')->get($mobile);
            if ( empty($userinfo) ) $this->returnAjax(array('code'=>11));//验证码正确(手机已注册)
            $this->returnAjax(array('code'=>1));//验证码正确(但手机未注册)
        }
    }

    /**
     * 清除验证码记录
     * 
     * @author  Xuni
     * @since   2015-11-06
     *
     * @access  public
     * @return  null
     */
    private function unsetMsgCode()
    {
        $this->load('passport')->unsetMsgCode();
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
    private function setMsgCode($mobile, $fix='valid', $length=4, $type='NUMBER')
    {
        $code   = randCode($length, $type);
        $prefix = C('COOKIE_PREFIX');
        $cname  = $prefix.$this->mvName;
        $mbNo   = $prefix.$this->mbNo;
        Session::set($cname, $code, $this->codeTime);
        Session::set($mbNo, $mobile, $this->codeTime);
        $msgTemp = C('MSG_TEMPLATE');
        $content = Session::get($cname);
        $msg     = sprintf($msgTemp[$fix], $content);
        return $this->load('outmsg')->sendMsg($mobile, $msg);
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
        $this->load('passport')->setLogin($data);
    }
}
?>
<?
/**
 * 买商标，求购
 *
 * 求购商标信息添加
 *
 * @package	Action
 * @author	Xuni
 * @since	2015-11-13
 */
class BuyAction extends AppAction
{
    private $_passStr = "你的账号%s已生成，密码为%s。第一次登录请修改密码，如您忘记你的密码，请联系超凡网客服人员。";

    public function index()
    {
        $this->display();
    }

    /**
     * 添加求购信息
     *
     * @author  Xuni
     * @since   2015-11-14
     *
     * @access  public
     *
     * @return  json
     */
    public function add()
    {
        $mobile     = $this->input('mobile', 'string', '');
        $content    = $this->input('content', 'string', '');
        $name       = $this->input('name', 'string', '');

        if ( isCheck($mobile) != 2 || $content == ''){
            $this->returnAjax(array('code'=>2));//信息不正确
        }
        $buy = array(
            'source'    => 4,
            'phone'     => $mobile,
            'need'      => $content,
            'contact'   => $name,
            'date'      => time(),
            );
        if ( $this->userId ){
            $buy['loginUserId'] = $this->userId;
        }else{
            $userinfo = $this->load('passport')->get($mobile);
            //没有账号自动创建该手机账号
            if ( empty($userinfo) ){
                $userId = $this->register($mobile);//注册并发密码短信
                if ( !$userId ) $this->returnAjax(array('code'=>3)); //未成功
                $buy['loginUserId'] = $userId;
            }else{
                $buy['loginUserId'] = $userinfo['id'];
            }
        }
        $res = $this->load('buy')->create($buy);
        if ( $res ) $this->returnAjax(array('code'=>1));
        $this->returnAjax(array('code'=>4)); //未生成求购成功
    }

    /**
     * 注册手机账号
     *
     * 通过手机号进行注册并发送随机的8位密码
     * 
     * @author  Xuni
     * @since   2015-11-14
     *
     * @return  bool
     */
    private function register($mobile)
    {
        $pass   = randCode(8);//生成8位随机密码
        $userId = $this->load('passport')->register($mobile, $pass, isCheck($mobile));
        if ( !$userId ) return false;

        $msg = sprintf($this->_passStr, $mobile, $pass);
        $res = $this->load('outmsg')->sendMsg($mobile, $msg);
        if (isset($res['code']) && $res['code'] == 1){
            return $userId;
        }
        return false;
    }

}
?>
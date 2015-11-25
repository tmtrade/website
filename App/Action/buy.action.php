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
        $role = array(
            'eq' => array(
                'source'        => 4,
                'phone'         => $mobile,
                'need'          => $content,
                //'contact'       => $name,
                'loginUserId'   => $buy['loginUserId'],
                ),
            );
        if ( $this->load('buy')->find($role) ){
            $this->returnAjax(array('code'=>1));
        }
        $res = $this->load('buy')->create($buy);
        if ( $res ) {

            $this->returnAjax(array('code'=>1));//成功
        }
        $this->returnAjax(array('code'=>4)); //未成功
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
        return $this->load('passport')->autoRegMoblie($mobile);
    }

}
?>
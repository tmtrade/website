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
    public $pageTitle   = '我要买 - 一只蝉';

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
        if ( !$this->isAjax() ) $this->redirect('', '/');

        $mobile     = $this->input('mobile', 'string', '');
        $content    = $this->input('content', 'string', '');
        $name       = $this->input('name', 'string', '');
        $sid        = $this->input('sid', 'string', '');
        $area       = $this->input('area', 'string', '');

        if ( isCheck($mobile) != 2 || $content == ''){
            $this->returnAjax(array('code'=>2));//信息不正确
        }
        $buy = array(
            'source'    => 10,
            'phone'     => $mobile,
            'need'      => $content,
            //'sid'       => $sid,
            //'area'      => $area,
            'contact'   => $name,
            'date'      => time(),
            );
        if ( $this->userId ){
            $buy['loginUserId'] = $this->userId;
        }else{
            $userinfo = $this->load('passport')->get($mobile);
            if ( empty($userinfo) ){
                $this->returnAjax(array('code'=>4)); //未成功
            }else{
                $buy['loginUserId'] = $userinfo['id'];
            }
        }
        $role = array(
            'eq' => array(
                'source'        => 10,
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
            //推送求购到分配系统
            $this->load('temp')->pushTrack($content, $name, $mobile, $sid, $area);
            //if ( $userinfo ) $this->load('passport')->setLogin($userinfo);
            $this->returnAjax(array('code'=>1));//成功
        }
        $this->returnAjax(array('code'=>4)); //未成功
    }

    /**
     * 添加临时求购信息
     *
     * @author  Xuni
     * @since   2015-11-25
     *
     * @access  public
     *
     * @return  json
     */
    public function addTemp()
    {
        if ( !$this->isAjax() ) $this->redirect('', '/');

        $mobile     = $this->input('mobile', 'string', '');
        $content    = $this->input('content', 'string', '');
        $name       = $this->input('name', 'string', '');
        $sid        = $this->input('sid', 'string', '');
        $area       = $this->input('area', 'string', '');

        if ( $this->isLogin ){
            $this->returnAjax(array('code'=>2)); //已登录不添加临时
        }
        $userinfo = $this->load('passport')->get($mobile,2);
        if ( $userinfo ) $this->returnAjax(array('code'=>2)); //已注册不添加临时
        $user = $this->load('temp')->isExist($mobile);
        if ( $user ) $this->returnAjax(array('code'=>2)); //已临时注册不添加临时
        $role = array(
            'eq' => array(
                'mobile'    => $mobile,
                'need'      => $content,
                //'userId'    => empty($userinfo)?'0':$userinfo['id'],
                ),
            );
        $temp = $this->load('buy')->findTemp($role);
        if ( $temp ){
            $this->returnAjax(array('code'=>1));//未添加
        }
        $buy = array(
            'mobile'    => $mobile,
            'need'      => $content,
            'name'      => $name,
            'sid'       => $sid,
            'area'      => $area,
            //'userId'    => empty($userinfo)?'0':$userinfo['id'],
            'ip'        => getClientIp(),
            'date'      => time(),
            );
        $res = $this->load('buy')->addTempBuy($buy);
        if ( $res ) {
            $this->returnAjax(array('code'=>1));//成功
        }
        $this->returnAjax(array('code'=>0)); //未成功
    }

}
?>
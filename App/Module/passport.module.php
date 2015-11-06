<?
/**
 * 用户账号登录、验证、修改
 *
 * 用户账号登录、验证、修改
 * 
 * @package	Module
 * @author	Xuni
 * @since	2015-11-05
 */
class PassportModule extends AppModule
{

    /**
     * 获取账号信息
     * 
     * @author  Xuni
     * @since   2015-11-05
     *
     * @access  public
     * @param   string  $email      收件人邮箱
     * @param   string  $title      邮件标题
     *
     * @return  array   $data       用户信息
     */
    public function get($account, $cateId=2)
    {
        $res = $this->importBi('passport')->get($account, $cateId);
        if ( isset($res['code']) && $res['code'] == 1 ){
            return empty($res['data']) ? array() : (array)$res['data'];
        }
        return array();
    }
	
    /**
     * 账户登录
     * 
     * @author  Xuni
     * @since   2015-11-05
     *
     * @access  public
     * @param   string  $account    登录账户
     * @param   string  $password   登录密码
     * @param   int     $cateId     账户标识(1邮件、2手机、3用户名)
     *
     * @return  int     $data       用户信息
     */
    public function login($account, $password,  $cateId=1)
    {
        $ip     = getClientIp();
        $res    = $this->importBi('passport')->login($account, $cateId, $password, $ip);
        return $res;
    }

}
?>
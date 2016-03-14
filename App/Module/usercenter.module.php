<?
/**
 * 用户中心组件
 *
 * 与用户中心交互接口
 * 
 * @package	Module
 * @author	Xuni
 * @since	2016-03-14
 */
class UserCenterModule extends AppModule
{

    public function existLook($numbers)
    {
        //判断是否登录
        if ( empty($this->loginId) ) return array();
        if ( empty($numbers) ) return array();

        $res = $this->importBi('usercenter')->existLook($numbers, $this->loginId);
        return $res;
    }
	
}
?>
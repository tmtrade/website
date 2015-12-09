<?php 
class TempModule extends AppModule
{
    /**
	* 引用业务模型
	*/
	public $models = array(
        'user'      => 'tempuser',
	);

    /**
     * 基础获取求购信息的方法
     *
     * @author  Xuni
     * @since   2015-12-8
     *
     * @access  public
     * @param   array     $r     条件
     *
     * @return  array
     */
    public function find($r)
    {
        return $this->import('user')->find($r);
    }

	/**
     * 判断求购信息是否存在
	 * 
     * @author  Xuni
     * @since   2015-12-8
     *
     * @access  public
     * @param   array   $data   数据
     * @return  void
	 */
	public function isExist( $uname )
	{
        if ( empty($uname) ) return array();
        $r['eq']        = array('username'=>$uname);
        $r['limit']     = 1;
        $data           = $this->import('user')->find($r);
        return $data;
	}

    /**
     * 创建数据
     * @author	Xuni
     * @since	2015-12-8
     *
     * @access	public
     * @param	array	$data	数据
     * @return	void
     */
    public function create($uname, $upass, $sid, $memo)
    {
        $data = array(
            'username'  => $uname,
            'password'  => $upass,
            'date'      => time(),
            'ip'        => getClientIp(),
            'sid'       => $sid,
            'memo'      => $memo,
            );
        return $this->import("user")->create($data);
    }

	/**
     * 创建数据
     * @author  Xuni
     * @since   2015-12-8
     *
     * @access  public
     * @param   array   $data   数据
     * @return  void
     */
    public function moveTempToReal($userId, $uname, $sid)
    {
        $role['eq']     = array('mobile' => $uname);
        $role['notIn']  = array('status' => array(2));

        $temp = $this->load('buy')->findTemp($role);
        if ( empty($temp) ) return true;

        $buy = array(
            'source'        => 10,
            'phone'         => $uname,
            'need'          => $temp['need'],
            'sid'           => $temp['sid'],
            'area'          => $temp['area'],
            'contact'       => $temp['name'],
            'date'          => time(),
            'loginUserId'   => $userId,
            );
        $res = $this->load('buy')->create($buy);//创建求购信息
        if ( $res ){
            $rol['eq'] = array('mobile' => $uname);
            $this->load('buy')->removeTemp($rol);
            $r['eq'] = array('username' => $uname);
            $this->import('user')->remove($r);
            return true;
        }
        return false;
    }

}
?>

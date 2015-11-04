<?
class PassportBi extends Bi
{
	/**
	 * 对外接口域名编号(默认为超凡网、其他待定)
	 */
	public $apiId = 2;


	/**
	 * 获取账户信息
	 * 
	 * @access	public
	 * @param	string	$account	登录账户
	 * @param	int		$cateId		账户标识(1邮件、2手机、3用户名、4用户id)
	 * @return	array   返回账户所有信息(空为账户不存在)
	 */
	public function get($account, $cateId=1)
	{
		$param = array(
			'account' => $account,
			'cateId'  => $cateId,
			);
		$data = $this->request("passport/get/", $param);

		return empty($data) ? array() : $data;
	}

}
?>
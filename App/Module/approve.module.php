<?
/**
 * 出售信息
 *
 * 获取出售信息，添加出售信息
 * 
 * @package	Module
 * @author	Xuni
 * @since	2015-09-15
 */
class ApproveModule extends AppModule
{
    /**
     * 引用业务模型
     */
    public $models = array(
        'approve'		=> 'approve',
        'proposer'		=> 'proposer',
        'note'			=> 'note',
        'member'		=> 'member',
        );


	/**
	 * 获取申请人信息
	 * @author	Xuni
	 *
	 * @access	public
	 * @param	array 	$id			申请人newID
	 *
	 * @return	array
	 */
    public function getProposer($id)
    {
    	$r['eq'] = array('newId'=>$id);
    	return $this->import('proposer')->find($r);
    }

	/**
	 * 验证email与申请人是否关联
	 * @author	Xuni
	 *
	 * @return	bool
	 */
    public function verifyProposer($account, $proposer)
    {
    	$res = $this->importBi('proposer')->VerifyEmail($account, $proposer);
    	return $res;
    }

	/**
	 * 获取单条认证信息
	 * @author	Xuni
	 *
	 * @return	array
	 */
    public function getInfo($role)
    {
    	return $this->import('approve')->find($role);
    }
	
	/**
	 * 查询出售商标是否认证
	 * @author	JEANY
	 * @since	2015-09-17
	 *
	 * @access	public
	 * @param	array
	 * @return	array
	 */
	public function getApprove($param)
	{
		$params['limit'] 	= 1;
		$params['page']		= 1;
		$result = array('status'=>4,'type'=>3);
		$data = $this->import('approve')->findAll($params);
		if($data['rows']){
			foreach($data['rows'] as $val){
				
				if($val['status'] == 2){
					$result['status'] = 2;
					$result['type'] = $val['type'];
				}
			}
		}
		return $result;
	}

	/**
	 * 添加单条认证信息
	 * @author	Xuni
	 *
	 * @return	int
	 */
    public function addApprove($data)
    {
    	return $this->import('approve')->create($data);
    }


	/**
	 * 添加单条站内信
	 * @author	Xuni
	 *
	 * @return	int
	 */
    public function addNote($data)
    {
    	return $this->import('note')->add($data);
    }

	/**
	 * 更新出售信息认证方式与状态
	 * @author	Xuni
	 *
	 * @return	bool
	 */
    public function updateSaleApprove($sid, $type, $status)
    {
    	return $this->load('sale')->updateApprove($sid, $type, $status);
    }

	/**
	 * 获取相关角色的账户
	 * @author	Xuni
	 *
	 * @return	array
	 */
    public function getAdmin()
    { 
    	$r['limit'] = 100;
    	$r['eq'] 	= array('roleId'=>1,'isUse'=>1);

    	return $this->import('member')->find($r);
    }
}
?>
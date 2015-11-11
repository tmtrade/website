<?
/**
 * 申请人
 *
 * 获取出售信息，添加出售信息
 * 
 * @package	Module
 * @author	JEANY
 * @since	2015-09-15
 */
class ProposerModule extends AppModule
{
    /**
     * 引用业务模型
     */
    public $models = array(
        'proposer'		=> 'proposer',
        );


	/**
	 * 获取代理人信息
	 * @author	Xuni
	 *
	 * @access	public
	 * @param	array 	$id			申请人newID
	 *
	 * @return	array
	 */
    public function getProposer($id)
    {
    	$r['eq'] = array('id'=>$id);
		$data = $this->import('proposer')->find($r);
    	return $data ? $data : '';
    }

	
}
?>
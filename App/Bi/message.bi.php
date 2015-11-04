<?
/**
 * 消息Api代理接口
 *
 * 发送邮件、短信
 *
 * @package	Bi
 * @author	void
 * @since	2014-11-03
 */
class MessageBi extends Bi
{
	/**
	 * 接口标识
	 */
	public $apiId = 2;

	/**
	 * 发送邮件
	 * 
	 * @author	void
	 * @since	2014-11-03
	 *
	 * @access	public
	 * @param	string	$email		收件人邮箱
	 * @param	string	$title		邮件标题
	 * @param	string	$content	邮件内容
	 * @param	string	$name		收件人名称[没有则为空]
	 * @param	string	$from		签名
	 * 
	 * @return	array
	 */
	public function sendMail($email, $title, $content, $name = '', $from='超凡网')
	{
		$param = array(
			'email'   => $email,
			'title'   => $title,
			'content' => $content,
			'name'	  => $name,
			'from'	  => $from,
		);
		
		return $this->request("message/sendMail/", $param);
	}
}
?>
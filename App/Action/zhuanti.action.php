<?
/**
 * 专题信息
 *
 * 专题界面
 *
 * @package	Action
 * @author  far
 * @since   2016-3-08
 */
class ZhuantiAction extends AppAction
{
    public $seotime		= '一只蝉';
    /**
     * 专题中心
     * @author  far
     * @since   2016-3-08
     */
    public function index()
    {
		$title		 = "一只蝉：百万注册商标转让平台网-独家100%签定风险赔付协议";
		$keywords	 = "一只蝉,商标转让,商标转让网,注册商标转让,转让商标,商标买卖,商标交易,商标交易网";
		$description = "一只蝉是超凡集团资产交易平台：13年积累约200余万商标转让信息-也是中国独家签订交易损失赔付协议保障风险平台-商标转让-专利转让";
		$this->set('title', $title);
                $page 	= $this->input('page', 'int');
                $res 	= $this->load('zhuanti')->getList(0, $page, $this->rowNum);
                $total 	= empty($res['total']) ? 0 : $res['total'];
		$list 	= empty($res['rows']) ? array() : $res['rows'];

		$pager 		= $this->pagerNew($total, $this->rowNum);
                $pageBar 	= empty($list) ? '' : getPageBarNew($pager);
                $this->set('list', $list);
                $this->set("pageBar",$pageBar);
                $this->display();
    }
	
    /**
     * 专题详情
     * @author  far
     * @since   2016-3-08
     */
    public function view() 
	{	
		$id 	= $this->input('id', 'int', '0');
                $page 	= $this->input('page', 'int');
		$topic = $topicItems = array();
		if($id){
			$topic 	= $this->load('zhuanti')->getTopicInfo($id);
			$topicItems = $this->load('zhuanti')->getTopicClassList($id);
		}
		$this->set('topic', $topic);
		$this->set('topicItems', $topicItems);
                
                $res 	= $this->load('zhuanti')->getList($id, $page, 4,array('isMore'=>'asc','sort'=>'asc'));
		$list 	= empty($res['rows']) ? array() : $res['rows'];
                $title   = $topic['title'] . ' - '.$this->seotime;
                $this->set('list', $list);
                $this->set('title', $title);
                $this->display();
	}

}
?>
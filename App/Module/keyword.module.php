<?
/**
 * 关键字、搜索地址
 * 
 * 查询、创建
 *
 * @package	Module
 * @author	Xuni
 * @since	2016-04-13
 */
class KeywordModule extends AppModule
{
	
    /**
     * 引用业务模型
     */
    public $models = array(
        'kw'    => 'keyword',
        'su'    => 'searchUrl',
    );
	
    /**
     * 获取关键字ID
     * 
     * @author  Xuni
     * @since   2016-04-13
     *
     * @access  public
     * @param   string      $keyword    搜索关键字
     *
     * @return  int         关键字ID
     */
    public function getKeywordId($keyword)
    {
        if ( empty($keyword) ) return '0';

        $r['eq']    = array('keyword'=>$keyword);
        $r['col']   = array('id');
        $r['limit'] = 1;
        $res = $this->import('kw')->find($r);
        if ( $res['id'] > 0 ) return $res['id'];

        $data = array(
            'keyword'   => $keyword,
            'date'      => time(),
            );
        return $this->import('kw')->create($data);
    }


    /**
     * 通过ID获取关键字
     * 
     * @author  Xuni
     * @since   2016-04-13
     *
     * @access  public
     * @param   int         $id    关键字ID
     *
     * @return  string      关键字
     */
    public function getKeywordById($id)
    {
        if ( empty($id) ) return '';

        $r['eq']    = array('id'=>$id);
        $r['col']   = array('keyword');
        $r['limit'] = 1;
        $res = $this->import('kw')->find($r);
        
        if ( empty($res['keyword']) ) return '';
        return $res['keyword'];
    }

    /**
     * 获取搜索地址ID
     * 
     * @author  Xuni
     * @since   2016-04-13
     *
     * @access  public
     * @param   string      $url        搜索地址
     * @param   int         $isData     是否有数据
     *
     * @return  int         搜索地址ID
     */
    public function getUrlId($url, $isData)
    {
        if ( empty($url) ) return '0';

        $r['eq']    = array('url'=>$url);
        $r['col']   = array('id');
        $r['limit'] = 1;
        $res = $this->import('su')->find($r);
        if ( $res['id'] > 0 ) return $res['id'];

        $data = array(
            'url'       => $url,
            'isData'    => $isData,
            'date'      => time(),
            );
        return $this->import('su')->create($data);
    }


    /**
     * 通过ID搜索地址键字
     * 
     * @author  Xuni
     * @since   2016-04-13
     *
     * @access  public
     * @param   int         $id         搜索地址ID
     *
     * @return  string      搜索地址
     */
    public function getUrlById($id)
    {
        if ( empty($id) ) return '';

        $r['eq']    = array('id'=>$id);
        $r['col']   = array('url');
        $r['limit'] = 1;
        $res = $this->import('su')->find($r);
        
        if ( empty($res['url']) ) return '';
        return $res['url'];
    }

}
?>
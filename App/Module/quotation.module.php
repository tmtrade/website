<?
/**
 * 商品报价单
 * 
 * 查询、创建
 *
 * @package	Module
 * @author	dower
 * @since	2016-08-25
 */
class quotationModule extends AppModule
{
	private $class = null;
    /**
     * 引用业务模型
     */
    public $models = array(
        'quotation'         => 'quotation',
        'quotationItems'    => 'quotationItems',
        'userImage'         => 'userImage',
        'tminfo'        => 'saleTminfo',
        'tm'		=> 'trademark',
    );

    /**
     * 得到报价单详细信息
     * @param $id
     * @param $uid int 用户id
     * @return array
     */
    public function getDetail($id,$uid){
        $r = array();
        $r['eq']['id'] = $id;
        $rst = $this->import('quotation')->find($r);

        if($rst){
            //得到报价单详细数据
            $rst['data'] = $this->handleData($id,$uid);
        }
        return $rst?:array();
    }

    /**
     * 得到报价单商标信息
     * @param $id
     * @param $uid
     * @return array
     */
    private function handleData($id,$uid){
        if(!$id) return array();
        //得到报价单的商标信息
        $r = array();
        $r['eq']['qid'] = $id;
        $r['limit'] = 50;
        $r['order'] = array('sort'=>'asc');
        $rst = $this->import('quotationItems')->find($r);
        if(!$rst) return array();
        foreach($rst as $k=>$row){
            //得到图片
            $rst[$k]['img'] = $this->getImg($row['number'],$uid);
            //得到商品-分类-申请时间信息
            $tmp = $this->getTm($row['number']);
            $rst[$k] = array_merge($rst[$k],$tmp);
        }
        return $rst;
    }

    /**
     * 得到商标的分类名数组
     * @return array|null
     */
    private function getClass(){
        if(!$this->class){
            $class = $this->load('search')->getClassGroup(0,0);
            $this->class = $class?$class[0]:array();
        }
        return $this->class;
    }

    /**
     * 得到商标图片
     * @param $number
     * @param $uid
     * @return string
     */
    private function getImg($number,$uid){
        //查询用户上传
        $r = array();
        $r['eq']['uid'] = $uid;
        $r['eq']['number'] = $number;
        $r['order'] = array('created'=>'desc');
        $r['col'] = array('image');
        $rst = $this->import('userImage')->find($r);
        if($rst && $rst['image']){
            return SELLER_URL.$rst['image'];
        }else{
            //查询美化商标
            $r = array();
            $r['eq']['number'] = $number;
            $r['col'] = 'embellish';
            $rst = $this->import('tminfo')->find($r);
            if($rst && $rst['embellish']){
                return  TRADE_URL.$rst['embellish'];
            }else{
                //查询商标原图
                return $this->load('trademark')->getImg($number);
            }
        }
    }

    /**
     * 得到商标的商品.申请时间.分类信息
     * @param $number
     * @return array|mixed
     */
    private function getTm($number){
        $r = array();
        $r['eq']['id'] = $number;
        $r['col'] = array('goods,apply_date,class');
//        $r['limit'] = 50;//此处待处理----多类问题
        $rst = $this->import('tm')->find($r);
        if(!$rst) return array();
//        $info = current($rst);
//        $info['class'] = arrayColumn($rst,'class');//此处待处理----多类问题
        $tmp = $this->getClass();
        $rst['classname'] = $tmp[$rst['class']];
        return $rst;
    }
}
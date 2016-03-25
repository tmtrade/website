<?
/**
 * 商标详情界面
 *
 * 商标详情界面
 *
 * @package Action
 * @author  Dower
 * @since   2016-03-08
 */
class DetailAction extends AppAction
{
	/**
	 * 设置标题
	 * @param $data
	 * @param $goods
	 * @return string
	 */
	private function getTitle($data,$goods)
	{
		$title = $data['name']."_".$data['class']."类_".$goods."商标转让|买卖|交易 – 一只蝉商标转让平台网";
		return $title;
	}

	/**
	 * 商标详情
	 * @throws SpringException
	 */
	public function view(){
		if(isset($_COOKIE['uc_nickname'])){
			$this->set('user_tel',$_COOKIE['uc_nickname']);
		}else{
			$this->set('user_tel','');
		}
                
		$tag = $this->input('short', 'string', '');
		//获取参数
		if ( $tag ){
			if ( strpos($tag, '-') === false ) $this->redirect('未找到页面1', '/index/error');
			list($tid, $class) = explode('-', $tag);
		}else{
			$this->redirect('未找到页面2', '/index/error');
		}
		//获得商标号和分类
		$_info 	= $this->load('trademark')->getInfo($tid,array('`id` as `number`','class'));
		$number = $_info['number'];
		if ( empty($_info) || empty($number) ){
			$this->redirect('未找到页面3', '/index/error');
		}elseif( !in_array($class, range(1,45)) ){
			$this->redirect('未找到页面4', '/index/error');
		}
		//得到商标信息
		$info 	= $this->load('trademark')->getTmInfo($number);
		if ( empty($info) ){
			$this->redirect('未找到页面5', '/index/error');
		}
		if ( !in_array($class, $info['class']) ){
			$this->redirect('未找到页面6', '/index/error');
		}
		//解析商标群组名
//		foreach($info['items'] as &$item){
//			$item['groupName'] = $this->load('search')->handleGroup($item['group']);
//		}
		//unset($item);
		//得到商标的分类描述
		if(count($info['class']==1)){
			$res = $this->load('search')->getClassInfo($info['class'][0]);
			$info['label'] = $res['title'].': '.preg_replace('/,/','/',$res['label']);
			$info['className'] = $res['name'];//分类名
			//处理商标名和分类描述的字符问题
			$info['thum_name'] = mbSub($info['name'],0,10);//10字符
			$info['thum_label'] = mbSub($info['label'],0,20);//20字符
		}
		//分配平台数据到页面
		$this->set("platformIn", C('PLATFORM_IN'));
		$this->set("platformUrl", C('PLATFORM_URL'));
		$this->set("platformItems", C('PLATFORM_ITEMS'));
		//商标是否出售,获取其他信息
		$issale = 0;
		$tid 	= $info['tid'];
		$class 	= current($info['class']);
		$_class = implode(',', $info['class']);
		$other 	= $this->load('trademark')->getTmOther($number);//商标其他信息
		$saleId = $this->load('internal')->existSale($number);//是否出售
		if ( $saleId <= 0 ){//不是出售信息中的
			$platform 	= $other['platform'];
			$isSale 	= $this->load('blacklist')->isBlack($number) ? false : true;
			$tips = $sale = array();

			$tips['safeUrl'] 	= 'http://jingling.yizhchan.com/?nid='.$number.'&class='.$class;
			$contact['name']	= '蝉妹妹';
			$contact['phone']	= $this->getPhoneName($tid, $class, 0);
		}else{
			$sale 		= $this->load('internal')->getSaleInfo($saleId, 0, 1,false);
			$platform 	= explode(',', $sale['platform']);
			$isSale  	= $sale['status'] == 1 ? true : false;
			$tips 		= $this->load('search')->getTips($sale);

			$contact['name']	= $this->getPhoneName($tid, $class, 1);//获取联系人信息
			$contact['phone']	= empty($sale['viewPhone']) ? '18602868321' : $sale['viewPhone'];
		}
		//合并商标的额外数据
		$saleExt = $this->load('search')->getExtSaleInfo($saleId);
		$sale = array_merge($sale,$saleExt);
		//设置标题
		$title['name'] 	= $info['name'];
		$title['class']	= $class;
		$goods 			= current( explode(',', $info['goods']) );
		$this->set('title', $this->getTitle($title,$goods));
		//读取推荐商标
		$refer 	= $this->load("internal")->getReferrer($_class, 8, $number);
		$tj 	= $this->load('search')->getListTips($refer);

                //存取浏览记录
                $prefix = C('COOKIE_PREFIX');
                $cookie_record = $prefix.C('PUBLIC_RECORD');
                $is_record = FALSE;
                $record = $_COOKIE[$cookie_record]; 
                $record = unserialize($record);
                foreach ($record as $v){
                    if($v['tid']==$tid){
                        $is_record = TRUE;
                    }
                }
                if(!$is_record){
                    $recordList = array(0=>array("tid"=>$tid,"class"=>$class,"imgUrl"=>$info['imgUrl']));
                    for($i=0;$i<7;$i++){
                        if(!empty($record[$i])){
                            $recordList[] = $record[$i];
                        }
                    }
                    $recordList = serialize($recordList);
                    setcookie($cookie_record,$recordList,0, Session::$path, Session::$domain);
                }
                if(!empty($record)){
                    $this->set("recordList", $record);
                }else{//没有浏览记录时推荐5个
                    $newList 	= $this->load("internal")->getNewSale($_class, $number,5);
                    $newLists 	= $this->load('search')->getListTips($newList);
                    $this->set("recordList", $newLists);
                }

		//电话旁边联系人信息
		$this->set("contact", $contact);
		//得到商标参与的专题
		$topic = $this->load('zhuanti')->getTopicByNumber($number);
		//得到用户订单的need字段
		$need = "商标号:".$info['number'].",类别:".implode(',',$info['class']);
		//分配数据
		$this->set("info", $info);
		$this->set("sale", $sale);
		$this->set("need", $need);
		$this->set("topic", $topic);
		$this->set("tips", $tips);
		$this->set("tid", $tid);
		$this->set("class", $class);
		$this->set("isSale", $isSale);
		$this->set("userMobile", $this->userMobile);
		$this->set("platform", $platform);
		$this->set("tj", $tj);
		$this->display();
	}

	/**
	 * 电话号码姓氏挑选
	 * @param $tid
	 * @param $class
	 * @param int $issale
	 * @return string
	 * @throws SpringException
	 */
	private function getPhoneName($tid,$class,$issale = 0)
	{
		if ( $issale ){
			$name = "赵 钱 孙 李 周 吴 郑 王 冯 陈 褚 卫 蒋 沈 韩 杨 朱 秦 尤 许 何 吕 施 张 孔 曹 严 华 金 魏 陶 姜 戚 谢 邹 喻
			柏 水 窦 章 云 苏 潘 葛 奚 范 彭 郎 鲁 韦 昌 马 苗 凤 花 方 俞 任 袁 柳 酆 鲍 史 唐 费 廉 岑 薛 雷 贺 倪 汤 刘 母 白
			滕 殷 罗 毕 郝 邬 安 常 乐 于 时 傅 皮 卞 齐 康 伍 余 元 卜 顾 孟 平 黄 和 穆 萧 尹 姚 邵 湛 汪 祁 毛 禹 狄 欧阳 慕容
			米 贝 明 臧 计 伏 成 戴 谈 宋 茅 庞 熊 纪 舒 屈 项 祝 董 梁 杜 阮 蓝 闵 席 季 麻 强 贾 路 娄 危 江 童 颜 郭 蒲 崔 沙
			梅 盛 林 刁 锺 徐 邱 骆 高 夏 蔡 田 樊 胡 凌 霍 虞 万 支 柯 昝 管 卢 莫 经 房 裘 缪 干 解 应 宗 丁 宣 贲 邓";

			$lastTid 	= ceil(substr($tid,-1)/3)*$class;
			$sex 		= ($class % 2 == 0) ? 1 : 2;
			$gender 	= array(1 => '先生', 2 => '女士');
			$nameArr 	= explode(" ", $name);
			$name 		= $nameArr[$lastTid].$gender[$sex];
			return $name;
		}

		$phoneArr 	= $this->load('phone')->getSexPhone();
		$phoneid 	= ceil(substr($tid,-1)/3*2) > 5 ? 5 : ceil(substr($tid,-1)/3*2);
		$phone 		= empty($phoneArr[$phoneid]) ? '18602868321' : $phoneArr[$phoneid];
		return $phone;
	}
        
	//返回登录用户的收藏列表
	public function ajaxSclist(){
		$arr = $this->input('tid', 'string', '');
		$number = explode(",", $arr);
		foreach ($number as $v){
			$sclist[] = $this->load('trademark')->getTmInfo($v);
		}
		$this->returnAjax($sclist);
	}
}
?>
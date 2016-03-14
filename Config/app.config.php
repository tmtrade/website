<?
//定义应用所需常量

//定义密约
define('AuthKey', md5('8782aa97c31e56363ced4e2e85ed81ee'));
define('FileSystemDir', DataDir.'/attachment');	                        //定义文件系统存放路径
define('FileSystemUrl', 'http://e2.chofn.net/'.DataDir.'/attachment/');	//定义文件系统地址

//近似查询接口相关配置
//define('SEARCH_URL', 'http://searchapi.chofn.net/');
define('SEARCH_URL', 'http://tmsearch.chofn.api/');
define('SEARCH_KEY', '89eb637c610f94b9d281c458bca42421');

//个人中心URL
define('MANAGER_URL', 'http://i.chofn.net/');
define('PERSONAL_CENTER', 'http://uc.test.chofn.net/');
//一只蝉URL
define('SITE_URL', 'http://t.chofn.net/');
//交易站后台URL
define('TRADE_URL', 'http://tr2.chofn.net/');
//近似查询URL
define('GUANJIA_URL', 'http://s.chofn.net/');
//文章发布后台API地址
define('WM_HOST', 'http://wm.chofn.net/api/');

?>
<?
$prefix     = 't_';
$dbId       = 'trade';
$configFile = array( ConfigDir.'/Db/trade.master.config.php' );

$tbl['tsale'] = array(
    'name'      => $prefix.'sale',
    'dbId'      => $dbId, 
    'configFile'=> $configFile,
);

$tbl['tsaleTrademark'] = array(
    'name'      => $prefix.'sale_trademark',
    'dbId'      => $dbId, 
    'configFile'=> $configFile,
);

$tbl['tbuy'] = array(
    'name'      => $prefix.'buy',
    'dbId'      => $dbId, 
    'configFile'=> $configFile,
);

$tbl['tbuyDeal'] = array(
    'name'      => $prefix.'buy_deal',
    'dbId'      => $dbId, 
    'configFile'=> $configFile,
);

$tbl['thistory'] = array(
    'name'      => $prefix.'history',
    'dbId'      => $dbId, 
    'configFile'=> $configFile,
);

$tbl['ttempBuy'] = array(
    'name'      => $prefix.'temp_buy',
    'dbId'      => $dbId, 
    'configFile'=> $configFile,
);

$tbl['ttempUser'] = array(
    'name'      => $prefix.'temp_user',
    'dbId'      => $dbId, 
    'configFile'=> $configFile,
);



?>
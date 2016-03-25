<?
/**
 +------------------------------------------------------------------------------
 * Spring框架 数据实体层
 +------------------------------------------------------------------------------
 * @mobile	13183857698
 * @qq		78252859
 * @author  void <lkf5_303@163.com>
 * @version 3.0
 +------------------------------------------------------------------------------
 */
class ModuleApi extends BaseCacheApi
{
	/**
	 * 数据表键[表的唯一标识]
	 */
	public $tableKey = 'module';

	/**
	 * 数据表主键
	 */
	public $pk       = 'id';
    
    /**
     * 缓存组件标识id
     */
    public $cacheId = 'redis';

    /**
     * 过期时间(60分钟)
     */
    public $expire  = 600;
}
?>
<?php
namespace app\model;

use acphp\base\Model;
use acphp\db\Db;

/**
 * 用户 Model
 * Class Item
 * @package app\model
 */
class Item extends Model {
    /**
     * 自定义当前模型操作的数据库表名称，
     * 如果不指定，默认为类名称的小写字符串，
     * 这里就是 item 表
     * @var string
     */
    protected $table = 'item';

    /**
     * 搜索功能，因为 Sql 父类里面没有现成的 like 搜索，
     * 所有需要自己写 SQL 语句，对数据库的操作应该都放在 Model 里面，
     * 然后提供给 Controller 直接调用
     * @param $title string 查询的关键词
     * @return array 返回的数据
     * @author: 阿川 <achuan@achuan.io>
     * @Time: 2019/4/27 22:17
     */
    public function search($keyword) {
        $sql = 'select * from `$this->table` where `item_name` like :keyword';
        $sth = Db::pdo()->prepare($sql);
        $sth = $this->formatParam($sth, [':keyword' => "%$keyword"]);
        $sth->execute();

        return $sth->fetchAll();
    }
}

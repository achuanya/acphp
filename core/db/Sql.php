<?php

namespace core\db;

use \PDOStatement;

class Sql {
    // 数据库表名
    protected $table;

    // 数据库主键
    protected $primary  = 'id';

    // WHERE 和 ORDER 拼装后的条件
    private $filter     = '';

    // Pdo bindParam() 绑定的参数集合
    private $param      = array();

    /**
     * 查询条件拼接, 使用方式
     *
     * this->where(['id = 1', 'and title = "Web"', ...])->fetch();
     * 为了防止注入, 建议通过 $param 方式传入参数:
     * $this->where(['id = :id'], [':id => $id'])->fetch();
     *
     * @param: array $where 条件
     * @return: $this 当前对象
     * @author: 阿川 <achuan@achuan.io>
     * @Time: 2019/4/5 17:10
     */
    public function where($where = array(), $param = array()) {
        if ($where) {
            $this->filter .= ' WHERE';
            $this->filter .= implode('', $where);

            $this->param = $param;
        }
        return $this;
    }

    /**
     *
     * @param array $order 排序条件
     * @return $this
     * @author: 阿川 <achuan@achuan.io>
     * @Time: 2019/4/5 17:15
     */
    public function order($order = array()) {
        if ($order) {
            $this->filter .= ' ORDER BY';
            $this->filter .= implode(',', $order);
        }
        return $this;
    }

    // 查询所有
    public function fetchAll() {
        // $sql = sprintf(SELECT * FROM `%s` `%s`, $this->table, $this->filter);
        // $sth = Db::pdo;
    }




}
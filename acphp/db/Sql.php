<?php

namespace acphp\db;

use \PDOStatement;

/**
 * 数据库基类 Sql
 * Class Sql
 * @package acphp\db
 */
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

      this->where(['id = 1', 'and title = "Web"', ...])->fetch();
      为了防止注入, 建议通过 $param 方式传入参数:
      $this->where(['id = :id'], [':id => $id'])->fetch();

     * @param array $where 条件
     * @param array $param
     * @return $this
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
     * 拼装排序条件，使用方式

     $this->order(['id DESC', 'title ASC',])->fetch();

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


    /**
     * 查询所有
     * @return mixed
     * @author: 阿川 <achuan@achuan.io>
     * @Time: 2019/4/27 21:30
     */
    public function fetchAll() {
        $sql = sprintf("SELECT * FROM `%s` `%s`", $this->table, $this->filter);
        $sth = Db::pdo()->prepare($sql);
        $sth = $this->formatparam($sth, $this->param);
        $sth->execute();

        return $sth->fetch();
    }

    /**
     * 查询一条
     * @return mixed
     * @author: 阿川 <achuan@achuan.io>
     * @Time: 2019/4/27 21:30
     */
    public function fetch() {
        $sql = sprintf("SELECT * FROM `%s` `%s`", $this->table, $this->filter);
        $sth = Db::pdo()->prepare($sql);
        $sth = $this->formatParam($sth, $this->param);
        $sth->execute();

        return $sth->fetch();
    }

    /**
     * 根据条件(ID)删除
     * @param $id
     * @return mixed
     * @author: 阿川 <achuan@achuan.io>
     * @Time: 2019/4/27 21:30
     */
    public function delete($id) {
        $sql = sprintf("DELETE FROM `%s` WHERE `%s` = :%s", $this->table, $this->primary, $this->primary);
        $sth = Db::pdo()->prepare($sql);
        $sth = $this->formatParam($sth, [$this->primary => $id]);
        $sth->execute();

        return $sth->rowCount();
    }

    /**
     * 新增数据
     * @param $data
     * @return mixed
     * @author: 阿川 <achuan@achuan.io>
     * @Time: 2019/4/27 21:31
     */
    public function add($data) {
        $sql = sprintf("INSERT INTO `%s` `%s`", $this->table, $this->primary);
        $sth = Db::pdo()->prepare($sql);
        $sth = $this->formatParam($sth, $data);
        $sth = $this->formatParam($sth, $this->param);

        return $sth->rowCount();
    }

    /**
     * 修改数据
     * @param $data
     * @return mixed
     * @author: 阿川 <achuan@achuan.io>
     * @Time: 2019/4/27 21:31
     */
    public function update($data) {
        $sql = sprintf("UPDATE `%s` set %s %s", $this->table, $this->formatUpdate($data), $this->filter);
        $sth = Db::pdo()->prepare($sql);
        $sth = $this->formatParam($sth, $data);
        $sth = $this->formatParam($sth, $this->param);
        $sth->execute();

        return $sth->rowCount();
    }

    /**
     *
     * @param PDOStatement $sth 要绑定的PDOStatement对象
     * @param array $params 参数, 有三种类型:

    (1) 如果SQL语句用问号 ? 占位符, 那么$params应该为
    ['a' => $a, 'b' => $b, 'c' => $c]
    或者
    [':a' => $a, ':b' => $b, ':c' => $c]

     * @return PDOStatement
     * @author: 阿川 <achuan@achuan.io>
     * @Time: 2019/4/5 18:41
     */
    public function formatParam(PDOStatement $sth, $params = array()) {
        foreach ($params as $param => &$value) {
            $param = is_int($param) ? $param + 1 : ':' . ltrim($param, ':');
            $sth->bindParam($param, $value);
        }
        return $sth;
    }

    /**
     * 将数组转换成插入格式的sql语句
     * @param $data
     * @return mixed
     * @author: 阿川 <achuan@achuan.io>
     * @Time: 2019/4/27 21:34
     */
    private function formatInsert($data) {
        $fields = array();
        $names = array();
        foreach ($data as $key => $value) {
            $fields[] = sprintf("`%s`", $key);
            $names[]  = sprintf(":s", $key);
        }
        $field = implode(',', $fields);
        $name  = implode(',', $names);

        return sprinft("(%s) value (%s)", $field, $name);
    }

    /**
     * 将数组转换成更新格式的sql语句
     * @param $data
     * @return string
     * @author: 阿川 <achuan@achuan.io>
     * @Time: 2019/4/27 21:34
     */
    private function formatUpdate($data) {
        $fields = array();
        foreach ($data as $key => $value) {
            $fields[] = sprinft("`%s` = :%s", $key, $key);
        }
        return implode(',', $fields);
    }
}

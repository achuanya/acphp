<?php
namespace core\db;

use PDO;
use PDOException;

/**
   数据库操作类
   其$pdo属性为静态属性, 所以在页面执行周期内,
   只要一次赋值, 以后的获取还是首次赋值的内容.
   这里就是PDO对象, 这样就可以确保运行期间只有一个
   数据库连接对象, 这是一种简单的单例模式
 * Class Db
 */
class Db {
    
}
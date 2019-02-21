<?php

// 定义服务器属性
const DNS="mysql:host=127.0.0.1; dbname=";
const USERNAME="achuan";
const PASSWORD="200529";
/**
 * pdo 连接 mysql
 * @return void
 * @author 阿川 <achuan@achuan.io>
 */
function get_pdo(){
    $pdo=new PDO(DNS, USERNAME, PASSWORD);
    $pdo->query("SET NAMES UTF8");
    // mysql报错
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    // 强制小写
    $pdo->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
    return $pdo;
}
/**
 * 取得结果集
 * @param [type] $sql
 * @param array $arr
 * @return MySQL结果集
 * @author 阿川 <achuan@achuan.io>
 */
function get_stmt($sql, $arr=[]){
    $pdo=get_pdo();
    $stmt=$pdo->prepare($sql);
    // 判断数组类型
    if(isset($arr[0])){
        foreach($arr as $key => $value){
            $stmt->bindValue($key+1, $value);
        }
    } else{
        foreach($arr as $key => $value){
            $stmt->bindvalue(':'.$key, $value);
        }
    }
    $stmt->execute();
    return $stmt;
}
/**
 * 取得SQL查询内容,并规定显示格式
 * @param [type] $sql
 * @param array $arr
 * @param string $rtype
 * @return SQL语句查询的内容
 * @author 阿川 <achuan@achuan.io>
 */
function get_result($sql, $arr=[], $rtype=''){
    $stmt=get_stmt($sql, $arr);
    // 结果集的显示格式
    if($rtype=='object'){
        $type=$stmt->fetchAll(PDO::FETCH_OBJ);
    } else{
        $type=$stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return $type;
}
/**
 * 从结果集中取出操作的影响行数
 * @param [type] $sql
 * @param array $arr
 * @return SQL语句影响的行数
 */
function get_count($sql, $arr=[]){
    $stmt=get_stmt($sql, $arr);
    // 从结果集中取出操作的影响行数
    $impact=$stmt->rowCount();
    return $impact;
}
// 默认页面
$default_page=isset($_GET['p'])?$_GET['p']:1;
// 每页的显示条数
$according=3;
// (当前的页码数-1)*显示条数
$computing_page=($default_page-1)*$according;

$sql="SELECT id,title,y,m,d FROM t_lishi WHERE id>=:id_one and id<=:id_two LIMIT $computing_page, $according;";
$query=get_result($sql, ['id_one'=>2000, 'id_two'=>2100]);

// $sql="UPDATE t_lishi SET title=:title WHERE id=:id LIMIT $computing_page, $according";
// $query=get_count($sql, ["日本成为英国在太平洋地区的盟国", "2000"]);

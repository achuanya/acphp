<?php
namespace app\controller;

use acphp\base\Controller;
use app\model\Item;

/**
 * 用户 Controller
 * Class ItemController
 * @package app\controller
 */
class ItemController extends Controller {
    /**
     * 首页方法，测试框架自定义 DB 查询
     * @author: 阿川 <achuan@achuan.io>
     * @Time: 2019/4/27 22:29
     */
    public function index() {
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

        if ($keyword) {
            $items = (new Item())->search($keyword);
        } else {
            // 查询所有内容，并按倒序排列输出
            // where() 方法不传入参数，或者省略
            $items = (new Item)->where()->order(['id DESC'])->fetchAll();
        }

        $this->assign('title', '全部条目');
        $this->assign('keyword', $keyword);
        $this->assign('items', $items);
        $this->render();
    }

    /**
     * 查看单条记录详情
     * @param $id
     * @author: 阿川 <achuan@achuan.io>
     * @Time: 2019/4/27 22:44
     */
    public function detail($id) {
        // 通过？占位符传入$id参数
        $item = (new Item())->where(["id = ?"], [$id])->fetch();

        $this->assign('title', '管理条目');
        $this->assign('item', $item);
        $this->render();
    }

    /**
     * 添加记录，测试框架 DB 记录创建（Create）
     * @author: 阿川 <achuan@achuan.io>
     * @Time: 2019/4/27 22:47
     */
    public function add() {
        $data['item_name'] = $_POST['value'];
        $count = (new Item)->add($data);

        $this->assign('title',  '添加成功');
        $this->assign('count', $count);
        $this->render();
    }

    /**
     * 操作管理
     * @param int $id
     * @author: 阿川 <achuan@achuan.io>
     * @Time: 2019/4/27 22:38
     */
    public function manage($id = 0) {
        $item = array();
        if ($id) {
            // 通过名称占位符传入参数
            $item = (new Item())->where(["id = :id"], [':id => $id'])->fetch();
        }
        $this->assign('title', '管理条目');
        $this->assign('item', $item);
        $this->render();
    }

    /**
     * 更新记录，测试框架DB记录更新（Update）
     * @author: 阿川 <achuan@achuan.io>
     * @Time: 2019/4/27 22:52
     */
    public function update() {
        $data  = array('id' => $_POST['id'], 'item_name' => $_POST['value']);
        $count = (new Item)->where(['id = :id'], [':id' => $data['id']])->updata($data);

        $this->assign('title', '修改成功');
        $this->assign('count', $count);
        $this->render();
    }

    /**
     * 删除记录，测试框架DB记录删除（Delete）
     * @param null $id
     * @author: 阿川 <achuan@achuan.io>
     * @Time: 2019/4/27 22:56
     */
    public function delete($id = null) {
        $count = (new Item)->delete($id);

        $this->assign('title' ,'删除成功');
        $this->assgin('count', $count);
        $this->render();
    }
}

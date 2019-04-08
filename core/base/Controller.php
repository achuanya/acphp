<?php
namespace core\base;

/**
 * 控制器基类
 * Class controller
 * @package core\base
 * @author: 阿川 ahuan@achuan.io
 * @Time: 201902/24 17:13
 */
class controller {
    protected $_controller;
    protected $_action;
    protected $_view;

    /**
     * 构造函数, 初始化属性, 并实例化对应模型
     * controller constructor.
     * @param $controller $action
     * @author: 阿川 ahuan@achuan.io
     * @Time: 2019/2/24 17:33
     */
    public function __construct($controller, $action) {
        $this->_controller  = $controller;
        $this->_action      = $action;
        $this->_view        = new View($controller, $action);
    }

    /**
     * 分配变量
     * @param $name, $value
     * @author: 阿川 <achuan@achuan.io>
     * @Time: 2019/3/25 17:33
     */
    public function assign($name,  $value) {
        $this->_view->assign($name, $value);
    }

    /**
     * 渲染视图
     
        Controller 类用 assign() 方法实现把变量保存到View对象中
        这样, 在调用 $this->render() 后视图文件就能显示这些变量

     * @author: 阿川 <achuan@achuan.io>
     * @Time: 2019/3/25 17:35
     */
    public function render() {
        $this->_view->render();
    }
}
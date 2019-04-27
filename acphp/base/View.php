<?php
namespace acphp\base;

/**
 * 视图基类
 * Class View
 * @package acphp\base
 */
class View {
    protected $variables = array();
    protected $_controller;
    protected $_action;

    /**
     * 构造函数，初始化属性并实例化对应模型
     * View constructor.
     * @param $controller
     * @param $action
     */
    function __construct($controller, $action) {
        $this->_controller = strtolower($controller);
        $this->_action     = strtolower($action);
    }

    /**
     * 分配变量
     * @param $name
     * @param $value
     * @author: 阿川 <achuan@achuan.io>
     * @Time: 2019/4/27 21:44
     */
    public function assign($name, $value) {
        $this->variables[$name] = $value;
    }

    /**
     * 渲染显示
     * @return:
     * @author: 阿川 <achuan@achuan.io>
     * @Time: 2019/4/27 21:04
     */
    public function render() {
        extract($this->variables);
        $defaultHeader    = APP_PATH . 'app/view/header.php';
        $defaultFooter    = APP_PATH . 'app/view/footer.php';

        $controllerHeader = APP_PATH . 'app/view/' . $this->_controller . 'header.php';
        $controllerFooter = APP_PATH . 'app/view/' . $this->_controller . 'footer.php';
        $controllerLayout = APP_PATH . 'app/view/' . $this->_controller . '/' . $this->_action. '.php';

        // 页头文件
        if (is_file($controllerHeader)) {
            include ($controllerHeader);
        } else {
            include ($defaultHeader);
        }

        // 判断视图文件是否存在
        if (is_file($controllerLayout)) {
            include ($controllerLayout);
        } else {
            echo '<h1>无法找到视图文件!<h1>';
        }

        // 页脚文件
        if (is_file($controllerFooter)) {
            include ($controllerFooter);
        } else {
            include ($defaultFooter);
        }
    }
}
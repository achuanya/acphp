<?php
namespace core\base;
/*
 * @Description: 控制器基类
 * @author: 阿川 ahuan@achuan.io
 * @Date: 2019-02-24 16:52:58
 * @LastEditTime: 2019-02-24 17:38:28
 */

/**
 * @Description: 控制器基类
 * @author: 阿川 ahuan@achuan.io
 * @Date: 2019-02-24 17:13:29
 */

class controller {
    protected $_controller;
    protected $_action;
    protected $_view;

    /**
     * @Description: 构造函数，初始化属性，并实例化对应模型
     * @param $controller, $action 
     * @return: 实例化对象
     * @author: 阿川 ahuan@achuan.io
     * @Date: 2019-02-24 17:19:59
     */
    public function __construct($controller, $action) {
        $this->_controller = $controller;
        $this->_action = $action;
        $this->_view = new View($controller, $action);
    }

    /**
     * @Description:分配变量
     * @param $name, $value
     * @return: 
     * @author: 阿川 ahuan@achuan.io
     * @Date: 2019-02-24 17:24:09
     */
    public function assign($name,  $value) {

    }
}
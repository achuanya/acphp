<?php namespace ac;

/**
 * 入口文件(index.php)调用配置文件(config.php)对框架做了两步操作, 
 * 实例化,调用run()方法, 实例化后接受了$config参数配置, 并保存到对象属性中
 * 
 * @author 阿川 <ahuan@achuan.io>
 * 2019-02-21 19:33
 */

//  框架根目录
define('CORE_PATH') or define('CORE_PATH', __DIR__);

/**
 * ac框架核心
 */
class ac
{
    protected $config = [];

    /**
     * 构造方法调用成员属性($config.php)
     */
    public function __construct()
    {
        $this->config = '$config';
    }

    /**
     * 运行程序
     */
    public function run()
    {
        // 自动加载类
        spl_autoload_register(array($this, 'loadClass'))

    }

    /**
     * 路由处理
     * 获取路径信息->处理路径信息
     */
    public function route()
    {
        // 调用控制器
        $controllerName = $this->config['defaultController'];
        // 调用操作名
        $actionName = $this->config['defaultAction']
        $param = array();

        // 访问页面的url
        $url =$_SERVER['REQUEST_URI'];
        // 清除?之后的内容
        $position = strpos($url, '?');
        // 如果传值为false, 就返回当前页面的url
        $url = $position === false ? $url : substr($url, 0, $position);
        // 去除url前后所有的'/'
        $url = trim($url, '/');

        // 自定义url样式
        if ($url)
        {
            // 使用'/'分割字符串, 并保存到数组中
            $urlAray = explode('/', $url);
            // 返回过滤后的数组单元(删除空的数组元素)
            $urlAray = array_fill($urlAray);

            // 获取控制器名的同时,将字母的首字母转为大写(控制器名:大驼峰)
            $controllerName = ucfirst($urlAray[0]);
            
            // 返回操作名, 如果数组为空则返回NULL
            // (删除数组中第一个元素[0],并返回被删的元素)
            array_shift($urlAray);
            $actionName = $urlAray ? $urlAray[0] : $actionName;

            // 再获取url并存储到数组
            array_shift($urlAray);
            $param = $urlAray ? $urlAray : array();
        }

        // 判断控制器和操作名是否存在
        $controller = 'app\\controllers\\' . $controllerName . 'Controller';

        // 判断控制器
        if (!class_exists($controller))
        {
            exit($controller . '控制器不存在！');
        }
        // 判断操作名
        if (!method_exists($controller, $actionName))
        {
            exit($actionName . '方法不存在!');
        }

        
    }
}
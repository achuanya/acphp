<?php 
namespace acphp;
/**
 * 入口文件(index.php)调用配置文件(config.php)对框架做了两步操作,
 * 实例化,调用run()方法, 实例化后接受了$config参数配置, 并保存到对象属性中
 * @author: 阿川 ahuan@achuan.io
 * @Date: 2019-02-21 19:01:01
 */

// 框架根目录
defined('CORE_PATH') or define('CORE_PATH', __DIR__);

/**
 * acphp框架核心
 * @author: 阿川 ahuan@achuan.io
 * @Date: 2019-02-24 14:16:28
 */
class acphp {

    protected $config = [];

    /**
     * 构造方法调用项目配置($config.php)
     * @return: 调用数据库配置
     * @author: 阿川 ahuan@achuan.io
     * @Date: 2019-02-24 11:54:40
     */
    public function __construct() {
        $this->config = '$config';
    }
    
    /**
     * 运行程序
     * @return: 自动加载类
     * @author: 阿川 ahuan@achuan.io
     * @Date: 2019-02-24 11:51:17
     */
    public function run() {
        // 自动加载类
        spl_autoload_register(array($this, 'loadClass'));
        $this->setReporting();
        $this->removeMagicQuotes();
        $this->unregisterGlobals();
        $this->setDbConfig();
        $this->route();
    }

    /**
     * 路由处理
     * @return: 获取路径信息->处理路径信息
     * @author: 阿川 ahuan@achuan.io
     * @Date: 2019-02-24 11:50:34
     */
    public function route() {
        // 调用控制器
        $controllerName = $this->config['defaultController'];
        // 调用操作名
        $actionName = $this->config['defaultAction'];
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
        if ($url)  {
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
        if (!class_exists($controller)) {
            exit($controller . '控制器不存在！');
        }
        // 判断操作名
        if (!method_exists($controller, $actionName)) {
            exit($actionName . '方法不存在!');
        }
        // 如果控制器和操作名存在,则实例化控制器, 因为控制器在对象里面
        // 因为还会用到控制器名和操作名，所以实例化的时候把他们俩的名称也传进去
        $dispath = new $controller($controllerName, $actionName);

        // call_user_func_array（返回回调函数的结果，如果出错的话就返回false）
        call_user_func_array(array($dispath, $actionName), $param);
    }
    /**
     * 检测开发环境
     * @return: 设置报告所有错误并开启日志
     * @author: 阿川 ahuan@achuan.io
     * @Date: 2019-02-24 11:55:09
     */
    public function setReporting() {
        if (APP_DEBUG === true) {
            // 设置报告所有PHP错误
            error_reporting(E_ALL);
            // 开启错误报告
            ini_set('display_errors','On');
        } else {
            error_reporting(E_ALL);
            ini_set('display_errors','Off');
            ini_set('log_errors','On');
        }
    }

    /**
     * 删除敏感字符
     * @param {array} $value
     * @return: 返回一个数组
     * @author: 阿川 ahuan@achuan.io
     * @Date: 2019-02-24 11:55:55
     */
    public function stripSlashesDeep($value) {
        // 如果$vlaue为空，则返回一个数
        $value = is_array($value) ? array_map(array($this, 'stripSlashesDeep'), $value) : stripSlashesDeep($value);
        return $value;
    }

    /**
     * 检测敏感字符并删除
     * @return: 返回处理后的字符串
     * @author: 阿川 <achuan@achuan.io>
     * @Time: 2019/02/24 13:38
     */
    public function removeMagicQuotes() {
        // 对取来的数据进行判断，不要被magic_quotes_gpc转义过的字符串使用addslashes()
        if (get_magic_quotes_gpc()) {
            $_GET = isset($_GET) ? $this->stripSlashesDeep($_GET) : '';
            $POST = isset($_POST) ? $this->stripSlashesDeep($_POST) : '';
            $_COOKIE = isset($_COOKIE) ? $this->stripSlashesDeep($_COOKIE) : '';
            $_SESSION = isset($_SESSION) ? $this->stripSlashesDeep($_SESSION) : '';
        }
    }

    /**
     * 检测自定义全局变量并移除，因为 register_globals 已经弃用
     * 如果弃用的 register_globals 指令被设置为on，那么局部变量也将在脚本的全局作用域中可用

      示例：
      $_POST['user'] 也将以 $user 的形势存在，这样写的话会影响代码的其他变量
      参考：http://php.net/manual/zh/faq.using.php#faq.register-globals

     * @return: 删除自定义全局变量
     * @author: 阿川 <achuan@achuan.io>
     * @Time: 2019/02/24 15:29
     */
    public function unregisterGlobals() {
        if (ini_get('register_globals')) {
            $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');

            foreach ($array as $value) {
                foreach ($GLOBALS[$value] as $key => $val) {
                    if ($val === $GLOBALS[$key]) {
                        unset($GLOBALS[$key]);
                    }
                }
            }
        }
    }

    /**
     * 配置数据库信息
     * @return: 数据库信息
     * @author: 阿川 <achuan@achuan.io>
     * @Time: 2019/02/24 16:02
     */
    public function setDbConfig() {
        if ($this->config['db']) {
            define('DB_HOST', $this->config['db']['host']);
            define('DB_NAME', $this->config['db']['dbname']);
            define('DB_USER', $this->config['db']['username']);
            define('DB_PASS', $this->config['db']['password']);
        }
    }

    /**
     * 类的自动加载
     * @param {class} $className
     * @return: 类文件
     * @author: 阿川 <achuan@achuan.io>
     * @Time: 2019/02/24 16:12
     */
    public function loadClass($className) {
        $classMap = $this->classMap();
        if (isset($classMap[$className])) {
            // 包涵内核文件
            $file = $classMap[$className];
            // 判断 $className '\\'
        } elseif (strpos($className,'\\') !== false) {
            // 包涵应用（application目录）文件
            $file = APP-PATH . str_replace('\\', '/', $className) . '.php';
            // 判断一个文件名是否为正常的文件
            if (!is_file($file)) {
                return;
            }
        } else {
            return;
        }
        include $file;
    }

    /**
     * 内核文件命名空间映射关系
     * @return array
     * @return: 键值对（路径）
     * @author: 阿川 <achuan@achuan.io>
     * @Time: 2019/02/24 16:29
     */
    protected function classMap() {
        return [
            'core\base\Controller' => CORE_PATH . '/base/Controller.php',
            'core\base\Model' => CORE_PATH . '/base/Model.php',
            'core\base\View' => CORE_PATH . '/base/View.php',
            'core\db\Pdo' => CORE_PATH . '/db/Pdo.php',
            'core\db\Sql' => CORE_PATH . '/db/Sql.php',
        ];
    }
}

<?php
// +----------------------------------------------------------------------
// | Bootstrap
// +----------------------------------------------------------------------
// | 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用, 调用次序和申明的次序相同
// +----------------------------------------------------------------------
// | 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
// +----------------------------------------------------------------------
// | Author: qh.cao
// +----------------------------------------------------------------------
class Bootstrap extends Yaf\Bootstrap_Abstract 
{
    public function _initConfig() 
    {
        $config = Yaf\Application::app()->getConfig();
		Yaf\Registry::set('config', $config);
	}

    public function _initLoader() 
    {
        Yaf\Loader::import(APPLICATION_PATH . "/vendor/autoload.php");
    }

    public function _initSystem(Yaf\Dispatcher $dispatcher) 
    {
        $dispatcher->disableView();
        $dispatcher->returnResponse(true);
        error_reporting(0);
    }

	public function _initPlugin(Yaf\Dispatcher $dispatcher)
    {

	}

	public function _initRoute(Yaf\Dispatcher $dispatcher)
    {
        $routes = require APPLICATION_PATH . "/route/route.php";
        $dispatcher = \FastRoute\simpleDispatcher(
            function(\FastRoute\RouteCollector $handler) use ($routes) {
                foreach ($routes as $route) {
                    $handler->addRoute(strtoupper($route[0]), $route[1], $route[2]);
                }
            }
        );

        Yaf\Registry::set('routeDispatcher', $dispatcher);
	}

    public function _initDbAdapter() 
    {
        $capsule  = new \Illuminate\Database\Capsule\Manager;
        $capsule->addConnection(Config::get('database', 'database'));
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
        class_alias('\Illuminate\Database\Capsule\Manager', 'DB');
    }
}

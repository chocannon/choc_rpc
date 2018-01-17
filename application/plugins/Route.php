<?php
// +----------------------------------------------------------------------
// | 将fastroute路由解析成yaf的sample模式
// +----------------------------------------------------------------------
// | Author: chocannon
// +----------------------------------------------------------------------
class RoutePlugin extends Yaf\Plugin_Abstract 
{
	public function routerStartup(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response) 
	{
		$httpMethod = $request->getMethod();
		$uri = $request->getRequestUri();
		
		$dispatcher = Yaf\Registry::get('routeDispatcher');
		if (false !== $pos = strpos($uri, '?')) {
		    $uri = substr($uri, 0, $pos);
		}
		$uri = rawurldecode($uri);
		$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
		switch ($routeInfo[0]) {
    		case FastRoute\Dispatcher::NOT_FOUND:
    			throw new Exception("NOT_FOUND", 1);
        		break;
    		case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        		$allowedMethods = $routeInfo[1];
        		throw new Exception("{$allowedMethods}", 1);
        		break;
    		case FastRoute\Dispatcher::FOUND:
        		$handler = $routeInfo[1];
        		$vars = $routeInfo[2];
        		echo "<pre>";
        		var_dump($routeInfo);
        		exit();
        	break;
        }
	}

	public function routerShutdown(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response) 
	{
	}

	public function dispatchLoopStartup(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response) 
	{
	}

	public function preDispatch(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response) 
	{
	}

	public function postDispatch(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response) 
	{
	}

	public function dispatchLoopShutdown(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response) 
	{
	}
}

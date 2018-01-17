<?php
// +----------------------------------------------------------------------
// | Http服务
// +----------------------------------------------------------------------
// | Author: chocannon
// +----------------------------------------------------------------------
namespace Service;

use Coral\Server\BaseServer;
use App\Exceptions\ParamException;
use App\Exceptions\RouteException;

class HttpServer extends BaseServer 
{
    protected $application = null;
    protected $processName = 'HttpServer';
    protected $serverType  = 'Http';

    public function onWorkerStart(\Swoole\Server $server, int $workerId) 
    {
        parent::onWorkerStart($server, $workerId);
        if(function_exists('opcache_reset')){
            opcache_reset();
        }
        $this->application = new \Yaf\Application(APPLICATION_PATH . "/conf/application.ini");
        ob_start();
        $this->application->bootstrap()->run();
        ob_end_clean();
    }


    public function onRequest(\Swoole\Http\Request $request, \Swoole\Http\Response $response)
    {
        \Yaf\Registry::set('SERVER', $request->server);
        \Yaf\Registry::set('HEADER', $request->header);

        $queryString = isset($request->server['path_info']) ? rawurldecode($request->server['path_info']) : '/';
        $queryMethod = isset($request->server['request_method']) ? $request->server['request_method'] : 'GET';
        if (false !== $pos = strpos($queryString, '?')) {
            $queryString = substr($uri, 0, $pos);
        }
        $dispatcher = \Yaf\Registry::get('routeDispatcher');
        $routeInfo  = $dispatcher->dispatch($queryMethod, $queryString);

        $request = new \Yaf\Request\Http($routeInfo[1]);
        array_walk($routeInfo[2], function ($val, $key) use ($request) {
            $request->setParam($key, $val);
        });

        try {
            if (\FastRoute\Dispatcher::FOUND !== $routeInfo[0]) {
                throw new \App\Exceptions\RouteException('API Unavailable');
            }
            ob_start();
            $this->application->getDispatcher()->catchException(true)->dispatch($request);
            $ret = ob_get_contents();
        } catch (\Exception $e) {
            if ($e instanceof ParamException || $e instanceof RouteException) {
                $ret = json_encode([
                    'code'    => $e->getCode(),
                    'message' => $e->getMessage(),
                    'result'  => []
                ], JSON_UNESCAPED_UNICODE);
            } else {
                \Logger::error("code:{code}\r\nmessage:{message}\r\ntrace:{trace}", [
                    'code'    => $e->getCode(),
                    'message' => $e->getMessage(),
                    'trace'   => $e->getTrace(),
                ]);
                $ret = json_encode([
                    'code'    => '500',
                    'message' => 'System Error',
                    'result'  => []
                ], JSON_UNESCAPED_UNICODE);
            }
        }
        ob_end_clean();      

        $response->header('Server', 'Somur-Server');
        $response->header('Content-Type', 'application/json');
        $response->end($ret);
    }
}
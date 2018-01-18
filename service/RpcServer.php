<?php
// +----------------------------------------------------------------------
// | Rpc服务
// +----------------------------------------------------------------------
// | Author: chocannon
// +----------------------------------------------------------------------
namespace Service;

use Output;
use Helper;
use Exception;
use Coral\Utility\Package;
use Coral\Server\BaseServer;
use Yaf\Exception\LoadFailed;
use App\Exceptions\LogicException;
use App\Exceptions\ServiceException;

class RpcServer extends BaseServer 
{
    protected $application = null;
    protected $processName = 'RpcServer';
    protected $port        = 9502;
    protected $serverType  = 'Tcp';

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

    public function onReceive(\Swoole\Server $serv, int $fd, int $reactorId, string $data) 
    {
        $receive = Package::decode($data);
        ob_start();
        try {
            $this->checkAuthorize($receive);
            $request = new \Yaf\Request\Http($receive['url']);
            array_walk($receive['params'], function ($val, $key) use ($request) {
                $request->setParam($key, $val);
            });
            $this->application->getDispatcher()->dispatch($request);
            $ret = ob_get_contents();
        } catch (Exception $e) {
            if ($e instanceof ServiceException 
                || $e instanceof LogicException 
                || $e instanceof LoadFailed) {
                $ret = Output::json($e->getCode(), $e->getMessage());
            }else{
                \Logger::error("receive:{receive}\r\ncode:{code}\r\nmessage:{message}\r\ntrace:{trace}", [
                    'receive' => $receive,
                    'code'    => $e->getCode(),
                    'message' => $e->getMessage(),
                    'trace'   => $e->getTrace(),
                ]);
                $ret = Output::json(500, 'System Error');
            }
        }
        ob_end_clean();
        $serv->send($fd, Package::encode($ret));
    }


    protected function checkAuthorize($data)
    {
        if (!isset($data['auth']['appKey']) || !isset($data['auth']['appSecret'])) {
            throw new ServiceException('Authorize Parameter Missing!');
        }
       
        if ($data['auth']['appSecret'] !== Helper::hash([
            'url'    => $data['url'],
            'params' => $data['params'],
        ], $data['auth']['appKey'])) {
            throw new ServiceException('Authorize Failure!');
        }
        return true;
    }
}
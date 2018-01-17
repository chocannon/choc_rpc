<?php
// +----------------------------------------------------------------------
// | 调用RPC客户端封装
// +----------------------------------------------------------------------
// | Author: qh.cao
// +----------------------------------------------------------------------
use Service\RpcClient;
use App\Exceptions\RemoteException;

class Remote
{
    protected static $instance = null;

    public static function call(string $url, array $params)
    {
        try {
            if (null === self::$instance) {
                self::$instance = new RpcClient();
                self::$instance->setConfig(Config::get('server/client'));
            }
            $data = json_encode([
                'url' => $url,
                'params' => $params,
            ]);
            return self::$instance->exec($data);
        } catch (\Exception $e) {
            Logger::error("send:{send}\r\ncode:{code}\r\nmessage:{message}\r\ntrace:{trace}", [
                'send'    => $data,
                'code'    => $e->getCode(),
                'message' => $e->getMessage(),
                'trace'   => $e->getTrace(),
            ]);
            throw new RemoteException('Getting Remote Data Failure!');
        } 
    }
}
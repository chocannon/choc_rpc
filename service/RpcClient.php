<?php
// +----------------------------------------------------------------------
// | Rpc客户端
// +----------------------------------------------------------------------
// | Author: chocannon
// +----------------------------------------------------------------------
namespace Service;

use Coral\Utility\Package;
use Coral\Client\BaseClient;

class RpcClient extends BaseClient 
{
    protected $client   = null;
    protected $host     = '0.0.0.0';
    protected $port     = 9502;
    protected $syncType = SWOOLE_SOCK_SYNC;
}
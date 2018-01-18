<?php
abstract class Controller extends Yaf\Controller_Abstract 
{
    public function init() 
    {
        // list($state, $msg) = Validation::check($this->getRequest());
        // if (false === $state) {
        //     throw new App\Exceptions\LogicException($msg);
        // }
    }

    
    /**
     * 响应客户端
     * @param  string  $data 响应数组
     * @return [type]       [description]
     */
    protected function response(string $data) 
    {
        $response = $this->getResponse();
        $response->setBody($data);
        $response->response();
    }
}
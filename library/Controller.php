<?php
abstract class Controller extends Yaf\Controller_Abstract 
{
    public function init() 
    {
        // list($state, $msg) = Validation::check($this->getRequest());
        // if (false === $state) {
        //     throw new App\Exceptions\ParamException($msg);
        // }
    }

    
    /**
     * 响应客户端
     * @param  array  $data 响应数组
     * @return [type]       [description]
     */
    protected function response(array $data) 
    {
        $response = $this->getResponse();
        $response->setBody(json_encode($data, JSON_UNESCAPED_UNICODE));
        $response->response();
    }
}
<?php
// +----------------------------------------------------------------------
// | 格式化输出封装
// +----------------------------------------------------------------------
// | Author: qh.cao
// +----------------------------------------------------------------------
class Output
{
    protected $code    = 200;
    protected $message = 'Successful';
    protected $time    = 0;  
    protected $data    = [];


    function __toString()
    {
        $output = [
            'code'    => $this->code,
            'message' => $this->message,
            'time'    => time(),
            'data'    => $this->data
        ];
        return json_encode($output, JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT);
    }
}
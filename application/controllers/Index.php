<?php

class IndexController extends Controller 
{
    public function indexAction() 
    {
        // return ['hello world'];
        return $this->response(['hello world']);
        // throw new App\Exceptions\ParamException('lalala');
    }


    public function testAction()
    {   
        $ret = Remote::call('index/index/aaa', ['title' => '3333344444']);
        return $this->response($ret);
    }

    public function homeAction()
    {
        return $this->response(['a' => 'b']);
    }
}
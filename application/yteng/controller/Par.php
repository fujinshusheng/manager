<?php 
namespace app\yteng\controller;
use think\Controller;
class Par extends Controller{
	//需要判断登陆的都继承此父类
	protected function initialize()
    {
        islogin();
    }
}

 ?>
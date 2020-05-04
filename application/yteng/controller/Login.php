<?php 
namespace app\yteng\controller;
use think\Controller;
use think\captcha\Captcha;
use think\Request;
use app\yteng\model\Admins;
class Login extends Controller{
	public function index(){
		 return $this->fetch();
	}
	public function check(Request $request){
		 $admins = new Admins();
		 $admins->checkAdminLogin($request);
	}
	public function pl(){
		$url = url("/yteng/login");
		$this->success("请登陆",$url);
	}
	public function logout(){
		session('username', null,'yteng');
		session('userid', null,'yteng');
		$url = url("/yteng/login");
		$this->success("安全退出成功",$url);
	}
	public function verify()
    {
        $config =    [
		    // 验证码字体大小
		    'fontSize'    =>   22,    
		    // 验证码位数
		    'length'      =>    4,   
		    // 关闭验证码杂点
		    'useImgBg'    => false,
		    'reset'       => false, //验证成功后是否重置
		];
		$captcha = new Captcha($config);
        return $captcha->entry();    
    }
}

 ?>
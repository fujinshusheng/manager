<?php
namespace app\yteng\model;
use think\Model;
use app\yteng\model\Adminlogs;
class Admins extends Model
{
	//判断用户登陆
	public function checkAdminLogin($request){
		    $info = ["mes"=>"信息错误","status"=>0];
			if($request->isAjax()){
			 	$data = $request->post();
			 	if(!captcha_check($data["vercode"])){
				 	$info["mes"] = "验证码错误!";
				 	ajson($info);
				};
				if($data["username"] && $data["password"]){
					$map["username"] = $data["username"];
					$map["userpwd"]  = mypwd($data["password"]);
					$result = $this->where($map)->find();
					if(!$result){
						$info["mes"] = "账号或密码错误!";
						ajson($info);
					}
					else{
						$info = ["mes"=>"登陆成功","status"=>1];
						session("username",$data["username"],"yteng");
						session("userid",$result->id,"yteng");
						session("purview",$result->purview,"yteng");
						Adminlogs::create([
							"userid"  => $result->id,
							"logtime" => time(),
							"logip"   => $request->ip()
							]);
						ajson($info);
					}
				}
				else{
					$info["mes"] = "账号或密码不能为空!";
					ajson($info);
				}
		 }
		 else{
		 	ajson($info);
		 }
	}
}
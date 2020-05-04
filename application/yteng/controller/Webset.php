<?php 
namespace app\yteng\controller;
use app\yteng\controller\Par;
use app\facade\Files;
class Webset extends Par{
	public $path;
	protected function initialize()
    {
        $this->path = env("RUNTIME_PATH") . "set" . "/webset.php";
    }
	public function index(){
		$data["info"] = json_decode(Files::get_json_file($this->path),true);
		$this->assign($data);
		return $this->fetch();
	}
	public function save(){
		$data = $this->request->post();
		Files::set_json_file($this->path, json_encode($data));
		$info = ["status"=>1,"mes"=>"设置成功"];
		ajson($info);
	}
}

 ?>
<?php 
namespace app\yteng\controller;
use app\yteng\controller\Par;
use think\Db;
class Getpath extends Par{
	public function index(){
        $table = "yt_column";
        $result = Db::table($table)->select();
        foreach ($result as $v) {
        	$id = $v["id"];
        	$path = getParentPath($id,$table) . ",$id,";
        	Db::table($table)->where("id","=",$id)->update(["sortpath"=>$path]);
        	echo $path . "<br/>";
        	//die();
        }
	}
}

 ?>
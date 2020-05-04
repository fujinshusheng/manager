<?php 
namespace app\yteng\controller;
use app\yteng\controller\Par;
use app\yteng\model\Photos;
class Filesdo extends Par{
	//上传普通图片
	public function upload(){
    	$file = request()->file('image');
    	$info = $file->move(config("app.fpath"));
    	$names = "";
	    if($info){
	        $names = $info->getSaveName();
	        ajson(["code"=>1,"filename"=>str_replace("\\","/",$names)]);
	    }
	    else{
	    	ajson(["code"=>0]);
	    }
	}
	//删除提交的文件
	public function del(){
		$files = request()->post("files");
		if($files){
			   if(file_exists(config("app.fpath")."/".$files)){
			   		unlink(config("app.fpath")."/".$files);
			   }
			   Photos::where('pic','=',$files)->delete();
	        	
	    }
	}
	//接收大文件分开块状文件
	public function getBlockFile(){
		set_time_limit(0);
		// 建立临时目录存放文件-以MD5为唯一标识
		$post = request()->post();
		$dir = config("app.fpath") ."/".  $post["md5"];
	    if (!file_exists($dir)) {
	        mkdir($dir,0777,true);
	    }
	    // 移动每一块文件到 唯一 标识文件夹下
	    if($post["size"] > 1 * 1024 * 1024){
	    	 move_uploaded_file($_FILES["file"]["tmp_name"], $dir.'/'.$post["chunk"]);
	    }
	    else{
	    	//文件 小于 设置的 chunk 大小 时,没有chunk
	    	$file = $_FILES["file"];
	    	move_uploaded_file($_FILES["file"]["tmp_name"], $dir.'/'.$file["name"]);
	    }
	   
	}
	//合成分块上传的文件
	public function merge(){
		   set_time_limit(0);
			// 接收相关数据
		    $post = $_POST;
		    // 找出分片文件
		    $dir = config("app.fpath") ."/".  $post['md5'];
		    // 获取分片文件内容
		    $block_info = scandir($dir);
		    // 除去无用文件
		    foreach ($block_info as $key => $block) {
		        if ($block == '.' || $block == '..') unset($block_info[$key]);
		    }
		    // 数组按照正常规则排序
		    natsort($block_info);
		    // 定义保存文件
		    $save_path = date("Ymd").'/';
			$target_path  = config("app.fpath") ."/". $save_path;
		    if(!is_dir($target_path)) mkdirs($target_path);
       		$new_file_name = date('Ymdgis').rand().".".getSuffix($post['fileName']);
		    $save_file = $target_path . $new_file_name;
		    $save_name = $save_path . $new_file_name;
		    // 没有？建立
		    if (!file_exists($save_file)) fopen($save_file, "w");
		    // 开始写入
		    $out = @fopen($save_file, "wb");
		    // 增加文件锁
		    if (flock($out, LOCK_EX)) {
		        foreach ($block_info as $b) {
		            // 读取文件
		            if (!$in = @fopen($dir.'/'.$b, "rb")) {
		                break;
		            }

		            // 写入文件
		            while ($buff = fread($in, 4096)) {
		                fwrite($out, $buff);
		            }

		            @fclose($in);
		            @unlink($dir.'/'.$b);
		        }
		        flock($out, LOCK_UN);
		    }
		    @fclose($out);
		    @rmdir($dir);
		    ajson(["save_name" => $save_name, "status" => 1]);
	}
}
?>

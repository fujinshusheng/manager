<?php
namespace app\common;
//文件操作管理类
class Files{
	
	//将json格式的字符串存到指定的php文件中 
	function set_json_file($filename, $content)
	{
	    $fp = fopen($filename, "w");
	    fwrite($fp, "<?php exit();?>" . $content);
	    fclose($fp);
	}

	//获取php文件的内容
	function get_json_file($filename)
	{
	    return trim(substr(file_get_contents($filename), 15));
	}
}

 ?>
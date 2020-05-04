<?php
//定义加密函数文件
function mypwd($pwd){
	$md1 = sha1($pwd);
	$md2 = sha1(substr($md1,2,21));
	$md3 = md5($md2);
	return $md3;
}
//ajax输出json格式 信息
function ajson($data){
	echo json_encode($data);
	exit();
}
//判断是否登陆
function islogin(){
	if(!session('username', '', 'yteng')){
		$url = url("/yteng/login/pl");
		echo "<script>parent.location.href='$url';</script>";
		exit();
	}
}
/**
  * 获得类别路径名称
*/
if (!function_exists('get_sortpath_names')) {
    
    function get_sortpath_names($path,$table)
    {
       $str = "";
       $arr = explode(",",$path);
       if($arr)
       {
         $i = 1;
          foreach($arr as $id)
          {
                $sortname = \think\Db::name($table)->where("id",$id)->value("sortname");
                if($i==1){$str .= $sortname;}
                else{$str .= " -> " . $sortname;}
                $i++;
          }
          
       }
       return $str;
    }
}

//生成唯一数;
function uuid() {  
  return md5(time() . mt_rand(1,1000000));
}

// 返回 get请求的 url 链接
function getQuery(){
    return http_build_query(array_splice($_GET,1));
}

/**
 * 批量创建目录
 *
 * @param string $path 需要创建的目录
 * @param int $mode
 */
 function mkdirs($path, $mode = 0777)
 { 
   $path = substr($path,-1,1)!='/'?$path.'/':$path;
   $dirs = explode('/',$path); 
   $subamount = FALSE=== strrpos($path, ".")?0:1;
   for ($c=0;$c < count($dirs) - $subamount; $c++) 
   { 
     $thispath=""; 
     for ($cc=0; $cc <= $c; $cc++) 
     { 
      $thispath.=$dirs[$cc].'/'; 
     } 
      if (!file_exists($thispath))@mkdir($thispath,$mode); 
   } 
 }

 //PHP取得文件后缀
function getSuffix($file_name)
{
  $extend =explode("." , $file_name);
  $va=count($extend)-1;
  return $extend[$va];
}

function formatFileSize($size){
      $fileSize = 0;
      if($size/1024>1024){
          $len = strlen($size/1024/1024);
          $fileSize = sprintf("%.2f",$len)."MB";
      }else if($size/1024/1024>1024){
          $len = $size/1024/1024;
          $fileSize = sprintf("%.2f",$len)."GB";
      }else{
          $len = $size/1024;
          $fileSize = sprintf("%.2f",$len)."KB";
      }
      return $fileSize;
  }
//得到文件所有的父级
function getParentPath($id,$table,&$arr=[]){
    $pid = \think\Db::table($table)->where("id","=",$id)->value("parentid");
    if($pid){
         $arr[] = $pid;
        // dump($arr);
         getParentPath($pid,$table,$arr);
    }
    if($arr){
       return "0,".implode(",",array_reverse($arr));
    }
    else{
       return "0";
    }
   
    
}

<?php 
namespace app\yteng\controller;
use app\yteng\controller\Par;
use sort\Sortclass;
use think\Db;
class Main extends Par{
	public function index(){

		$data["strMenu"] = $this->getMenu();
		$this->assign($data);
		return $this->fetch();
	}
	public function home(){
		return $this->fetch();
	}
	public function getMenu(){
		$strMenu = "";
		//查询一级栏目
		$map[] = ["parentid",'=',0];
		$purview = session('purview','','yteng');
		if(session('userid','','yteng') != 1){
			$map[] = ["id",'in',$purview];
		}
		$rs0 = Db::name("column")->where($map)->order('orders asc,id')->select();
		foreach ($rs0 as $k=>$v0) {
			if($k == 0){
				$strMenu .= "<li class='layui-nav-item layui-nav-itemed'>"; //默认打开第一项
			}
			else{
				$strMenu .= "<li class='layui-nav-item'>"; //非第一项不设置默认打开
			}
			//查找第二级有没有子类 开始
			$m = [];
			$m[] = ["parentid",'=',$v0["id"]];
			$purview = session('purview','','yteng');
			if(session('userid','','yteng') != 1){
				$m[] = ["id",'in',$purview];
			}
			$rs1 = Db::name("column")->where($m)->order('orders asc,id')->select();
			//查找第二级有没有子类 结束
			$sortname = $v0["sortname"]; //一级类别名称
			if($rs1){
				//有二级
				$strMenu .= " <a href='javascript:;' lay-tips='$sortname' lay-direction='2'>
				                <i class='layui-icon layui-icon-list'></i>
				                <cite>$sortname</cite>
				              </a>";
			    $strMenu .= "<dl class='layui-nav-child'>";
			    foreach ($rs1 as $k1=>$v1) {
			    	$strMenu .= "<dd>";
			    	//查找第三级有没有子类 开始
				    $n = [];
					$n[] = ["parentid",'=',$v1["id"]];
					$purview = session('purview','','yteng');
					if(session('userid','','yteng') != 1){
						$n[] = ["id",'in',$purview];
					}
					$rs2 = Db::name("column")->where($n)->order('orders asc,id')->select();
					//查找第三级有没有子类 结束
					if($rs2){
						$strMenu .= "<a href='javascript:;'>".$v1["sortname"]."</a>";
						$strMenu .= "<dl class='layui-nav-child'>";
						foreach ($rs2 as $k2 => $v2) {
							 $url = url($v2["urls"]);
							 $strMenu .= "<dd>";
							 $strMenu .= "<a lay-href='$url'>".$v2["sortname"]."</a>";
							 $strMenu .= "</dd>";
						}
						$strMenu .= "</dl>";
					}
					else{
						$url = url($v1["urls"]);
						$strMenu .= "<a lay-href='$url'>".$v1["sortname"]."</a>";
					}
				    $strMenu .= "</dd>";
			    }
			    $strMenu .= "</dl>";

			}
			else{
				//只有一级，没有子类
				$url = url($v0["urls"]);
                $strMenu .= " <a href='javascript:;' lay-href='$url' lay-tips='$sortname' lay-direction='2'>
				                <i class='layui-icon layui-icon-read'></i>
				                <cite>$sortname</cite>
				              </a>";
			}
			 $strMenu .= "</li>";
		}
		return $strMenu;
	}
}

 ?>
<?php 
namespace app\yteng\controller;
use app\yteng\controller\Par;
use app\yteng\model\Admins;
use think\Db;
class Administrator extends Par{
	protected $userid;
	protected function initialize()
    {
        $this->userid = session("userid","","yteng");
    } 
	public function index(){
		$data["list"] = Admins::where("id","<>",1)->all();
		$this->assign($data);
		return $this->fetch();
	}
	//得到编辑信息详细
	public function edit(){
		$id = $this->request->post("id");
		$action = $this->request->post("action");
		if($id){
			//编辑时， 需要返回当前类别的所有信息
			$data = Admins::where('id',$id)->find();
			$data["purview"] = $this->get_purview($data["purview"]);
		}
		else{
			 $data["purview"] = $this->get_purview();
		}
		ajson($data);
	}

	//个人基本信息
	public function mybase(){
		$data = Admins::where('id',$this->userid)->find();
		//$data["purview"] = $this->get_purview($data["purview"]);
		$this->assign("row",$data);
		return $this->fetch();
	}

	public function mybase_save(){
		$data = $this->request->post();
		//dump($data);
		//die();
		/*$data["purview"]  = $this->request->post("purview/a","");
        if($data["purview"]){
            $data["purview"] = implode(",", $data["purview"]);
        }*/
       
		//单独修改时， 用户名已经设置不能修改， 不能判断是否重复
		Admins::update($data);
		ajson(["status"=>1,"mes"=>"修改成功"]);
	}

	//重新设置密码
	public function editpwd(){
		 return $this->fetch();
	}
	public function editpwd_save(){
		$data = ["id"=>$this->userid,"userpwd"=>mypwd($this->request->post("userpwd"))];
		Admins::update($data);
		ajson(["status"=>1,"mes"=>"修改成功"]);
	}

	public function save(){
		$data = $this->request->post();
		$data["addtime"] = time();
		$data["purview"]  = $this->request->post("purview/a","");
        if($data["purview"]){
            $data["purview"] = implode(",", $data["purview"]);
        }
       
		//查询是否已经存在的基础条件
		$map[] = ["username",'=',$data["username"]];
		if($data["id"]){
			//修改
			$map[] = ["id","<>",$data["id"]];
			$count = Admins::where($map)->count();
			if($count){
				ajson(["status"=>0,"mes"=>"用户名已经存在"]);
			}
			else{
				unset($data["userpwd"]); //修改用户信息时不能修改密码
				Admins::update($data);
				$info = $this->getSlide();
				ajson(["status"=>1,"mes"=>"修改成功","info"=>$info]);
			}
		}
		else{
			//添加
			$count = Admins::where($map)->count();
			if($count){
				ajson(["status"=>0,"mes"=>"用户名已经存在"]);
			}
			else{
				unset($data["id"]); //添加时没有id
				$data["userpwd"] = mypwd($data["userpwd"]);
			    Admins::create($data);
				$info = $this->getSlide();
				ajson(["status"=>1,"mes"=>"添加成功","info"=>$info]);
			}
		}
	}
	//初始化密码为 123456
	public function initpwd(){
		$data = $this->request->post();
		$data["userpwd"] = mypwd("123456");
		Admins::update($data);
		ajson(["status"=>1,"mes"=>"初始化密码为：123456 成功"]);
	}

	//删除类别 begin
	public function del(){
		$id = $this->request->post("id");
		$count = Admins::destroy($id);
		if($count){
			ajson(["status"=>1,"mes"=>"删除成功"]);
		}
		else{
			ajson(["status"=>0,"mes"=>"删除失败"]);
		}
	}
	//获得权限
    protected function get_power($purview="")  
    {
       
        return $lists;
    }
	//获得权限
    public function get_purview($purview="")  
    {
        $lists="";$css1="";$css2="";
        if($purview) $purview = explode(",", $purview);
        $data_search="";
        $map["parentid"] = 0;
        $result = Db::name("column")->where($map)->order('orders asc,id')->select();
       
        if(!empty($result)){
            foreach($result as $rs){
               $lists.="<tr>";
               $css1="";
               if(($purview)){
                   if(in_array($rs["id"],$purview)) $css1="checked";
               }
               $lists.="<td class='first'><input type='checkbox' lay-filter='pcheck' lay-skin='primary' name='purview[]'  value='".$rs["id"]."' $css1 title='".$rs["sortname"]."'/></td>";
               $mp["parentid"] = $rs["id"];
               $rr = Db::name("column")->where($mp)->order('orders asc,id')->select();
               $lists.="<td>";
               if(!empty($rr)){
                   foreach($rr as $r){
                       $css2="";
		               if(($purview)){
		                   if(in_array($r["id"],$purview)) $css2="checked";
		               }
                      //判断第三级操作
                      $m["parentid"] = $r["id"];
                      $r3 = Db::name("column")->where($m)->order('orders asc,id')->select();
                      if($r3){
                      	 $lists.="<div>
                      	 <input type='checkbox' lay-skin='primary' lay-filter='ccheck1' name='purview[]'  value='".$r["id"]."' $css2 title='".$r["sortname"]."'/>";
                      	  $lists.="<div class='layui-input-block'>";
                      	  foreach ($r3 as $d3) {
                      	  		 $css3="";
					               if(($purview)){
					                   if(in_array($d3["id"],$purview)) $css3="checked";
					               }
		                        $lists.="<input type='checkbox' lay-skin='primary' lay-filter='c3check' name='purview[]'  value='".$d3["id"]."' $css3 title='".$d3["sortname"]."'/>&nbsp;";
                      	  }
                      	  $lists.="</div></div>";
                      }
                      else{
                      	  $lists.="<input type='checkbox' lay-skin='primary' lay-filter='ccheck2' name='purview[]'  value='".$r["id"]."' $css2 title='".$r["sortname"]."'/>&nbsp;";
                      }
                      
                   }
               }
               $lists.="</td>";
               $lists.='</tr>';
            }
        }
        
        return $lists;
    }
    //得到更新视图页面
	protected function getSlide(){
		$data["list"] = Admins::where("id","<>",1)->all();
		$this->assign($data);
		$info = $this->fetch("slide");
		return $info;
	}
}
 ?>

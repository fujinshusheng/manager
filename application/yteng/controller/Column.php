<?php 
namespace app\yteng\controller;
use app\yteng\controller\Par;
use sort\Sortclass;
use think\Db;
use app\yteng\model\Column as Col;
class Column extends Par{
	protected $table = "column";
	public function index(){
		$sort = new Sortclass();
        $table = config('database.prefix') . $this->table;
        $results = $sort->get_Children_Class($parentid=0,$table);
        $this->assign("results",$results);
        return $this -> fetch();
	}

	//得到编辑信息详细
	public function edit(Sortclass $sort){
		$id = $this->request->post("id");
		$action = $this->request->post("action");
		$table = config('database.prefix') . $this->table;
		if($action == "add"){
			//添加时，只返回父类就可以
			$data["sortclass"] = $sort->select_trees(0,$table,$id);
		}
		else{
			//编辑时， 需要返回当前类别的所有信息
			$data = Col::where('id',$id)->find();
			$data["sortclass"] = $sort->select_trees(0,$table,$data["parentid"]);
		}
		ajson($data);
	}

	//类别保存
	public function save(){
		$data = $this->request->post();
		$data["parentid"] = $parentid = $data["sortid"] ? $data["sortid"] : 0; //父级没有选择为 顶级

		//设置 sortpath 及 level
		if($parentid){
			if($data["id"] == $parentid){
				ajson(["status"=>0,"mes"=>"父目录不能是自己"]);
			}
			$row = Col::get($parentid);
			$data["level"] = $row->level + 1;
			$data["sortpath"] = $row->sortpath;
		}
		else{
			//一级类别添加
		 	$data["level"] = 0;	//层级为0;
			$data["sortpath"] = "0,";
		}
		//查询是否已经存在的基础条件
		$map[] = ["parentid","=",$data["parentid"]];
		$map[] = ["sortname",'=',$data["sortname"]];
		//dump($data);
		if($data["id"]){
			//修改栏目
			$map[] = ["id","<>",$data["id"]];
			$count = Col::where($map)->count();
			if($count){
				ajson(["status"=>0,"mes"=>"同栏目下名称不能重复"]);
			}
			else{
				$data["sortpath"] = $data["sortpath"] . $data["id"] . ",";
				Col::update($data);
				$info = $this->getSlide();
				ajson(["status"=>1,"mes"=>"修改成功","info"=>$info]);
			}
		}
		else{
			//添加
			$count = Col::where($map)->count();
			if($count){
				ajson(["status"=>0,"mes"=>"同栏目下名称不能重复"]);
			}
			else{
				unset($data["id"]);
				$col = Col::create($data);
				$sortpath = $data["sortpath"] . $col->id . ",";
				Col::where('id', $col->id)->update(['sortpath' => $sortpath]);
				$info = $this->getSlide();
				ajson(["status"=>1,"mes"=>"添加成功","info"=>$info]);
			}
		}
	}
	//得到更新视图页面
	protected function getSlide(){
		$sort = new Sortclass();
		$table = config('database.prefix') . $this->table;
		$results = $sort->get_Children_Class($parentid=0,$table);
		$this->assign("results",$results);
		$info = $this->fetch("slide");
		return $info;
	}

	//修改排序
	public function saveorders(){
		$count = Db::name($this->table)->update($this->request->post());
		ajson(["status"=>1,"mes"=>"修改成功"]);
	}

	//删除类别 begin
	public function del(){
		$id = $this->request->post("id");
		$count = Col::where('parentid',$id)->count();
		if($count){
			ajson(["status"=>0,"mes"=>"有子类，不能删除!"]);
		}
		$count = Col::destroy($id);
		if($count){
			ajson(["status"=>1,"mes"=>"删除成功"]);
		}
		else{
			ajson(["status"=>0,"mes"=>"删除失败"]);
		}
	}

}

 ?>
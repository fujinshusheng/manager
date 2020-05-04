<?php 
namespace app\yteng\controller;
use app\yteng\controller\Par;
use app\yteng\model\Links;
use sort\Sortclass;
use think\Db;
class Linksdo extends Par{
	public function index(Sortclass $sort){
		$data = $this->getList();
		$data["sortclass"] = $sort->select_trees(2,config('database.prefix')."sortclass",input('get.sortid'));
		$this->assign($data);
		return $this->fetch();
	}
	public function add(Sortclass $sort){
       	$this->assign("sortclass",$sort->select_trees(2,config('database.prefix')."sortclass",1));
		return $this->fetch();
	}
	//得到编辑信息详细
	public function edit(Sortclass $sort ,$id=0){
		if($id){
			//编辑时， 需要返回当前类别的所有信息
			$data["row"] = Links::where('id',$id)->find();
			$data["sortclass"] = $sort->select_trees(2,config('database.prefix')."sortclass",$data["row"]["sortid"]);
			$this->assign($data);
			return $this->fetch();
		}
	}

	//添加编辑保存
	public function save(){
		$data = $this->request->post();
		$id  = $this->request->post("id");
        $data["sortpath"] = Db::name("sortclass")->where("id",$data["sortid"])->value("sortpath");
		if($id){
			//修改
			Links::update($data);
			ajson(["status"=>1,"mes"=>"修改成功"]);
		}
		else{
			//添加
			$data["addtime"] = time();
		    Links::create($data);
			ajson(["status"=>1,"mes"=>"添加成功"]);
			}
	}
	

	//删除类别 begin
	public function del(){
		$ids = $id = $this->request->post("id");
		if(is_array($id)){
			$ids = implode(",",$ids);
		}
		$result = Links::where("id",'in',$ids)->field('pic')->select();
		foreach ($result as $v) {
			if($v["pic"]){
				 if(file_exists(config("app.fpath")."/".$v["pic"])){
			   		unlink(config("app.fpath")."/".$v["pic"]);
			   }
			}
		}
		$count = Links::destroy($id);
		if($count){
			$data = $this->getList();
			ajson(["status"=>1,"mes"=>"删除成功","info"=>$data["info"]]);
		}
		else{
			ajson(["status"=>0,"mes"=>"删除失败"]);
		}
	}
    //得到视图列表页面信息
	public function getList(){
		
		 //搜索条件的建立 begin
        $title = $this->request->get("title","");
        $sortid  = $this->request->get("sortid",0);
        $map[] = ['isdel','=',1]; //建立查询条件
        $query = []; //url额外参数
        if($title)
        {
        	$map[]  = ['title','like', "%$title%"];
        	$query["title"] = $title;
        }
        if($sortid)
        {
        	$sortpath = Db::name("sortclass")->where("id",$sortid)->value("sortpath");
        	if($sortpath)
        	{
        		$map[]  = ['sortpath','like', $sortpath."%"];
        	}
        	$query["sortid"] = $sortid;
        }
        //搜索条件的建立 end
		$data["list"] = Links::where($map)->order('id', 'desc')->paginate(10,false,[
		    'query'     => $query,
		    'path'      => url("yteng/linksdo/index")
		]);
		
		$this->assign($data);
		$data["info"] = $this->fetch("list");

		return $data;
	}

	 public function getRemark(){
		$remark = Db::name("sortclass")->where("id",$_POST['classid'])->value("remark");
		echo $remark;
	}

	//修改排序
	public function saveorders(){
		Links::update($this->request->post());
		ajson(["status"=>1,"mes"=>"修改成功"]);
		
	}
}
 ?>

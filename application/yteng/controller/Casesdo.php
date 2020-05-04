<?php 
namespace app\yteng\controller;
use app\yteng\controller\Par;
use app\yteng\model\Cases;
use app\yteng\model\Photos;
use sort\Sortclass;
use think\Db;
class Casesdo extends Par{
	public function index(Sortclass $sort){
		$data = $this->getList();
		$data["sortclass"] = $sort->select_trees(39,config('database.prefix')."sortclass",input('get.sortid'));
		$this->assign($data);
		return $this->fetch();
	}
	public function add(Sortclass $sort){
       	$this->assign("sortclass",$sort->select_trees(39,config('database.prefix')."sortclass",1));
		return $this->fetch();
	}
	//得到编辑信息详细
	public function edit(Sortclass $sort ,$id=0){
		if($id){
			//编辑时， 需要返回当前类别的所有信息
			$data["row"] = Cases::where('id',$id)->find();
			$data["sortclass"] = $sort->select_trees(39,config('database.prefix')."sortclass",$data["row"]["sortid"]);
			 //得到批量上传的图片 begin
            $uuid = $data["row"]['uuid'];
            $photos = Photos::where("uuid",$uuid)->order('id asc')->select();
            $strphoto = "";
            if($photos){
                foreach($photos as $da){
                $url  =  config("app.rootfpath")."/".$da["pic"];
                $imgname = $da["pic"];
                $title    = $da['title'];
               
                $strphoto .= "<li><img  src='$url' /><a href='javascript:;' _files='$imgname' class='delete'>删除</a><input class='p_name' name='txt[]' value='$title'/><input type='checkbox' name='img[]' value='$imgname' checked style='display:none'/></li>";
                }
             }
            //得到批量上传的图片 end
            $data["strphoto"] = $strphoto;
			$this->assign($data);
			return $this->fetch();
		}
	}

	//添加编辑保存
	public function save(Photos $photos){
		$data = $this->request->post();
		$id  = $this->request->post("id");
        $data["sortpath"] = Db::name("sortclass")->where("id",$data["sortid"])->value("sortpath");
        $img = $this->request->post("img/a","");
	    $txt = $this->request->post("txt/a","");
		if($id){
			//修改
			Cases::update($data);
			$uuid = Cases::where('id',$id)->value('uuid');
			//逐个读取上传图片并存储到表tp_photos: 如果已经有了，就update，如果表里不存在，就insert
			   if($img){
				   	 foreach ($img as $k => $v)
				   	 {

			    		$pic = $v;
			    		$title = $txt[$k];
			    		$data = ['title'=> $title,'pic'=> $pic,'uuid'=> $uuid];
			    		$map = [];
			    		$map[]  = ['pic','=', $pic];
			    		$map[]  = ['uuid','=',$uuid];
			    		$exitid = Photos::where($map)->value('id');
			    		if ($exitid){
				       	   		//如果存在该信息, 只更新说明
				       	   		Photos::where('id', $exitid)->update(['title' => $title]);
			       		}
			      		 else{
					 		   Photos::create($data);
						}

			    	}
		         
		        }
			ajson(["status"=>1,"mes"=>"修改成功"]);
		}
		else{
			//添加
			$uuid = uuid();  //生成唯一值
			$data["uuid"] = $uuid;
			$data["addtime"] = time();
		    Cases::create($data,true);

		    //上传多图到 表 photos begin
		    
	        $list = [];
		    if($img){
		    	foreach ($img as $k => $v) {
		    		$pic = $v;
		    		$title = $txt[$k];
		    		$list[] = ['title'=> $title,'pic'=> $pic,'uuid'=> $uuid];
		    	}
	        }
	        $photos->saveAll($list);
	        //上传多图到 表 photos end
			ajson(["status"=>1,"mes"=>"添加成功"]);
			}
	}
	

	//删除类别 begin
	public function del(){
		$ids = $id = $this->request->post("id");
		if(is_array($id)){
			$ids = implode(",",$ids);
		}
		$result = Cases::where("id",'in',$ids)->field('pic,uuid')->select();
		foreach ($result as $v) {
			if($v["pic"]){
				 if(file_exists(config("app.fpath")."/".$v["pic"])){
			   		unlink(config("app.fpath")."/".$v["pic"]);
			   }
			}
			$uuid = $v["uuid"];
			$rs   = Photos::where("uuid",'=',$uuid)->field('pic')->select();
			foreach($rs as $vv){
					if($vv["pic"]){
					 if(file_exists(config("app.fpath")."/".$vv["pic"])){
				   		unlink(config("app.fpath")."/".$vv["pic"]);
					   }
					}
			}
			Photos::where("uuid",'=',$uuid)->delete();
		}
		$count = Cases::destroy($id);
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
		$data["list"] = Cases::where($map)->order('id', 'desc')->paginate(10,false,[
		    'query'     => $query,
		    'path'      => url("yteng/casesdo/index")
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
		Cases::update($this->request->post());
		ajson(["status"=>1,"mes"=>"修改成功"]);
		
	}
}
 ?>

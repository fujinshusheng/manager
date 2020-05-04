

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>用户管理</title>
  {include file='public/head'}
  <style type="text/css">
    .orders{height: 26px;}
  </style>
</head>
<body>

  <div class="layui-fluid">
    <div class="layui-row layui-col-space15">
      <div class="layui-col-md12">
        <div class="layui-card">
          <div class="layui-card-body layui-form">
          <blockquote class="layui-elem-quote">
           <a href="javascript:;" class="layui-btn layui-btn-xs add" _id ="0"><i class="layui-icon">&#xe61f;</i>添加</a>
            <a href="javascript:;" class="layui-btn layui-btn-xs layui-btn-normal" id="checkAll"><i class="layui-icon">&#xe605;</i>全选</a>
            <a href="javascript:;" class="layui-btn layui-btn-xs layui-btn-normal" id="checkOther" ><i class="layui-icon">&#xe605;</i>反选</a>
            <a href="javascript:;" class="layui-btn layui-btn-xs layui-btn-danger" id="deleteAll" _id="0"><i class="layui-icon">&#xe640;</i>删除</a>
          </blockquote>
            <table class="layui-table">
              <colgroup>
                <col width="55">
                <col>
                <col>
                <col>
              </colgroup>
              <thead>
                <tr>
                  <th>选择</th>
                  <th>用户名</th>
                  <th>真实姓名</th>
                  <th>性别</th>
                  <th>添加时间</th>
                  <th>操作</th>
                </tr> 
              </thead>
              <tbody id="tbody">
               {volist name="list" id="row"}
                <tr>
                    <td><input type="checkbox" name="ids[]" value="{$row.id}" lay-skin="primary"></td>
                    <td>{$row.username}</td>
                    <td>{$row.realname}</td>
                    <td>{$row.sex}</td>
                    <td>{:date("Y-m-d H:i:s",$row.addtime)}</td>
                    <td>
                      <a href="javascript:;" _id ="{$row['id']}" class="layui-btn layui-btn-xs edit">修改</a>
                      <a href="javascript:;" _id ="{$row['id']}" class="layui-btn layui-btn-xs layui-btn-normal initpwd" title="初始化密码: 123456">初始化密码</a>
                      <a href="javascript:;" _id ="{$row['id']}" class="layui-btn layui-btn-danger layui-btn-xs del">删除</a>
                    </td>
                </tr>
               {/volist}
              </tbody>
            </table>
          </div>
        </div>
      </div>
      
    </div>
  </div>

 
</body>
</html>
 <div id="save" class="layui-fluid" style="display: none; overflow-y:scroll;">
         <form class="layui-form">
            <div class="layui-form-item">
                <label class="layui-form-label">用户名</label>
                <div class="layui-input-inline">
                    <input type="text" id="username" name="username" maxlength="20" autocomplete="off" class="layui-input">
                </div>
              
                <label class="layui-form-label userpwd">用户密码</label>
                <div class="layui-input-inline userpwd">
                      <input type="text" id="userpwd" name="userpwd" maxlength="20" autocomplete="off" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                  <label class="layui-form-label">真实姓名</label>
                  <div class="layui-input-inline">
                    <input type="text" id="realname" name="realname" maxlength="20" autocomplete="off" class="layui-input">
                  </div>
                  <label class="layui-form-label">性别</label>
                  <div class="layui-input-inline">
                    <input type="radio" name="sex" value="男" title="男">
                    <input type="radio" name="sex" value="女" title="女">
                  </div>
            </div>
            
            <div class="layui-form-item">
              <label class="layui-form-label">权限选择</label>
              <div class="layui-input-block">
                <div>
                   <button type="button" class="layui-btn layui-btn-xs layui-btn-normal" id="selectAll"><i class="layui-icon">&#xe605;</i>全选</button>
                    <button type="button" class="layui-btn layui-btn-xs layui-btn-normal" id="selectOther"><i class="layui-icon">&#xe605;</i>反选</button>
                </div>
                  <table id="purview_table">
                  </table>
                </div>
            </div>
            <style type="text/css">
               #purview_table{width: 600px;}
               #purview_table tr{border-bottom: 1px dashed #ccc; vertical-align: center;} 
               #purview_table td{padding-bottom: 2px; } 
                .layui-form-checkbox span{font-size: 12px;}
              .layui-form-checkbox[lay-skin=primary] i {width: 14px;height: 14px;line-height: 14px;} 

              </style>
             <div class="layui-form-item" style="padding-left: 80px;">
                  <input type="hidden" name="id" id="id" value="">
                  <button type="button" class="layui-btn" lay-submit lay-filter="go">立即提交</button>
                  <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </form>
  </div>
  <script src="__LAYUIADMIN__/layui/layui.js"></script>
  <script>
  layui.config({
    base: '__LAYUIADMIN__/' //静态资源所在路径
  }).extend({
    index: 'lib/index' //主入口模块
    ,ytajax:'yteng/ytajax'//定义的 ajax 模块
  }).use(['ytajax','index','form','layer'],function(){
       var $ = layui.$
          ,form = layui.form 
          ,layer = layui.layer
          ,ytajax = layui.ytajax
          ;
       //编辑信息
       $(document).on("click",".edit,.add",function(){
          //初始化信息设置
           var cls = $(this).attr("class");
           var pos = cls.indexOf("add"); //判断是添加， 还是 编辑

           if(pos > 0){
                var data  = {id:$(this).attr("_id")};
                var title = "添加管理员";
                $("button[type='reset']").show();
                $(".userpwd").show();
           }
           else{
                var data  = {id:$(this).attr("_id")};
                var title = "修改管理员";
                $("button[type='reset']").hide();
                $(".userpwd").hide(); //修改时不修改密码，自己可以单独修改密码， 管理员可以初始密码
           }
           $.ajax({
                 type:"post",
                 data:data,
                 url :'{:url("yteng/administrator/edit")}',
                 dataType:'json',
                 async:true,
                 success:function(res){
                     if(pos<0){
                        //编辑初始值 begin
                        $("#username").val(res.username);
                        $("#realname").val(res.realname);
                        $("#id").val(res.id);
                        $("input[value="+(res.sex)+"]").prop("checked",true);
                     }
                     else{
                         $("#username").val('');
                         $("#realname").val('');
                         $("#userpwd").val('');
                         $("#id").val('');
                         $("input[value=男]").prop("checked",true);
                     }
                     $("#purview_table").html(res.purview);
                      
                      form.render();
                      //弹出开始
                        //初始化信息结束
                         index_alert=layer.open({
                          title:title,
                          type: 1,
                          skin: 'layui-layer-rim',
                          area: ['800px'],
                          content: $('#save')
                        });
                      //弹出结束
                 }
              })
           
       })
       //编辑信息提交
       form.on('submit(go)', function(data){
            var id       = data.field.id;
            if(!data.field.username){
                layer.msg("用户名不能为空",{icon:2,time:1000},function(){$("#username").focus();});
                return false;
            }
            if(!id){
                if(!data.field.userpwd){
                    layer.msg("用户密码不能为空",{icon:2,time:1000},function(){$("#userpwd").focus();});
                    return false;
                 } 
            }
            var data = $("form").serializeArray();
            $.ajax({
                 type:"post",
                 data:data,
                 url :'{:url("yteng/administrator/save")}',
                 dataType:'json',
                 success:function(res){
                     if(res.status){
                         layer.msg(res.mes,{icon:1,time:1000},function(){
                           $("#tbody").html(res.info);
                           form.render();
                           layer.close(index_alert);
                         });
                         return false;
                     }
                     else{
                        layer.msg(res.mes,{icon:2,time:2000});return false;
                     }
                 }
              })
             //添加，修改 结束
            //layer.close(index_alert);
       })
       
       //修改类别排序
       $(document).on("click",".initpwd",function(){
           var data = {id:$(this).attr("_id")};
           ytajax({data:data,url:'{:url("yteng/administrator/initpwd")}'});
       })
       //单个删除用户 begin
       $(document).on("click",".del",function(){
            var data = {id:$(this).attr("_id")};
            var _this = $(this);
            layer.confirm('你真的忍心要删除我吗?亲!', function(index){
              $.ajax({
                 type:"post",
                 data:data,
                 url :'{:url("yteng/administrator/del")}',
                 dataType:'json',
                 success:function(res){
                     if(res.status){
                         layer.msg(res.mes,{icon:1,time:1000});
                         _this.parent().parent().remove();
                         return false;
                     }
                     else{
                        layer.msg(res.mes,{icon:2,time:2000});return false;
                     }
                 }
              })
              //layer.close(index);
            }); 
       })
       //批量删除用户 begin
       $(document).on("click","#deleteAll",function(){

            var ids = $('input[name="ids[]"]:checked');
            if(!ids.length){
                layer.msg("请选择删除选选项",{icon:2,time:1000});
                return false;
            }
            else{
                var id = [];
                ids.each(function(){
                    id.push($(this).val());
                })
            }

            var _this = $(this);
            var data = {id:id};
            layer.confirm('你真的忍心要删除我吗?亲!', function(index){
              $.ajax({
                 type:"post",
                 data:data,
                 url :'{:url("yteng/administrator/del")}',
                 dataType:'json',
                 success:function(res){
                     if(res.status){
                         layer.msg(res.mes,{icon:1,time:1000},function(){
                                ids.each(function(){
                                  $(this).parent().parent().remove();
                              })
                         });
                         
                        return false;
                     }
                     else{
                        layer.msg(res.mes,{icon:2,time:2000});return false;
                     }
                 }
              })
              //layer.close(index);
            }); 
       })
       //删除删除 end
       //
       //删除全选
       $(document).on("click","#checkAll",function(){
            $('input[name="ids[]"]').prop("checked",true);
            form.render('checkbox');
          });
       //删除反选
          $(document).on("click","#checkOther",function(){
             $('input[name="ids[]"]').each(function(){
                  $(this).prop("checked",!$(this).prop("checked"));
                })
            form.render('checkbox');
          });
       //权限全部选择 begin
          //全选
          $(document).on("click","#selectAll",function(){
            $('input[name="purview[]"]').prop("checked",true);
            form.render('checkbox');
          });
          //反选
          $(document).on("click","#selectOther",function(){
             $('input[name="purview[]"]').each(function(){
                  $(this).prop("checked",!$(this).prop("checked"));
                })
            form.render('checkbox');
          });
          //选父类时, 子类一样的选择
          form.on('checkbox(pcheck)', function(data){
                    var ischecked = data.elem.checked;
                  var child = $(data.elem).parent('td').siblings().find('input');
                    child.each(function(index, item){
                         item.checked = ischecked;
                      });
                  form.render('checkbox');
          });
          //有三级子类时， 单击二级类别 没有选择时, 父类也取消选择
          form.on('checkbox(ccheck1)', function(data){

                    var parent = $(data.elem).parent("div").parent('td').siblings().children('input[type="checkbox"]');
                    var child = $(data.elem).siblings().children('input');
                       //让三级类选中 或者 取消
                        child.prop("checked",$(data.elem).prop("checked"));
                    
                    var parent_check = $(data.elem).parent().parent().children("div").children('input');
                    var ischecks = false;
                      parent_check.each(function(index, item){
                         if(item.checked){ischecks = true;} //如果子项有一项为true, 那么父类就会选中
                      });
                      console.log(ischecks);
                       //让一级类选中 或者 取消
                      parent.prop("checked",ischecks);
                      form.render('checkbox');
          });
          //没有三级， 选择二级类时
          form.on('checkbox(ccheck2)', function(data){
                    var parent = $(data.elem).parent('td').siblings().children('input[type="checkbox"]');
                    var child = $(data.elem).parent('td').children('input');
                    var ischecked = false;
                      child.each(function(index, item){
                         if(item.checked){ischecked = true;} //如果子项有一项为true, 那么父类就会选中
                      });
                      parent.prop("checked",ischecked);
                    form.render('checkbox');
          });
          //三级类没有选择时, 父类也取消选择
          form.on('checkbox(c3check)', function(data){
                    var parent = $(data.elem).parent().siblings('input');
                    console.log(parent);
                    var child =  $(data.elem).parent().find('input');
                    var ischecked = false;
                      child.each(function(index, item){
                         if(item.checked){ischecked = true;} //如果子项有一项为true, 那么父类就会选中
                      });
                      parent.prop("checked",ischecked);
                     
                   form.render('checkbox');
          });
          //权限全部选择 end
  });
  </script>
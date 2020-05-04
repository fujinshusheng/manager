

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>广告位置管理</title>
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
          <div class="layui-card-header">广告位置管理　　<a href="javascript:;" _pid ="0" class="layui-btn layui-btn-normal layui-btn-xs add">添加一级广告位置</a></div>
          <div class="layui-card-body">
            <table class="layui-table">
              <colgroup>
                <col width="60">
                <col>
                <col width="100" align="center">
                <col width="300">
              </colgroup>
              <thead>
                <tr>
                  <th>ID号</th>
                  <th>位置名称</th>
                  <th>排序</th>
                  <th>操作</th>
                </tr> 
              </thead>
              <tbody id="tbody">
               <?php foreach($results as $row) { ?>
            <tr>
              <td><?php echo $row["id"];?></td>
              <td><?php echo $row["space"].$row["sortname"];?></td>
              <td>
                  <input type="number" class="layui-input orders" _pid ="{$row['id']}" value="<?php echo $row["orders"];?>">
              </td>
              <td>
                <a href="javascript:;" _pid ="{$row['id']}" class="layui-btn layui-btn-normal layui-btn-xs add">添加</a>
                <a href="javascript:;" _pid ="{$row['id']}" class="layui-btn layui-btn-xs edit">修改</a>
                <a href="javascript:;" _pid ="{$row['id']}" class="layui-btn layui-btn-danger layui-btn-xs del">删除</a>
              </td>
               </tr>
            <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      
    </div>
  </div>

 
</body>
</html>
 <div id="save" class="layui-fluid" style="display: none;">
         <form class="layui-form">
            <div class="layui-form-item">
              <label class="layui-form-label">父级位置</label>
              <div class="layui-input-block" id="sortclass"></div>
            </div>
            <div class="layui-form-item">
              <label class="layui-form-label">位置名称</label>
              <div class="layui-input-block">
                <input type="text" id="sortname" name="sortname" maxlength="20" autocomplete="off" class="layui-input">
              </div>
            </div>
            <div class="layui-form-item" style="display: none;">
              <label class="layui-form-label">英文名称</label>
              <div class="layui-input-inline">
                <input type="text" id="esortname" name="esortname" maxlength="20" autocomplete="off" class="layui-input">
              </div>
            </div>
            <div class="layui-form-item">
              <label class="layui-form-label">注释</label>
              <div class="layui-input-block">
                <input type="text" id="remark" name="remark" maxlength="20" autocomplete="off" class="layui-input">
              </div>
            </div>
            <div class="layui-form-item">
              <label class="layui-form-label">排序</label>
              <div class="layui-input-inline">
                <input type="number" min="0" id="orders" name="orders"  autocomplete="off" class="layui-input">
              </div>
            </div>
            
             <div class="layui-form-item" style="padding-left: 80px;">
                  <input type="hidden" name="id" id="id" value="">
                  <button type="button" class="layui-btn submit" >立即提交</button>
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
                var data  = {id:$(this).attr("_pid"),action:"add"};
                var title = "添加广告位置";
                $("button[type='reset']").show();
           }
           else{
                var data  = {id:$(this).attr("_pid"),action:"edit"};
                var title = "修改广告位置";
                $("button[type='reset']").hide();
           }
           $.ajax({
                 type:"post",
                 data:data,
                 url :'{:url("yteng/adsort/edit")}',
                 dataType:'json',
                 async:true,
                 success:function(res){
                     if(pos<0){
                        //编辑初始值 begin
                        $("#sortclass").html(res.sortclass);
                        $("#sortname").val(res.sortname);
                        $("#orders").val(res.orders);
                        $("#remark").val(res.remark);
                        $("#id").val(res.id);
                     }
                     else{
                        $("#sortclass").html(res.sortclass);
                        $("#sortname").val('');
                        $("#orders").val('1');
                        $("#remark").val('');
                        $("#id").val('');
                     }
                      
                      form.render();
                      //弹出开始
                        //初始化信息结束
                         index_alert=layer.open({
                          title:title,
                          type: 1,
                          skin: 'layui-layer-rim',
                          area: ['700px', '450px'],
                          content: $('#save')
                        });
                      //弹出结束
                 }
              })
           
       })
       //编辑信息提交
       $(document).on("click",".submit",function(){
            var sortname = $("#sortname").val();
            if(!sortname){
                layer.msg("广告位置名称不能为空",{icon:2,time:1000},function(){
                    $("#sortname").focus();
                });
            }
            var orders   = $("#orders").val();
            if(!orders){
                layer.msg("请填写排序",{icon:2,time:1000},function(){
                    $("#orders").focus();
                });
            }
            
            var data = $("form").serializeArray();
            $.ajax({
                 type:"post",
                 data:data,
                 url :'{:url("yteng/adsort/save")}',
                 dataType:'json',
                 success:function(res){
                     if(res.status){
                         layer.msg(res.mes,{icon:1,time:1000},function(){
                          console.log(res);
                           //$("#tbody").html(res.info);
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
       //修改广告位置排序
       $(document).on("blur",".orders",function(){
           var data = {id:$(this).attr("_pid"),orders:$(this).val()};
           ytajax({data:data,url:'{:url("yteng/adsort/saveorders")}'});
       })
       //删除广告位置 begin
       $(document).on("click",".del",function(){
            var data = {id:$(this).attr("_pid")};
            var _this = $(this);
            layer.confirm('你真的忍心要删除我吗?亲!', function(index){
              $.ajax({
                 type:"post",
                 data:data,
                 url :'{:url("yteng/adsort/del")}',
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
       //删除广告位置 end
  });
  </script>
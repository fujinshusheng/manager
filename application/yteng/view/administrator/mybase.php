

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
    <div class="layui-card">
      <!-- <div class="layui-card-header">基本资料</div> -->
      <div class="layui-card-body" style="padding: 15px;">
        <form class="layui-form">
         <div class="layui-form-item">
                <label class="layui-form-label">用户名</label>
                <div class="layui-input-inline">
                    <input type="text" id="username" name="username" value="{$row.username}" disabled="" maxlength="20" autocomplete="off" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                  <label class="layui-form-label">真实姓名</label>
                  <div class="layui-input-inline">
                    <input type="text" id="realname" name="realname" value="{$row.realname}" maxlength="20" autocomplete="off" class="layui-input">
                  </div>
                  <label class="layui-form-label">性别</label>
                  <div class="layui-input-inline">
                    <input type="radio" name="sex" value="男" title="男" {eq name="row.sex" value="男"}checked{/eq}>
                    <input type="radio" name="sex" value="女" title="女" {eq name="row.sex" value="女"}checked{/eq}>

                  </div>
            </div>
          
             <div class="layui-form-item" style="padding-left: 80px;">
                  <input type="hidden" name="id" id="id" value="{$row.id}">
                  <button type="button" class="layui-btn" lay-submit lay-filter="go">立即提交</button>
                  <!-- <button type="reset" class="layui-btn layui-btn-primary">重置</button> -->
            </div>
        </form>
      </div>
    </div>
  </div>


 
</body>
</html>
 <div id="save" class="layui-fluid" style="display: none; overflow-y:scroll;">
        
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
      
       //编辑信息提交
       form.on('submit(go)', function(data){
            if(!data.field.username){
                layer.msg("用户名不能为空",{icon:2,time:1000},function(){$("#username").focus();});
                return false;
            }
            var data = $("form").serializeArray();
           
            $.ajax({
                 type:"post",
                 data:data,
                 url :'{:url("yteng/administrator/mybase_save")}',
                 dataType:'json',
                 success:function(res){
                     if(res.status){
                         layer.msg(res.mes,{icon:1,time:1000},function(){
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
     
  });
  </script>
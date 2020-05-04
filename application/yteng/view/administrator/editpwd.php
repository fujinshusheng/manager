

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
                <label class="layui-form-label">新的密码</label>
                <div class="layui-input-inline">
                    <input type="password" id="userpwd" name="userpwd"  maxlength="20" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">确认密码</label>
                <div class="layui-input-inline">
                    <input type="password" id="cuserpwd" name="cuserpwd" maxlength="20" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item" style="padding-left: 80px;">
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
            if(!data.field.userpwd){
                layer.msg("用户密码不能为空",{icon:2,time:1000},function(){$("#userpwd").focus();});
                return false;
            }
            if(data.field.userpwd != data.field.cuserpwd){
                layer.msg("用户密码与确认密码不相等",{icon:2,time:1000},function(){$("#cuserpwd").focus();});
                return false;
            }
            var data = {userpwd:data.field.userpwd};
           
            $.ajax({
                 type:"post",
                 data:data,
                 url :'{:url("yteng/administrator/editpwd_save")}',
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
             //修改 结束
            //layer.close(index_alert);
       })
       
       
  });
  </script>
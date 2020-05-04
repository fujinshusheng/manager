<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>{:config("app.webname")}管理后台登录</title>
  {include file='public/head'}
  <link rel="stylesheet" href="__LAYUIADMIN__/style/login.css" media="all">
</head>
<body>

  <div class="layadmin-user-login layadmin-user-display-show" id="LAY-user-login" style="display: none;">

    <div class="layadmin-user-login-main">
      <div class="layadmin-user-login-box layadmin-user-login-header">
        <h2>{:config("app.webname")}管理后台登录</h2>
        <p></p>
      </div>
      <div class="layadmin-user-login-box layadmin-user-login-body layui-form">
        <div class="layui-form-item">
          <label class="layadmin-user-login-icon layui-icon layui-icon-username" for="LAY-user-login-username"></label>
          <input type="text" name="username" id="LAY-user-login-username" lay-verify="required" placeholder="用户名" class="layui-input">
        </div>
        <div class="layui-form-item">
          <label class="layadmin-user-login-icon layui-icon layui-icon-password" for="LAY-user-login-password"></label>
          <input type="password" name="password" id="LAY-user-login-password" lay-verify="required" placeholder="密码" class="layui-input">
        </div>
        <div class="layui-form-item">
          <div class="layui-row">
            <div class="layui-col-xs7">
              <label class="layadmin-user-login-icon layui-icon layui-icon-vercode" for="LAY-user-login-vercode"></label>
              <input type="text" name="vercode" id="LAY-user-login-vercode" lay-verify="required" placeholder="图形验证码" class="layui-input">
            </div>
            <div class="layui-col-xs5">
              <div style="margin-left: 10px;">
                <img src="{:url('/yteng/login/verify')}" class="layadmin-user-login-codeimg" onclick="this.src='{:url('/yteng/login/verify')}?id='+Math.random();">
              </div>
            </div>
          </div>
        </div>
        <div class="layui-form-item">
          <button class="layui-btn layui-btn-fluid" id="LAY-user-login-submit" lay-submit lay-filter="LAY-user-login-submit">登 入</button>
        </div>
        
      </div>
    </div>
  </div>

  <script src="__LAYUIADMIN__/layui/layui.js"></script>  
  <script>
   layui.use(['form'], function(){
    var $ = layui.$
    ,form = layui.form 
    ;
    form.render();

    //jquery 触发 回车事件
    $(document).keyup(function(event){
      if(event.keyCode ==13){
        $("#LAY-user-login-submit").trigger("click");
      }
    });
    //提交
    form.on('submit(LAY-user-login-submit)', function(obj){
      //请求登入接口
      $.ajax({
        type:'post'
        ,url: '{:url("yteng/login/check")}'
        ,data: obj.field
        ,dataType:'json'
        ,success: function(res){
            if(!res.status){
               layer.msg(res.mes,{icon:2,time:1000});return false;
            }
            //请求失败结束
            else{
              layer.msg(res.mes,{icon:1,time:1000},function(){
                  location.href='{:url("/yteng/main")}';
              });return false;
            }
            //请求成功结束

        }
      });
      
    });
    
  });
  </script>
</body>
</html>
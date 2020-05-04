

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>网站设置</title>
  {include file='public/head'}
</head>
<body>

  <div class="layui-fluid">
    <div class="layui-row layui-col-space15">
      <div class="layui-col-md12">
        <div class="layui-card">
          <div class="layui-card-header">网站设置</div>
          <div class="layui-card-body" pad15>
            
            <div class="layui-form" wid100 lay-filter="">
              <div class="layui-form-item">
                <label class="layui-form-label">网站名称</label>
                <div class="layui-input-block">
                  <input type="text" name="sitename" class="layui-input" value="{:isset($info.sitename)?$info.sitename:''}">
                </div>
              </div>
              <div class="layui-form-item">
                <label class="layui-form-label">首页标题</label>
                <div class="layui-input-block">
                  <input type="text" name="title" class="layui-input" value="{:isset($info.title)?$info.title:''}">
                </div>
              </div>
               <div class="layui-form-item">
                <label class="layui-form-label">首页关键词</label>
                <div class="layui-input-block">
                  <input type="text" name="keywords" class="layui-input" value="{:isset($info.keywords)?$info.keywords:''}">
                </div>
              </div>
              <div class="layui-form-item layui-form-text">
                <label class="layui-form-label">首页描述</label>
                <div class="layui-input-block">
                  <textarea name="description" class="layui-textarea">{:isset($info.description)?$info.description:''}</textarea>
                </div>
              </div>
               <div class="layui-form-item">
                <label class="layui-form-label">联系人</label>
                <div class="layui-input-inline">
                  <input type="text" name="contact" class="layui-input" value="{:isset($info.contact)?$info.contact:''}">
                </div>
              </div>
               <div class="layui-form-item">
                <label class="layui-form-label">公司地址</label>
                <div class="layui-input-block">
                  <input type="text" name="address" class="layui-input" value="{:isset($info.address)?$info.address:''}">
                </div>
              </div>
              <div class="layui-form-item">
                <label class="layui-form-label">固定电话</label>
                <div class="layui-input-inline">
                  <input type="text" name="fixed_call" class="layui-input" value="{:isset($info.fixed_call)?$info.fixed_call:''}">
                </div>
              </div>
              <div class="layui-form-item">
                <label class="layui-form-label">移动电话</label>
                <div class="layui-input-inline">
                  <input type="text" name="mobile" class="layui-input" value="{:isset($info.mobile)?$info.mobile:''}">
                </div>
              </div>
              <div class="layui-form-item">
                <label class="layui-form-label">QQ</label>
                <div class="layui-input-inline">
                  <input type="text" name="qq" class="layui-input" value="{:isset($info.qq)?$info.qq:''}">
                </div>
              </div>
               <div class="layui-form-item">
                <label class="layui-form-label">电子邮箱</label>
                <div class="layui-input-inline">
                  <input type="text" name="email" class="layui-input" value="{:isset($info.email)?$info.email:''}">
                </div>
              </div>
               <div class="layui-form-item">
                <label class="layui-form-label">域名</label>
                <div class="layui-input-inline">
                  <input type="text" name="domain" class="layui-input" value="{:isset($info.domain)?$info.domain:''}">
                </div>
              </div>
              <div class="layui-form-item">
                <label class="layui-form-label">管理局备案号</label>
                <div class="layui-input-inline">
                  <input type="text" name="icp" class="layui-input" value="{:isset($info.icp)?$info.icp:''}">
                </div>
              </div>
              <div class="layui-form-item">
                <label class="layui-form-label">公安备案号</label>
                <div class="layui-input-inline">
                  <input type="text" name="police_icp" class="layui-input" value="{:isset($info.police_icp)?$info.police_icp:''}">
                </div>
              </div>
              <div class="layui-form-item">
                <label class="layui-form-label">版权信息</label>
                <div class="layui-input-block">
                  <input type="text" name="copyright" class="layui-input" value="{:isset($info.copyright)?$info.copyright:''}">
                </div>
              </div>
              <div class="layui-form-item">
                <div class="layui-input-block">
                  <button class="layui-btn" lay-submit lay-filter="set_website">确认保存</button>
                </div>
              </div>
            </div>
            
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
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
      form.on("submit(set_website)",function(obj){
            var data = obj.field;
            ytajax({data:data,url:'{:url("yteng/webset/save")}'});
      });
  });
  </script>
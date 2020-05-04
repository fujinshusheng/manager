

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>链接添加</title>
  {include file='public/head'}
</head>
<body>

  <div class="layui-fluid">
    <div class="layui-row layui-col-space15">
      <div class="layui-col-md12">
        <div class="layui-card">
          <div class="layui-card-body" pad15>
            <fieldset class="layui-elem-field">
                <legend>链接添加</legend>
                <div class="layui-field-box layui-form">
                   <div class="layui-form-item">
                    <label class="layui-form-label">名称</label>
                    <div class="layui-input-inline">
                      <input type="text" name="title" id="title" lay-verify="required" lay-verType="tips" autocomplete="off" class="layui-input" maxlength="20" style="width: 250px;">
                    </div>
                  </div>
                  <div class="layui-form-item">
                    <label class="layui-form-label">链接地址</label>
                    <div class="layui-input-block">
                      <input type="text" name="linkurl" id="linkurl" maxlength="100"  autocomplete="off" class="layui-input">
                    </div>
                  </div>

                  <div class="layui-form-item">
                    <label class="layui-form-label">类别</label>
                    <div class="layui-input-block">
                      <?php echo $sortclass;?>
                    </div>
                  </div>
                  <div class="layui-form-item">
                    <label class="layui-form-label">LOGO</label>
                    <div class="layui-input-block">
                      <input type="hidden" id="pic" name="pic"/>
                      <button type="button" class="layui-btn" id="uppic">上传图片</button>
                      <button type="button" class="layui-btn  layui-btn-danger delpic" style="display: none;">删除图片</button>
                       <div class="layui-upload-list">
                       <img class="layui-upload-img" id="demopic" style="max-width: 100px;">
                       <p id="FailText"></p>
                      </div>
                      
                  </div>
                  
                    <div class="layui-form-item">
                      <label class="layui-form-label">排序</label>
                      <div class="layui-input-inline">
                        <input type="number" name="orders" value="1" maxlength="4" lay-verify="number\required"  autocomplete="off" class="layui-input" style="width: 100px;">
                      </div>
                    </div>
                   <div class="layui-form-item">
                      <label class="layui-form-label">是否显示</label>
                      <div class="layui-input-block">
                        <input type="radio" name="isshow" value="1" title="是" checked>
                        <input type="radio" name="isshow" value="2" title="否">
                      </div>
                    </div>
                    <div class="layui-form-item">
                      <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="go" id="btn">确认保存</button>
                      </div>
                    </div>
                </div>
          </fieldset>
            
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
  }).use(['ytajax','laydate','form','layer','upload'],function(){
       var $ = layui.$
          ,form = layui.form 
          ,layer = layui.layer
          ,ytajax = layui.ytajax
          ,laydate = layui.laydate
          ,upload = layui.upload
          ;
  
   
      form.on("submit(go)",function(obj){
            var data = obj.field;
            if(!data.sortid){
                layer.msg("请选择类别",{icon:2,offset: 'auto',anim: 6},function(){});
                return false;
            }
            ytajax({data:data,url:'{:url("yteng/linksdo/save")}',islocation:1,location_url:'{:url("yteng/linksdo/index")}'});
      });
      ////提交表单结束
  //普通图片上传
  var uploadInst = upload.render({
    elem: '#uppic'
    ,field:'image'
    ,size: 300
    ,url: '{:url("yteng/filesdo/upload")}'
    ,acceptMime: 'image/*'
    ,before: function(obj){
      layer.load(); //上传loading
    }
    ,done: function(res){
      //如果上传失败
      if(res.code > 0){
        layer.closeAll('loading'); //关闭loading
        //原来有图片先删除原来的图片
        var files = $("#pic").val();
        if(files){
           var data = {files:files}
           ytajax({data:data,isalert:0,url:'{:url("yteng/filesdo/del")}'});
        }
        //预览上传成功的图片
        $('#demopic').attr('src', '<?php echo config("app.rootfpath");?>'+"/"+(res.filename));
        $("#pic").val(res.filename);
        $(".delpic").show(); //隐藏删除图片按钮
      }
      else{
        layer.closeAll('loading'); //关闭loading
        return layer.msg('上传失败')
      }
    }
    ,error: function(){
      layer.closeAll('loading'); //关闭loading
      //失败状态，并实现重传
      var FailText = $('#FailText');
          FailText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs reload">重试</a>');
          FailText.find('.reload').on('click', function(){
            $('#FailText').html('');
            uploadInst.upload();

      });
    }
  });
  //普通图片上传结束
  //绑定上传图片删除功能
  $(document).on("click",".delpic",function(){
        var files = $("#pic").val();
        $('#demopic').attr('src', '');
        $("#pic").val('');
        $(".delpic").hide();
        var data = {files:files}
        ytajax({data:data,isalert:0,url:'{:url("yteng/filesdo/del")}'});
  })
 
  });
  </script>
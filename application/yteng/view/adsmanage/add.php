

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>广告添加</title>
  {include file='public/head'}
</head>
<body>

  <div class="layui-fluid">
    <div class="layui-row layui-col-space15">
      <div class="layui-col-md12">
        <div class="layui-card">
          <div class="layui-card-body" pad15>
            <fieldset class="layui-elem-field">
                <legend>广告添加</legend>
                <div class="layui-field-box layui-form">
                   <div class="layui-form-item">
                    <label class="layui-form-label">广告名称</label>
                    <div class="layui-input-block">
                      <input type="text" name="title" id="title" lay-verify="required" lay-verType="tips" autocomplete="off" class="layui-input" maxlength="100">
                    </div>
                  </div>
                  <div class="layui-form-item" style="margin-top: 8px;">
                    <label class="layui-form-label">广告位置</label>
                    <div class="layui-input-block">
                      <?php echo $adsortclass;?>
                    </div>
                  </div>
                  <div class="layui-form-item">
                    <label class="layui-form-label">链接地址</label>
                    <div class="layui-input-block">
                      <input type="text" name="linkurl" value="" id="linkurl" maxlength="100"  autocomplete="off" class="layui-input">
                    </div>
                  </div>
                  <div class="layui-form-item">
                    <label class="layui-form-label">广告图片</label>
                    <div class="layui-input-block">
                      <input type="hidden" id="pic" name="pic" />
                      <button type="button" class="layui-btn" id="uppic">上传图片</button>
                      <div id="msg_show_1" style="clear: left; padding-bottom: 5px; color: #f00;"></div>
                      <div class="layui-upload-list">
                        <img class="layui-upload-img" id="demopic" style="max-width: 150px;">
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
                    <label class="layui-form-label">是否上线</label>
                    <div class="layui-input-inline">
                      <input type="radio" name="online" value="1" title="是" checked="true">
                      <input type="radio" name="online" value="2" title="否">
                    </div>
                  </div>
                   <div class="layui-form-item">
                      <label class="layui-form-label">起止日期</label>
                      <div class="layui-input-inline">
                        <input class="layui-input" placeholder="开始日" lay-verify="required" lay-verType="tips" id="starttime" name="starttime">
                      </div>
                      <div class="layui-input-inline">
                        <input class="layui-input" placeholder="截止日" lay-verify="required" lay-verType="tips" id="endtime" name="endtime">
                      </div>
                    </div>
                    <div class="layui-form-item">
                      <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="go">确认保存</button>
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
      //绑定开始时间
        laydate.render({ 
              elem: '#starttime'
              ,type: 'datetime'
              ,theme: 'grid'
       });
      //绑定结束时间
        laydate.render({ 
              elem: '#endtime'
              ,type: 'datetime'
              ,theme: 'grid'
       });


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
        /*当改变图片位置时, 显示 广告说明*/
         form.on('select(sortid)', function(data){
            $.ajax({
                type: "POST",
                url: "<?php echo url('yteng/adsmanage/getRemark'); ?>",
                async: false,
                data: {classid:data.value},
                success: function(msg)
                {
                 $("#msg_show_1").html(msg);
                }
              });
        });
         /*当改变图片位置时, 显示 广告说明*/
      form.on("submit(go)",function(obj){
            var data = obj.field;
            if(!data.sortid){
                layer.msg("请选择广告位置",{icon:2,offset: 'auto',anim: 6},function(){});
                //layer.tips('请选择广告位置', '吸附元素选择器', {tips: [1, '#3595CC'],time: 1000});
                return false;
            }
            if(!data.pic){
                layer.msg("请上传图片",{icon:2,offset: 'auto',anim: 6},function(){});
                return false;
            }
            if(data.endtime <= data.starttime){
               layer.tips('结束时间不能小于开始时间', '#endtime', {tips: [1, '#000'],time: 1000});
            }
             if(data.endtime <= data.starttime){
               layer.tips('结束时间不能小于开始时间', '#endtime', {tips: [1, '#000'],time: 1000});
            }
            ytajax({data:data,url:'{:url("yteng/adsmanage/save")}',islocation:1,location_url:'{:url("yteng/adsmanage/index")}'});
      });
  });
  </script>
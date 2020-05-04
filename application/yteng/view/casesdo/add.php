

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>案例添加</title>
  {include file='public/head'}
</head>
<body>

  <div class="layui-fluid">
    <div class="layui-row layui-col-space15">
      <div class="layui-col-md12">
        <div class="layui-card">
          <div class="layui-card-body" pad15>
            <fieldset class="layui-elem-field">
                <legend>案例添加</legend>
                <div class="layui-field-box layui-form">
                   <div class="layui-form-item">
                    <label class="layui-form-label">名称</label>
                    <div class="layui-input-block">
                      <input type="text" name="title" id="title" lay-verify="required" lay-verType="tips" autocomplete="off" class="layui-input" maxlength="100">
                    </div>
                  </div>
                  <div class="layui-form-item">
                      <label class="layui-form-label">关键词</label>
                      <div class="layui-input-block">
                        <input type="text" name="keyword" value="" id="keyword" maxlength="30"  autocomplete="off" class="layui-input"> 3-5关键词为宜, 用英文 逗号 隔开
                      </div>
                    </div>
                    <div class="layui-form-item layui-form-text">
                      <label class="layui-form-label">概述</label>
                      <div class="layui-input-block">
                        <textarea name="description" placeholder="请输入内容" class="layui-textarea"></textarea>
                      </div>
                    </div>
                    <div class="layui-form-item">
                      <label class="layui-form-label">信息类别</label>
                      <div class="layui-input-block">
                        <?php echo $sortclass;?>
                      </div>
                    </div>
                  <div class="layui-form-item">
                    <label class="layui-form-label">小图</label>
                    <div class="layui-input-block">
                      <input type="hidden" id="pic" name="pic" />
                      <button type="button" class="layui-btn" id="uppic">上传图片</button>
                      <button type="button" class="layui-btn  layui-btn-danger delpic" style="display: none;">删除图片</button>
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

                 <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">详情</label>
                    <div class="layui-input-block">
                      <!-- 加载编辑器的容器 -->
    <script id="content" name="content" type="text/plain" style="height: 350px;"></script>
    <!-- 配置文件 -->
    <script type="text/javascript" src="__UEDITOR__/ueditor.config.js"></script>
    <!-- 编辑器源码文件 -->
    <script type="text/javascript" src="__UEDITOR__/ueditor.all.js"></script>
    <!-- 实例化编辑器 -->
    <script type="text/javascript">
        var ue = UE.getEditor('content');
    </script>
                    </div>
                  </div>
                  <!-- 上传多图 HTML begin-->
                  <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label"></label>
                    <div class="layui-input-block">
                      <table>
                                  <tr>
                                      <td>
                                       <button type="button" class="layui-btn" id="upfile">上传多图</button>
                                    </td>
                                  </tr>
                                  <tr>
                                      <td>
                                          <div class="boxs" id="boxs" style="display:; padding-top: 10px;">
                                              <ul id="p_show"></ul>
                                           </div>
                                      </td>
                                  </tr>
                           </table>
                    </div>
                  </div>
                  <!-- 上传多图 HTML end-->
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
       
            var content = ue.getContent(); 
            var data = obj.field;
            if(!data.sortid){
                layer.msg("请选择类别",{icon:2,offset: 'auto',anim: 6},function(){});
                return false;
            }
            if(!content){
                layer.msg("内容不能为空",{icon:2,offset: 'auto',anim: 6},function(){});
                return false;
            }
             data.content = content;
             ytajax({data:data,url:'{:url("yteng/casesdo/save")}',islocation:1,location_url:'{:url("yteng/casesdo/index")}'});
      });
      ////提交表单结束
  //普通图片上传
  var uppic = upload.render({
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
            uppic.upload();

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

  //上传多图开始
    
         //普通图片上传
        //执行实例
        var upfile = upload.render({
          elem: '#upfile' //绑定元素
          ,size: 300
          ,field:'image'
          ,url: '{:url("yteng/filesdo/upload")}'
          ,acceptMime: 'image/*'
          ,multiple:true
          ,before: function(obj){
            //预读本地文件示例，不支持ie8
            obj.preview(function(index, file, result){
              //$('#demo1').attr('src', result); //图片链接（base64）
             
            });
          }
          ,done: function(res){
             //上传成功后, 预览显示, 同时可以对文件名做注释
             var filename = res.filename;
             var filepath = '<?php echo config("app.rootfpath");?>'+"/"+(res.filename);
             $("#p_show").append(
                "<li><img src='"+filepath+"'><a href='javascript:;' class='delete' _files='"+filename+"'>删除</a><input class='p_name' name='txt[]' maxlength='20' value='' placeholder='图片标题'/><input type='checkbox' name='img[]' value='"+filename+"' checked style='display:none'/></li>");
            //上传完毕回调
              $("#boxs").show();
           
          }
          ,error: function(){
            //请求异常回调
          }
        });
    //上传多图结束
    //绑定多图片单个删除功能
      $(document).on("click",".delete",function(){
            var files = $(this).attr("_files");
             $(this).parent().remove();
              if($("#boxs ul").children("li").length==0){
                $("#boxs").hide();
              }
             var data = {files:files}
             ytajax({data:data,isalert:0,url:'{:url("yteng/filesdo/del")}'});
      })
 
  });
  </script>
  
<style type="text/css">
.boxs{width:700px;overflow:hidden;float:left;pading:5px 0 5px 0; position:relative;}
.boxs ul{width:700px; padding-left:10px; display:block; float:left; overflow:hidden; _margin:5px 0;}
.boxs ul li{ list-style:none; float:left; display:block; width:153px; height:175px; padding:5px; margin-right:3px; border:1px #999f7e dashed; position:relative;overflow:hidden; clear:none;}
.boxs ul li img {width:150px; height:120px;border:1px #c3bb76 solid;display:block;}
.boxs ul li a{
  font-size:14px;
  color:#a37128;
  display:block;
  height:20px;
  text-align:center;
  margin:3px 0;
}
.boxs > ul > li > div{ display:none;}

.p_name{
  width:145px;
  height:20px;
  line-height:20px;
}
</style>
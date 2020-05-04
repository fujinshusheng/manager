

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>下载添加</title>
  {include file='public/head'}
</head>
<body>

  <div class="layui-fluid">
    <div class="layui-row layui-col-space15">
      <div class="layui-col-md12">
        <div class="layui-card">
          <div class="layui-card-body" pad15>
            <fieldset class="layui-elem-field">
                <legend>下载添加</legend>
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
                      <input type="number" name="orders" value="1" maxlength="4" lay-verify="number\required"  autocomplete="off" class="layui-input"  style="width: 60px;">
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
                  <div class="layui-form-item">
                    <label class="layui-form-label">上传文件</label>
                    <div class="layui-input-block">
                      <div id="infos" style="display:none;">
                      </div>
                      <table>
                          <tr>
                              <td><div id="pickerfile">选择文件</div></td>
                              <td style="padding-left:15px;"><button type="button" class="layui-btn" id="startup" style="display: none;">开始上传</button></td>
                          </tr>
                      </table>
                      <table class="layui-table">
                          <thead>
                             <tr>
                                 <th>文件名</th>
                                 <th>文件大小</th>
                                 <th>文件验证</th>
                                 <th style="width: 200px;">进度</th>
                                 <th>操作</th>
                             </tr>
                          </thead>
                          <tbody id="fileinfo">
                          </tbody>
                      </table>
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
    ,ytbigup:'yteng/ytbigup' //上传大文件
  }).use(['ytajax','ytbigup','form','layer','upload',"element"],function(){
       var $ = layui.$
          ,form = layui.form 
          ,layer = layui.layer
          ,ytajax = layui.ytajax
          ,ytbigup = layui.ytbigup
          ,upload = layui.upload
          ,element = layui.element
          ;
                     
      ytbigup({delurl:'{:url("yteng/filesdo/del")}'});
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
            var nums = $(".info").length;
             if(!nums){
                layer.msg("请上传下载文件",{icon:2,offset: 'auto',anim: 6},function(){});
                return false;
            }
            
             data.content = content;
             ytajax({data:data,url:'{:url("yteng/downs/save")}',islocation:1,location_url:'{:url("yteng/downs/index")}'});
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
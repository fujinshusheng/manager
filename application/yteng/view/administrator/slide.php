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
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
             
{volist name="list" id="row"}
                <tr>
                    <td><input type="checkbox" name="ids[]" value="{$row.id}" lay-skin="primary"></td>
                    <td><?php
                        $path = substr($row["sortpath"],7); 
                        $path = substr($path,0,strlen($path)-1);
                        echo get_sortpath_names($path,'sortclass');
                        ?>
                    </td>
                    <td>{:date('Y-m-d H:i:s',$row.addtime)}</td>
                   
                     <td>
                        <input type="number" class="layui-input orders" _id ="{$row['id']}" value="{$row.orders}">
                    </td>
                    <td>
                      <a href="{:url('yteng/about/edit',['id'=>$row.id])}"  class="layui-btn layui-btn-xs edit">修改</a>
                      <a href="javascript:;" _id ="{$row['id']}" class="layui-btn layui-btn-danger layui-btn-xs del">删除</a>
                    </td>
                </tr>
               {/volist}
                <tr>
                        <td colspan="10">
                                <a href="javascript:;" class="layui-btn layui-btn-xs layui-btn-normal" id="checkAll"><i class="layui-icon">&#xe605;</i>全选</a>
                                <a href="javascript:;" class="layui-btn layui-btn-xs layui-btn-normal" id="checkOther" ><i class="layui-icon">&#xe605;</i>反选</a>
                                <a href="javascript:;" class="layui-btn layui-btn-xs layui-btn-danger" id="deleteAll" _id="0"><i class="layui-icon">&#xe640;</i>删除</a>
                              <div id="pages">{$list|raw}</div>
                        </td>
                    </tr>
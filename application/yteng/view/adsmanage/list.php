{volist name="list" id="row"}
                <tr>
                    <td><input type="checkbox" name="ids[]" value="{$row.id}" lay-skin="primary"></td>
                    <td>{$row.title}</td>
                    <td><?php
                        $path = substr($row["sortpath"],4); 
                        $path = substr($path,0,strlen($path)-1);
                        echo get_sortpath_names($path,'adsortclass');
                        ?>
                    </td>
                    <td>
                        {eq name="$row.online" value="1"}
                             <font color="blue">是</font>
                        {else/}
                             <font color="red">否</font>
                        {/eq}
                    </td>
                    <td>
                        <?php 
                            //$nowtime = date("Y-m-d H:i:s");
                        ?>      
                        {egt name="$row.endtime" value="(:date("Y-m-d H:i:s"))"}
                             <span style='color:blue;'>未过期</span>
                        {else/}
                             <span style='color:red;'>已过期</span>
                        {/egt}
                    </td>
                    <td>{$row.starttime}</td>
                    <td>{$row.endtime}</td>
                    <td>
                        <input type="number" class="layui-input orders" _id ="{$row['id']}" value="{$row.orders}">
                    </td>
                    <td>
                        <img class="layui-upload-img" id="demopic" style="max-width: 100px;" src="<?php echo config("app.rootfpath")."/".$row["pic"];?>">
                    </td>
                    <td>
                      <a href="{:url('yteng/adsmanage/edit',['id'=>$row.id])}"  class="layui-btn layui-btn-xs edit">修改</a>
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
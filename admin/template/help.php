<div>
    <div class="selector">
        <div class="tab selected" name="新建工单">
            <table cellspacing="10">
                <tr><td class="table_input_label">工单主题</td><td><input type="text" placeholder="标题（30字）" style="width:500px;"/></td></tr>
                <tr><td valign="top" class="table_input_label">工单内容</td><td><textarea placeholder="工单内容（200字）" style="resize:none;width:500px;height:300px"></textarea></td></tr>
                <tr><td></td><td><button class="btn">提交工单</button></td></tr>
            </table>
        </div>
        <div class="tab" name="进行中工单" id="help_ing_list">
           <table width="100%" class="admin_list" cellspacing="0" >
                <tr><th>工单Id</th><th>工单主题</th><th>创建时间</th><th>状态</th><th>操作</th></tr>
                <tr><td align="center">2</td><td >请求扩大空间</td><td align="center">2017-02-23 18:34:56</td><td align="center">待接收</td><td align="center" class="operation"><span class="iconfont icon-chakan"></span><span class="iconfont icon-shanchu"></span></td></tr>
            </table>
        </div>
        <div class="tab" name="已完成工单" id="help_end_list">
            <table width="100%" class="admin_list" cellspacing="0" >
                <tr><th>工单Id</th><th>工单主题</th><th>创建时间</th><th>结束时间</th><th>状态</th><th>处理人</th></tr>
                <tr><td align="center">1</td><td >请求更新PHP版本</td><td align="center">2017-02-20 18:34:56</td><td align="center">2017-02-23 18:34:56</td><td align="center">已经结束</td><td align="center">admin</td></tr>
            </table>
        </div>
        
    </div>
    <script>$.initSelector()</script>
</div>
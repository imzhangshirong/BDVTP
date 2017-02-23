<?php
session_start();
?>
<div>
    <div class="selector">
        <div class="tab selected" name="个人设置">
            <div id="user_header" title="上传头像"></div>
            <div>
                <form style="display: none" id="user_header_upload" action="./admin/api.php?api=user&action=uploadHeader" method="post" enctype="multipart/form-data"><input type="file"  name="header"/></form>
                <table class="user_table">
                    <tr><td class="user_data_label">用户名</td><td colspan="3" id="user_info_username" class="user_data"></td></tr>
                    <tr><td class="user_data_label">昵  称</td><td colspan="3" class="user_data"><input    id="user_info_nickname"/></td></tr>
                    <tr><td class="user_data_label">邮  箱</td><td colspan="3" class="user_data"><input    id="user_info_email"/></td></tr>
                    <tr><td class="user_data_label">电  话</td><td colspan="3" class="user_data"><input    id="user_info_phone"/></td></tr>
                    <tr><td class="user_data_label">密  码</td><td colspan="3" class="user_data"><input    id="user_info_password" type="password" placeholder="留空则不修改密码"/></td></tr>
                    <tr><td colspan="4" align="center"><button class="btn" id="user_modify_button">修改信息</button></td></tr>
                    <tr class="user_limit">
                        <td class="user_data_label">最大CPU</td><td class="user_data" id="user_limit_cpu"></td>
                        <td class="user_data_label">内  存</td><td class="user_data" id="user_limit_mem"></td>
                        
                    </tr>
                    <tr class="user_limit">
                        <td class="user_data_label">任务数</td><td class="user_data" id="user_limit_task"></td>
                        <td class="user_data_label">空  间</td><td class="user_data" id="user_limit_space"></td>
                    </tr>
                    <tr class="user_limit">
                        <td class="user_data_label">进程数</td><td class="user_data" id="user_limit_process"></td>
                        <td class="user_data_label">进程时间</td><td class="user_data" id="user_limit_process_time"></td>
                    </tr>

                    <tr class="user_limit">
                        <td class="user_data_label">网络上传</td><td class="user_data" id="user_limit_upload"></td>
                        <td class="user_data_label">网络下载</td><td class="user_data" id="user_limit_download"></td>
                    </tr>
                </table>
            </div>
        </div>
        <?php if(isset($_SESSION['user']) && $_SESSION['user']['permission']<2){?>
        <div class="tab" name="用户管理" id="user_manage">
            <p class="title title2">下级用户</p>
            <table width="100%" class="admin_list" cellspacing="0" >
                <tr><th>用户名</th><th>所属</th><th>昵称</th><th>最近登录</th><th>操作</th></tr>
                <tr><td align="center">admin</td><td align="center">超级管理员</td><td align="center">HelloWorld</td><td align="center">2017-02-23 18:34:56 / 127.0.0.1</td><td align="center" class="operation"><span class="iconfont icon-chakan"></span><span class="iconfont icon-chongmingming"></span><span class="iconfont icon-shanchu"></span></td></tr>
            </table>
            <div style="text-align:right;margin:20px 0"><button class="btn">新增用户</button></div>
        </div>
        <?php }?>
    </div>
    
    
    <script>$.initSelector()</script>
    <script src="./js/admin_user.js?t=1"></script>
</div>
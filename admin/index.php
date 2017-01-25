<?php
header('Content-Type: html; charset=utf-8');
require_once dirname(__FILE__).'/config/base.php';
require_once ROOT_LIB.'globalFunction.php';
loadConfig();
loadClass();
loadModule("user");
$user=new User();
if(!$user->isLogin)exitByErrorScript("未登录，禁止进行操作","./");
$action=@$_GET['action'];
if(!apiIsLegal($api))$action="";
if(!$action)$action="task";
?>
<!DOCTYPE html>
<html>
    <head>
        <base href="../" />
        <title>大数据虚拟终端平台</title>
        <meta charset="utf-8"/>
        <link rel="stylesheet" type="text/css" href="./css/iconfont.css?t=<?=time()?>"/>
        <link rel="stylesheet" type="text/css" href="./css/index.css?t=<?=time()?>"/>
        <link rel="stylesheet" type="text/css" href="./css/admin.css?t=<?=time()?>"/>
        <link rel="stylesheet" type="text/css" href="./css/selector.css?t=<?=time()?>"/>
        <script src="./js/echarts.js"></script>
        <script src="./js/jquery-1.11.3.min.js"></script>
        <!--script src="./js/react/react.js"></script>
        <script src="./js/react/react-dom.js"></script-->
        <script src="./js/base.js?t=<?=time()?>"></script>
        <script src="./js/selector.js?t=<?=time()?>"></script>
    </head>
    <body>
        <div id="index_top">
            <div class="center">
                <div id="index_logo">
                    <div class="iconfont icon-logo"></div>
                    <div id="index_logo_text">
                        <p style="font-size:16px;">大数据虚拟终端平台</p>
                        <p style="font-size:12px;">Big Data Virtual Terminal Platform</p>
                    </div>
                </div>
                <div id="admin_person">
                    <div id="admin_info">
                        <img id="admin_profile_img" width="40" height="40" src="<?=PATH_USER_HEADER.$user->userInfo['header']?>"/>
                        <div id="admin_profile_name">
                            <p><?=$user->userInfo['nickname']?></p>
                            <span><?=$user->userInfo['permissionInfo']['name']?></span>
                        </div>
                    </div>
                    <div id="admin_message" title="消息" class="admin_person_button admin_click">
                        <div class="iconfont icon-xiaoxi" title="消息"></div>
                        <span>51</span>
                    </div>
                    <div id="admin_setting" title="个人设置" class="admin_person_button admin_click">
                        <div class="iconfont icon-shezhi" title="个人设置"></div>
                    </div>
                    <div id="admin_logout" title="退出" class="admin_person_button admin_click">
                        <div class="iconfont icon-tuichu" title="退出"></div>
                    </div>
                </div>
            </div>
        </div>
        <div id="admin_panel" class="center">
            <div id="admin_panel_menu">
                <div class="menu admin_click" action="task">
                    <span class="iconfont icon-renwu"></span>
                    <span>任务列表</span>
                </div>
                <div class="menu admin_click" action="file">
                    <span class="iconfont icon-file"></span>
                    <span>我的空间</span>
                </div>
                <div class="menu admin_click" action="overview">
                    <span class="iconfont icon-gaikuang"></span>
                    <span>系统概况</span>
                </div>
                <div class="line"></div>
                <div class="menu admin_click" action="help">
                    <span class="iconfont icon-gongdan"></span>
                    <span>提交工单</span>
                    </div>
                <div class="line"></div>
                <div class="menu admin_click" action="user">
                    <span class="iconfont icon-shezhi"></span>
                    <span>个人中心</span>
                    </div>
                <div class="line"></div>
                <div class="menu admin_click" action="feedback">
                    <span class="iconfont icon-fankui"></span>
                    <span>我要反馈</span>
                    </div>
            </div>
            <div id="admin_panel_operation"></div>
        </div>
        <div id="index_footer">
            <p style="text-align:center;font-size:14px;color:#6F6F6F">Copyright © 2016-2017. All Rights Reserved. BDVTP Alpha</p>
        </div>
        <script>
            $(".menu").on("click",function(){
                $(".menu").removeClass("seleted");
                $(this).addClass("seleted");
                BDVTP.load($(this).attr("action"));
            });
            $("#admin_logout").on("click",function(){
                BDVTP.current=undefined;
                BDVTP.api({api:"user",action:"logout"},function(resp){window.location.href="./";alert(resp.msg)});
            });
            $(".menu[action='<?=$action?>']").addClass("seleted");
            BDVTP.load("<?=$action?>");
        </script>
    </body>
    
</html>
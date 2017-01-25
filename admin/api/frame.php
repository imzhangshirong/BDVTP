<?php
header('Content-Type: html; charset=utf-8');
loadModule("user");
if(!checkGET(array("action")))exitByError(65535,"缺失参数");
$user=new User();
if(!$user->isLogin)exitByErrorScript("未登录，禁止进行操作","../");
$template=ROOT_TEMPLATE.$_GET['action'].".html";
if(file_exists($template)){
    require_once($template);
}
else{
    exitByErrorScript("操作面板不存在","./admin/");
}
?>
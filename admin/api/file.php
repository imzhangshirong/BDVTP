<?php
header('Content-Type: application/json; charset=utf-8');
loadModule("user");
$user=new User();
if(!$user->isLogin)exitByError(5,"未登录，禁止进行操作");
loadModule("overview");
$overview=new Overview();
if(checkGET(array("action"))){
    switch($_GET['action']){
        case "list":
            
            break;
        case "upload":
            
            break;
        case "download":

            break;
        default:
            exitByError(72,"未知操作");
    }
}
exitByError(65535,"缺失参数");
?>
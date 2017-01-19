<?php
loadModule("user");
$user=new User();
if(!$user->isLogin)exitByError(5,"未登录，禁止进行操作");
loadModule("overview");
$overview=new Overview();
if(checkGET(array("action"))){
    switch($_GET['action']){
        case "all":
            successByData("获取成功",$overview->getAll());
            break;
        case "cpu":
            successByData("获取成功",$overview->getCpu());
            break;
        case "cpuUsage":
            successByData("获取成功",$overview->getCpuUsage());
            break;
        case "cpuInfo":
            successByData("获取成功",$overview->getCpuInfo());
            break;
        case "systemLoad":
            successByData("获取成功",$overview->getSystemLoad());
            break;
        case "memory":
            successByData("获取成功",$overview->getMemory());
            break;
        case "space":
            successByData("获取成功",$overview->getSpace());
            break;
        case "net":
            successByData("获取成功",$overview->getNet());
            break;
        case "netSpeed":
            successByData("获取成功",$overview->getNetSpeed());
            break;
    }
    exitByError(72,"未知操作");
}
exitByError(65535,"缺失参数");
?>
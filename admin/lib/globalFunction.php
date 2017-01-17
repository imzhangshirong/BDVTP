<?php
function apiIsLegal($api){
    $zz="/(^[a-zA-Z0-9]*$)/";
    if(preg_match($zz,$api))return true;
    return false;
}
function dataIsLegal($sqldata){
    $zz="/(^[a-zA-Z0-9\x{4E00}-\x{9FA5}]*$)/u";
    for($a=0;$a<count($sqldata);$a++){
        if(!preg_match($zz,$sqldata[$a]))return false;
    }
    return true;
}
function exitByError($code,$msg){
    exit(json_encode(array(
        'code'=>$code,
        'msg'=>$msg
    )));
}
function successByData($msg,$data){
    exit(json_encode(array(
        'code'=>0,
        'msg'=>$msg,
        'data'=>$data
    )));
}
function successByMsg($msg){
    exit(json_encode(array(
        'code'=>0,
        'msg'=>$msg
    )));
}
function loadConfig(){
    $files=scandir(ROOT_CONFIG);
    for($a=0;$a<count($files);$a++){
        if(substr($files[$a],0,7)=="config." && substr($files[$a],-4)==".php"){
            require_once ROOT_CONFIG.$files[$a];
        }
    }
}
function loadClass(){
    $files=scandir(ROOT_CLASS);
    for($a=0;$a<count($files);$a++){
        if(substr($files[$a],0,6)=="class." && substr($files[$a],-4)==".php"){
            require_once ROOT_CLASS.$files[$a];
        }
    }
}
function loadModule($module){
    if($module && file_exists(ROOT_MODULE.$module.".php")){
        require_once ROOT_MODULE.$module.".php";
        return;
    }
    $files=scandir(ROOT_MODULE);
    for($a=0;$a<count($files);$a++){
        if(substr($files[$a],-4)==".php"){
            require_once ROOT_MODULE.$files[$a];
        }
    }
}
function checkGET($array){
    $all=count($array);
    for($a=0;$a<count($array);$a++){
        if(isset($_GET[$array[$a]]) && $_GET[$array[$a]]!="")$all--;
    }
    return $all==0;
}
function checkPOST($array){
    $all=count($array);
    for($a=0;$a<count($array);$a++){
        if(isset($_POST[$array[$a]]) && $_POST[$array[$a]]!="")$all--;
    }
    return $all==0;
}
function formatSizeUnit($size,$o=0){
    $unit=array(" B"," KB"," MB"," GB"," TB");
    $value=$size;
    $a=$o;
    for($a=$o;$value>=1024;$a++){
        $value/=1024;
    }
    return round($value,2).$unit[$a];
}
?>
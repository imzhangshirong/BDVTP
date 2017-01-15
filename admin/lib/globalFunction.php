<?php
function apiIsLegal($api){
    if(!$api)return false;
    $ILLEGALCHAR="./\\;,?+-!@#$%^&*=()<>";
    for($a=0;$a<strlen($ILLEGALCHAR);$a++){
        if(strpos($api,substr($ILLEGALCHAR,$a,1))>-1){
            return false;
        }
    }
    return true;
}
function exitByError($code,$msg){
    exit(json_encode(array(
        'code'=>$code,
        'msg'=>$msg
    ),true));
}
function successByData($msg,$data){
    exit(json_encode(array(
        'code'=>0,
        'msg'=>$msg,
        'data'=>$data
    ),true));
}
function successByMsg($msg){
    exit(json_encode(array(
        'code'=>0,
        'msg'=>$msg
    ),true));
}
?>
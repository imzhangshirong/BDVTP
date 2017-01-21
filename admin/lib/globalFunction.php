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
function getDirSize($dir){
    $handle = @opendir($dir);
    $sizeResult=0;
    while (false!==($FolderOrFile = @readdir($handle))){
        if($FolderOrFile != "." && $FolderOrFile != ".."){
            $new=$dir."/".$FolderOrFile;
            if(is_dir($new)){ 
                if(!is_link($new))$sizeResult += getDirSize($new); 
            }
            else{ 
                $sizeResult += @filesize($new); 
            }
        }
    }
    closedir($handle);
    return $sizeResult;
}
function checkValueType($data,$errormsg,$replaceMode=false){
    $data_re=array();
    foreach($data as $key=>$check){
        $type=@$check[0];
        $value=trim(@$check[1]);
        switch($type){
            case "email":
                if(!@preg_match("/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/",$value)){
                    $check[2]=false;
                    if(isset($errormsg[$key]))exitByError($errormsg[$key][0],$errormsg[$key][1]);
                }
                else{
                    $check[2]=true;
                }
                break;
            case "phone":
                if(!@preg_match("/^(13[0-9]|14[5|7]|15[0|1|2|3|5|6|7|8|9]|18[0|1|2|3|5|6|7|8|9])\d{8}$/",$value)){
                    $check[2]=false;
                    if(isset($errormsg[$key]))exitByError($errormsg[$key][0],$errormsg[$key][1]);
                }
                else{
                    $check[2]=true;
                }
                break;
            case "int":
                if(!@preg_match("/^-?[1-9]\d*$/",$value)){
                    $check[2]=false;
                    if(isset($errormsg[$key]))exitByError($errormsg[$key][0],$errormsg[$key][1]);
                }
                else{
                    $check[2]=true;
                }
                break;
            case "-int":
                if(!@preg_match("/^-[1-9]\d*$/",$value)){
                    $check[2]=false;
                    if(isset($errormsg[$key]))exitByError($errormsg[$key][0],$errormsg[$key][1]);
                }
                else{
                    $check[2]=true;
                }
                break;
            case "+int":
                if(!@preg_match("/^[1-9]\d*$/",$value)){
                    $check[2]=false;
                    if(isset($errormsg[$key]))exitByError($errormsg[$key][0],$errormsg[$key][1]);
                }
                else{
                    $check[2]=true;
                }
                break;
            case "float":
                if(!@preg_match("/^[-]?[1-9]\d*[\.]?\d*|0[\.]?\d*[1-9]\d*$/",$value)){
                    $check[2]=false;
                    if(isset($errormsg[$key]))exitByError($errormsg[$key][0],$errormsg[$key][1]);
                }
                else{
                    $check[2]=true;
                }
                break;
            case "+float":
                if(!@preg_match("/^[1-9]\d*[\.]?\d*|0[\.]?\d*[1-9]\d*$/",$value)){
                    $check[2]=false;
                    if(isset($errormsg[$key]))exitByError($errormsg[$key][0],$errormsg[$key][1]);
                }
                else{
                    $check[2]=true;
                }
                break;
            case "-float":
                if(!@preg_match("/^[-][1-9]\d*[\.]?\d*|0[\.]?\d*[1-9]\d*$/",$value)){
                    $check[2]=false;
                    if(isset($errormsg[$key]))exitByError($errormsg[$key][0],$errormsg[$key][1]);
                }
                else{
                    $check[2]=true;
                }
                break;
            case "letter+number":
                if(!@preg_match("/(^[a-zA-Z0-9]*$)/",$value)){
                    $check[2]=false;
                    if(isset($errormsg[$key]))exitByError($errormsg[$key][0],$errormsg[$key][1]);
                }
                else{
                    $check[2]=true;
                }
                break;
            case "legalString":
                if(!@preg_match("/(^[a-zA-Z0-9\x{4E00}-\x{9FA5}]*$)/u",$value)){
                    $check[2]=false;
                    
                    if(isset($errormsg[$key]))exitByError($errormsg[$key][0],$errormsg[$key][1]);
                }
                else{
                    $check[2]=true;
                }
                break;
        }
        $check[1]=$value;
        if($replaceMode){
            $data_re[$key]=$value;
        }
        else{
            $data_re[$key]=$check;
        }
    }
    return $data_re;
}
function exitByErrorScript($msg,$redir){
    if($redir)exit('<script>alert("'.$msg.'");window.location.href="'.$redir.'"</script>');
    exit('<script>alert("'.$msg.'");</script>');
}
?>
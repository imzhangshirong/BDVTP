<?php
require_once dirname(__FILE__).'/config/base.php';
require_once ROOT.'/lib/globalFunction.php';
$api = $_GET['api'];
if(apiIsLegal($api)){
    $apiFile=ROOT.'/api/'.$api.'.php';
    if(file_exists($apiFile)){
        require_once $apiFile;
    }
    else{
        exitByError(-2,"api不存在");
    }    
}
else{
    exitByError(-1,"非法api");
}
?>
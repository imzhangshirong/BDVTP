<?php
require_once dirname(__FILE__).'/config/base.php';
require_once ROOT_LIB.'globalFunction.php';
loadConfig();
loadClass();
$api = $_GET['api'];
if(apiIsLegal($api)){
    $apiFile=ROOT_API.$api.'.php';
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
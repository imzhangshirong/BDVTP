<?php 
/**
 * 输出二次验证结果,本文件示例只是简单的输出 Yes or No
 */
// error_reporting(0);
require_once ROOT . '/lib/class/class.geetestlib.php';
require_once ROOT . '/config/geetest.php';
session_start();
if($_GET['type'] == 'pc'){
    $GtSdk = new GeetestLib(CAPTCHA_ID, PRIVATE_KEY);
}elseif ($_GET['type'] == 'mobile') {
    $GtSdk = new GeetestLib(MOBILE_CAPTCHA_ID, MOBILE_PRIVATE_KEY);
}
else{
    $GtSdk = new GeetestLib(CAPTCHA_ID, PRIVATE_KEY);
}

$user_id = $_SESSION['BDVTP2016'];
if ($_SESSION['gtserver'] == 1) {   //服务器正常
    $result = $GtSdk->success_validate($_POST['geetest_challenge'], $_POST['geetest_validate'], $_POST['geetest_seccode'], $user_id);
    if ($result) {
        successByMsg("验证通过");
    } else{
        exitByError(-1,"验证未通过");
    }
}else{  //服务器宕机,走failback模式
    if ($GtSdk->fail_validate($_POST['geetest_challenge'],$_POST['geetest_validate'],$_POST['geetest_seccode'])) {
        successByMsg("验证通过");
    }else{
        exitByError(-1,"验证未通过");
    }
}
?>

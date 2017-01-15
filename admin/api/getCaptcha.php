<?php 
/**
 * 使用Get的方式返回：challenge和capthca_id 此方式以实现前后端完全分离的开发模式 专门实现failback
 * @author Tanxu
 */
require_once ROOT . '/lib/class/class.geetestlib.php';
require_once ROOT . '/config/geetest.php';
if($_GET['type'] == 'pc'){
	$GtSdk = new GeetestLib(CAPTCHA_ID, PRIVATE_KEY);
}elseif ($_GET['type'] == 'mobile') {
	$GtSdk = new GeetestLib(MOBILE_CAPTCHA_ID, MOBILE_PRIVATE_KEY);
}
else{
    $GtSdk = new GeetestLib(CAPTCHA_ID, PRIVATE_KEY);
}
session_start();
$user_id = "BDVTP2016";
$status = $GtSdk->pre_process($user_id);
$_SESSION['gtserver'] = $status;
$_SESSION['user_id'] = $user_id;
echo $GtSdk->get_response_str();
 ?>
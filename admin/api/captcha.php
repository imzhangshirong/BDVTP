<?php
header('Content-Type: application/json; charset=utf-8');
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
<?php
header('Content-Type: application/json; charset=utf-8');
session_start();
if($_GET['type'] == 'pc'){
    $GtSdk = new GeetestLib(CAPTCHA_ID, PRIVATE_KEY);
}elseif ($_GET['type'] == 'mobile') {
    $GtSdk = new GeetestLib(MOBILE_CAPTCHA_ID, MOBILE_PRIVATE_KEY);
}
else{
    $GtSdk = new GeetestLib(CAPTCHA_ID, PRIVATE_KEY);
}
if(checkPOST(array("username","password","geetest_challenge","geetest_validate","geetest_seccode"))){
    $user_id = $_SESSION['BDVTP2016'];
    if ($_SESSION['gtserver'] == 1) {   //服务器正常
        $result = $GtSdk->success_validate($_POST['geetest_challenge'], $_POST['geetest_validate'], $_POST['geetest_seccode'], $user_id);
        if ($result) {
            login();
        } else{
            exitByError(-1,"验证未通过");
        }
    }else{  //服务器宕机,走failback模式
        if ($GtSdk->fail_validate($_POST['geetest_challenge'],$_POST['geetest_validate'],$_POST['geetest_seccode'])) {
            login();
        }else{
            exitByError(-1,"验证未通过");
        }
    }
}
exitByError(65535,"缺失参数");

function login(){
    loadModule("user");
    $password=$_POST['password'];
    $data=array(
        'username'=>array('letter+number',$_POST['username']),
    );
    $error=array(
        'username'=>array(-6,"用户名非法"),
    );
    $data=checkValueType($data,$error,true);
    $user=new User(0,$data['username']);
    $user->login($password);
    successByMsg("登录成功");
}
?>

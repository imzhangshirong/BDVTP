<?php
loadModule("user");
$user=new User();
if(!$user->isLogin)exitByError(5,"未登录，禁止进行操作");
if(checkGET(array("action","sid"))){
    $sid=$_GET['sid'];
    if($_GET['action']=="add"){
        if(checkPOST(array("username","password","permission","nickname","email","phone"))){
            $profile=array(
                'nickname'=>$_POST['nickname'],
                'email'=>$_POST['email'],
                'phone'=>$_POST['phone'],
            );
            $user.add($_POST['username'],$_POST['password'],$profile,$_POST['permission']);
            successByMsg("创建用户成功");
        }
    }
    else if($_GET['action']=="edit"){
        if(checkPOST(array("nickname","email","phone"))){
            $profile=array(
                'nickname'=>$_POST['nickname'],
                'email'=>$_POST['email'],
                'phone'=>$_POST['phone'],
            );
            $user.editProfile($sid,$profile);
            successByMsg("修改用户成功");
        }
    }
    else if($_GET['action']=="modifyPass"){
        if(checkPOST(array("password"))){
            $user.modifyPassword($sid,$_POST['password']);
            successByMsg("修改用户密码成功");
        }
    }
    else if($_GET['action']=="modifyLimit"){
        if(checkPOST(array("cpu","memory","space","db_space","upload","download","process","process_time"))){
            $limit=array(
                'cpu'=>$_POST['cpu'],
                'memory'=>$_POST['memory'],
                'space'=>$_POST['space'],
                'db_space'=>$_POST['db_space'],
                'upload'=>$_POST['upload'],
                'download'=>$_POST['download'],
                'process'=>$_POST['process'],
                'process_time'=>$_POST['process_time'],
            );
            $user.modifyLimit($sid,$limit);
            successByMsg("修改用户配额成功");
        }
    }
    else if($_GET['action']=="delete"){
        $user->delete($sid);
        successByMsg("删除用户成功");
    }
    exitByError(72,"未知操作");
}
exitByError(65535,"缺失参数");
?>
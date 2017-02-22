<?php
class User{
    private $database;
    public $userInfo;
    public $isLogin=false;
    public function __construct($id=0,$username=""){
        session_start();
        $this->database=new Database();
        if($id && $id>0){
            $sql="SELECT * FROM `user` WHERE `id`='".$id."'";
            $result=$this->database->query($sql);
            if($result->num_rows>0){
                $this->userInfo=($this->database->fetchAll($result,MYSQLI_ASSOC))[0];
            }
        }
        else if($username){
            if(dataIsLegal(array($username))){
                $sql="SELECT * FROM `user` WHERE `username`='".$username."'";
                $result=$this->database->query($sql);
                if($result->num_rows>0){
                    $this->userInfo=($this->database->fetchAll($result,MYSQLI_ASSOC))[0];
                }
            }
        }
        else{
            $this->userInfo=$_SESSION['user'];
            if($this->userInfo['id'] && $this->userInfo['username']){
                $this->isLogin=true;
                if($_SESSION["update"]){
                    $sql="SELECT * FROM `user` WHERE `id`='".$this->userInfo['id']."'";
                    $result=$this->database->query($sql);
                    if($result->num_rows>0){
                        $this->userInfo=($this->database->fetchAll($result,MYSQLI_ASSOC))[0];
                    }
                }
            }
            else{
                $this->userInfo=null;
            }
        }
        if($this->userInfo)$this->userInfo['permissionInfo']=$this->getPermissionInfo();
        if($_SESSION["update"]){
            $_SESSION['user']=$this->userInfo;
            $_SESSION["update"];
        }
    }
    public function getPermissionInfo($code_=-1){
        $code=$code_;
        $re=array(
            'cpu'=>0,
            'memory'=>0,
            'download'=>0,
            'upload'=>0,
            'space'=>0,
            'task'=>0,
            'process'=>0,
            'db_space'=>0,
            'process_time'=>0,
            'name'=>"无效"
        );
        if($code<0 && $this->userInfo){
            $code=$this->userInfo['permission'];
        }
        switch($code){
            case 0:
                $re['cpu']=LIMIT_CPU_ADMIN;
                $re['memory']=LIMIT_RAM_ADMIN;
                $re['download']=LIMIT_DOWNLOAD_ADMIN;
                $re['upload']=LIMIT_UPLOAD_ADMIN;
                $re['space']=LIMIT_SPACE_ADMIN;
                $re['task']=LIMIT_TASK_ADMIN;
                $re['process']=LIMIT_PROCS_ADMIN;
                $re['db_space']=LIMIT_DBSPACE_ADMIN;
                $re['process_time']=LIMIT_PROCTIME_ADMIN;
                $re['name']="超级管理员";
                break;
            case 1:
                $re['cpu']=LIMIT_CPU_MANAGER;
                $re['memory']=LIMIT_RAM_MANAGER;
                $re['download']=LIMIT_DOWNLOAD_MANAGER;
                $re['upload']=LIMIT_UPLOAD_MANAGER;
                $re['space']=LIMIT_SPACE_MANAGER;
                $re['task']=LIMIT_TASK_MANAGER;
                $re['process']=LIMIT_PROCS_MANAGER;
                $re['db_space']=LIMIT_DBSPACE_MANAGER;
                $re['process_time']=LIMIT_PROCTIME_MANAGER;
                $re['name']="管理员";
                break;
            case 2:
                $re['cpu']=LIMIT_CPU_USER;
                $re['memory']=LIMIT_RAM_USER;
                $re['download']=LIMIT_DOWNLOAD_USER;
                $re['upload']=LIMIT_UPLOAD_USER;
                $re['task']=LIMIT_TASK_USER;
                $re['space']=LIMIT_SPACE_USER;
                $re['process']=LIMIT_PROCS_USER;
                $re['db_space']=LIMIT_DBSPACE_USER;
                $re['process_time']=LIMIT_PROCTIME_USER;
                $re['name']="普通用户";
                break;
        }
        if($code<0 && $this->userInfo){
            $re['cpu']=$this->userInfo['cpu'];
            $re['memory']=$this->userInfo['memory'];
            $re['task']=$this->userInfo['task'];
            $re['download']=$this->userInfo['net_download'];
            $re['upload']=$this->userInfo['net_upload'];
            $re['space']=$this->userInfo['space'];
            $re['process']=$this->userInfo['process'];
            $re['db_space']=$this->userInfo['db_space'];
        }
        return $re;
    }
    public function add($username,$password,$profile,$permission=2){
        if($this->userInfo['permission']>1){
            exitByError(64,"无权限进行操作");
        }
        if($permission<=userInfo['permission'] || $permission>2)exitByError(-5,"拒绝创建此类用户");
        if(!dataIsLegal(array($username))){
            exitByError(128,"存在非法字符");
        }
        $sql="SELECT `id` FROM `user` WHERE `username`='".$username."'";
        $result=$this->database->query($sql);
        if($result->num_rows>0){
            exitByError(3,"用户已经存在");
        }
        else{
            $limit=getPermissionInfo($permission);
            $sql="INSERT INTO `user`(username,password,nickname,email,phone,permission,cpu,memory,task,process,process_time,space,db_space,net_upload,net_download,creattime) VALUES('".$username."','".md5(MD5_SALT.$password)."','".$profile['nickname']."','".$profile['email']."','".$profile['phone']."','".$permission."','".$limit['cpu']."','".$limit['memory']."','".$limit['task']."','".$limit['process']."','".$limit['process_time']."','".$limit['space']."','".$limit['db_space']."','".$limit['upload']."','".$limit['download']."',NOW())";
            $result=$this->database->query($sql);
            mkdir(ROOT_USERSPACE.$username);
            $sql="CREATE DATABASE ".DATABASE_USER.$username;
            $this->database->query($sql);
            $sql="CREATE USER '".DATABASE_USER.$username."'@'localhost' IDENTIFIED BY '".$password."'";
            $this->database->query($sql);
            $sql="GRANT ALL ON ".DATABASE_USER.$username.".* TO '".DATABASE_USER.$username."'@'localhost'";
            $this->database->query($sql);
        }
    }
    public function getUsage(){
        $dir=ROOT_USERSPACE.$this->userInfo['username'];
        $dir_mysql=ROOT_USERSPACE_MYSQL.DATABASE_USER.$this->userInfo['username'];
        $size=getDirSize($dir);
        $size_mysql=getDirSize($dir_mysql);
        return array(
            'space'=>array($dir,formatSizeUnit($size)),
            'mysql'=>array($dir_mysql,formatSizeUnit($size_mysql)),
        );
    }
    public function modifyLimit($id,$limit){
        if($this->userInfo['permission']>1){
            exitByError(64,"无权限进行操作");
        }
        else{
            $userT=new User($id);
            if($userT->userInfo['permission']<=$this->userInfo['permission']){
                exitByError(64,"无权限进行操作");
            }
            $limitMax=getPermissionInfo($this->userInfo['permission']);
            if($limit['cpu']>$limitMax['cpu']||$limit['memory']>$limitMax['memory']||$limit['process']>$limitMax['process']||$limit['space']>$limitMax['space']||$limit['db_space']>$limitMax['db_space']||$limit['upload']>$limitMax['upload']||$limit['download']>$limitMax['download']||$limit['process_time']>$limitMax['process_time']||$limit['task']>$limitMax['task']){
                exitByError(4,"超出可设置的最大值");
            }
            else{
                $sql="UPDATE `user` SET `cpu`='".$limit['cpu']."',`memory`='".$limit['memory']."',`process`='".$limit['process']."',`space`='".$limit['space'].",`task`='".$limit['task']."',`db_space`='".$limit['db_space']."',`net_upload`='".$limit['upload']."',`net_download`='".$limit['download']."',`process_time`='".$limit['process_time']."' WHERE `id`='".$id."'";
                $result=$this->database->query($sql);
                $_SESSION["update"]=true;
            }
        }
    }
    public function modifyPassword($id,$password){
        $userT=new User($id);
        if(!$userT->userInfo){
                exitByError(2,"用户不存在");
            }
        if($userT->userInfo['permission']<$this->userInfo['permission']){
            exitByError(64,"无权限进行操作");
        }
        else{
            $sql="UPDATE `user` SET `password`='".md5(MD5_SALT.$password)."' WHERE `id`='".$id."'";
            $result=$this->database->query($sql);
            $sql="SET PASSWORD FOR '".DATABASE_USER.$this->userInfo['username']."'@'localhost' = PASSWORD('".$password."')";
            $this->database->query($sql);
            $_SESSION["update"]=true;
        }
    }
    public function uploadHeader(){
        $upload=new Upload("header");
        $name=md5($this->userInfo['username'].time()).".png";
        $result=$upload->uploadFile(
            ROOT_USER_HEADER,
            204800,
            array(
                "image/jpeg",
                "image/jpg",
                "image/pjpeg",
                "image/x-png",
                "image/png"
            )
            ,$name);
        if(!$result[0]['successed'])exitByError(-7,$result[0]['msg']);
        $sql="UPDATE `user` SET `header`='".$name."' WHERE `id`='".$this->userInfo['id']."'";
        $this->database->query($sql);
        $_SESSION["update"]=true;
        return $name;
    }
    public function editProfile($id,$profile){
        if($this->userInfo['permission']>1){
            exitByError(64,"无权限进行操作");
        }
        else{
            $userT=new User($id);
            if(!$userT->userInfo){
                exitByError(2,"用户不存在");
            }
            if($userT->userInfo['permission']<$this->userInfo['permission']){
                exitByError(64,"无权限进行操作");
            }
            $sql="UPDATE `user` SET `nickname`='".$profile['nickname']."',`email`='".$profile['email']."',`phone`='".$profile['phone']."' WHERE `id`='".$id."'";
            $result=$this->database->query($sql);
            $_SESSION["update"]=true;
        }
    }
    public function delete($id){
        if($this->userInfo['permission']>1){
            exitByError(64,"无权限进行操作");
        }
        else{
            $userT=new User($id);
            if(!$userT->userInfo){
                exitByError(2,"用户不存在");
            }
            if($userT->userInfo['permission']<=$this->userInfo['permission']){
                exitByError(64,"无权限进行操作");
            }
            $sql="DELETE FROM `user` WHERE `id`='".$id."'";
            $result=$this->database->query($sql);
        }
    }
    public function login($password){
        $_SESSION['user']=null;
        $this->isLogin=false;
        if(!$this->userInfo)exitByError(2,"用户不存在");
        $md5pass=md5(MD5_SALT.$password);
        if($md5pass!=$this->userInfo['password'])exitByError(1,"密码错误，登录失败");
        $sql="UPDATE `user` SET `lastlogin`='".$_SERVER["REMOTE_ADDR"]."',`logintime`=NOW() WHERE `id`='".$this->userInfo['id']."'";
        $result=$this->database->query($sql);
        $_SESSION['user']=$this->userInfo;
        $this->isLogin=true;
    }
    public function logout(){
        $_SESSION['user']=null;
        $this->isLogin=false;
    }
}
?>
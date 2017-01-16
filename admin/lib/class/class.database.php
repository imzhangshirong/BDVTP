<?php
class DataBase{
    public $mysqli;
    public function __construct() {
        $this->mysqli=new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
        if($this->mysqli->connection_errno){
            exitByError(-4,"数据库错误：连接数据库失败");
        }
    }
    public function query($sql,$autoExit = true){
        $result=$this->mysqli->query($sql);
        if(!$result){
            if($autoExit)exitByError(-4,"数据库错误：".$this->mysqli->error);
        }
        return $result;
    }
    public function fetchAll($result,$resulttype = MYSQLI_NUM)
    {
        if (method_exists('mysqli_result', 'fetch_all')) # Compatibility layer with PHP < 5.3
            $res = $result->fetch_all($resulttype);
        else
            for ($res = array(); $tmp = $result->fetch_array($resulttype);) $res[] = $tmp;
        return $res;
    }
    public function __destruct(){
        $this->mysqli->close();
    }
}
?>
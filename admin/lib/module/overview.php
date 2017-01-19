<?php
class Overview{
    private $system;
    public function __construct(){
        $this->system=new System();
    }
    public function getCpu(){
        return array(
            'info'=>$this->system->getCpuInfo(),
            'usage'=>$this->system->getCpuUsage(),            
        );
    }
    public function getCpuInfo(){
        return $this->system->getCpuInfo();
    }
    public function getCpuUsage(){
        return $this->system->getCpuUsage();
    }
    public function getProcess(){
        return $this->system->getProcess();
    }
    public function getSystemLoad(){
        return $this->system->getSystemLoad();
    }
    public function getMemory(){
        return $this->system->getMemory();
    }
    public function getSpace(){
        return $this->system->getSpace();
    }
    public function getNet(){
        return $this->system->getNet();
    }
    public function getNetSpeed(){
        return $this->system->getNetSpeed();
    }
    public function getAll(){
        return array(
            'cpu'=>$this->getCpu(),         
            'memory'=>$this->getMemory(),         
            'net'=>$this->getNet(),         
            'net_speed'=>$this->getNetSpeed(),         
        );
    }
}
?>
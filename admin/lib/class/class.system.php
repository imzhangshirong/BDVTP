<?php
#class System{
    function getCpuInfo(){
        if (false === ($str=@file("/proc/cpuinfo"))) return false;
        $str=implode("", $str);
        @preg_match_all("/model\s+name\s{0,}\:+\s{0,}([\w\s\)\(\@.-]+)([\r\n]+)/s", $str, $model);
        @preg_match_all("/cpu\s+MHz\s{0,}\:+\s{0,}([\d\.]+)[\r\n]+/", $str, $mhz);
        @preg_match_all("/cache\s+size\s{0,}\:+\s{0,}([\d\.]+\s{0,}[A-Z]+[\r\n]+)/", $str, $cache);
        @preg_match_all("/bogomips\s{0,}\:+\s{0,}([\d\.]+)[\r\n]+/", $str, $bogomips);
        $res=array();
        if (false !== is_array($model[1])){
            for($i=0; $i < sizeof($model[1]); $i++){
                $temp=array();
                $temp['model']=$model[1][$i];
                $temp['mhz']=$mhz[1][$i];
                $temp['cache']=$cache[1][$i];
                $temp['bogomips']=$bogomips[1][$i];
                $res[]=$temp;

            }
            
        }
        return $res;
    }
    function getCpuUsage(){
        $strtop=system("top -bn 1 |grep -E '^(%Cpu|Cpu)'");
        $strtop=substr($strtop,strpos($strtop,":")+1);
        echo $strtop;
        $cpu=explode(",",$strtop);
        $n=count($cpu);
        $cpuUse=array();
        for($a=0;$a<$n;$a++){
            $cpu[$a]=trim($cpu[$a]);
            $p=strpos($cpu[$a]," ");
            $type=substr($cpu[$a],$p+1);
            $value=substr($cpu[$a],0,$p);
            $cpuUse[$type]=$value;
        }
        return $cpuUse;
    }
    function getProcess(){
        $process=array();
        $data=array();
        $res = exec('ps aux',$data);
        for($a=1;$a<count($data);$a++){
            @preg_match_all("/(\S+)/", $data[$a], $b);
            $temp=array(
                'USER'=>$b[0][0],
                'PID'=>$b[0][1],
                'CPU'=>$b[0][2],
                'MEM'=>$b[0][3],
                'VSZ'=>$b[0][4],
                'RSS'=>$b[0][5],
                'TTY'=>$b[0][6],
                'STAT'=>$b[0][7],
                'START'=>$b[0][8],
                'TIME'=>$b[0][9],
                'COMMAND'=>substr($data[$a],strpos($data[$a]," ".$b[0][10])+1),
            );
            $process[]=$temp;
        }
        $data=array();
        $res = exec('ps -A l',$data);
        for($a=1;$a<count($data);$a++){
            @preg_match_all("/(\S+)/", $data[$a], $b);
            for($c=0;$c<count($process);$c++){
                $temp=$process[$c];
                if($temp['PID']==$b[0][2]){
                    $temp['F']=$b[0][0];
                    $temp['UID']=$b[0][1];
                    $temp['PPID']=$b[0][3];
                    $temp['PRI']=$b[0][4];
                    $temp['NI']=$b[0][5];
                    $temp['WCHAN']=$b[0][6];
                    $process[$c]=$temp;
                    break;
                }
            }
        }
        return $process;
    }
    function getMemory(){
        if (false === ($str=@file("/proc/meminfo"))) return false;
        $str=implode("", $str);
        preg_match_all("/MemTotal\s{0,}\:+\s{0,}([\d\.]+).+?MemFree\s{0,}\:+\s{0,}([\d\.]+).+?Cached\s{0,}\:+\s{0,}([\d\.]+).+?SwapTotal\s{0,}\:+\s{0,}([\d\.]+).+?SwapFree\s{0,}\:+\s{0,}([\d\.]+)/s", $str, $buf);
        preg_match_all("/Buffers\s{0,}\:+\s{0,}([\d\.]+)/s", $str, $buffers);
        $res['total']=$buf[1][0];
        $res['free']=$buf[2][0];
        $res['buffers']=$buffers[1][0];
        $res['cached']=$buf[3][0];
        $res['used']=$res['total']-$res['free'];
        
        $res['realUsed']=$res['total'] - $res['free'] - $res['cached'] - $res['buffers']; //真实内存使用
        $res['realFree']=$res['total'] - $res['realUsed']; //真实空闲
        
        $res['swapTotal']=$buf[4][0];
        $res['swapFree']=$buf[5][0];
        $res['swapUsed']=$res['swapTotal']-$res['swapFree'];

        $res['swapPercent']=(floatval($res['swapTotal'])!=0)?round($res['swapUsed']/$res['swapTotal']*100,2):0;
        $res['percent']=(floatval($res['total'])!=0)?round($res['used']/$res['total']*100,2):0;
        $res['realPercent']=(floatval($res['total'])!=0)?round($res['realUsed']/$res['total']*100,2):0; //真实内存使用率
        $res['cachedPercent']=(floatval($res['cached'])!=0)?round($res['cached']/$res['total']*100,2):0; //Cached内存使用率

        $res['total']=array($res['total'],formatSizeUnit($res['total'],1));
        $res['free']=array($res['free'],formatSizeUnit($res['free'],1));
        $res['buffers']=array($res['buffers'],formatSizeUnit($res['buffers'],1));
        $res['cached']=array($res['cached'],formatSizeUnit($res['cached'],1));
        $res['used']=array($res['used'],formatSizeUnit($res['used'],1));
        $res['realUsed']=array($res['realUsed'],formatSizeUnit($res['realUsed'],1));
        $res['realFree']=array($res['realFree'],formatSizeUnit($res['realFree'],1));
        $res['swapTotal']=array($res['swapTotal'],formatSizeUnit($res['swapTotal'],1));
        $res['swapFree']=array($res['swapFree'],formatSizeUnit($res['swapFree'],1));
        $res['swapUsed']=array($res['swapUsed'],formatSizeUnit($res['swapUsed'],1));
        
        
        return $res;
    }
    function getSpace(){

    }
    function getNet(){
            
    }
    function formatSizeUnit($size,$o=0){
        $unit=array(" B"," KB"," MB"," GB"," TB");
        $value=$size;
        $a=$o;
        for($a=$o;$value>=1024;$a++){
            $value/=1024;
        }
        return round($value,2).$unit[$a];
    }
//print_r(getCpuUsage());

#}

$net=@file("/proc/net/dev");
for($a=2;$a<count($net);$a++){
    $type=substr($net[$a],0,strpos($net[$a],":"));
    $data=substr($net[$a],strpos($net[$a],":")+1);
    @preg_match_all("/(\S+)/", $data, $b);
    print_r($b[0]);
}

//print_r($net);

?>
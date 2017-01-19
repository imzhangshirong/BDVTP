<?php
class System{
    public function getCpuInfo(){
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
                $temp['model']=trim($model[1][$i],"\n\r ");
                $temp['mhz']=floatval(trim($mhz[1][$i],"\n\r "));
                
                $temp['cache']=trim($cache[1][$i],"\n\r ");
                $temp['bogomips']=trim($bogomips[1][$i],"\n\r ");
                $res[]=$temp;

            }
            
        }
        return $res;
    }

    function GetCoreInformation() {
        $data = @file('/proc/stat');
        $cores = array();
        foreach( $data as $line ) {
            if( preg_match('/^cpu[0-9]/', $line) ){
                $info = explode(' ', $line);
                $cores[]=array(
                    'user'=>$info[1],
                    'nice'=>$info[2],
                    'sys' => $info[3],
                    'idle'=>$info[4],
                    'iowait'=>$info[5],
                    'irq' => $info[6],
                    'softirq' => $info[7]
                );
            }
        }
        return $cores;
    }
    public function getCpuUsage(){
        $stat1 = $this->GetCoreInformation();
        sleep(1);
        $stat2 = $this->GetCoreInformation();
        $cpus=array();
        for( $i = 0, $l = count($stat1); $i < $l; $i++) {
            $dif = array();
            $dif['user'] = $stat2[$i]['user'] - $stat1[$i]['user'];
            $dif['nice'] = $stat2[$i]['nice'] - $stat1[$i]['nice'];
            $dif['sys'] = $stat2[$i]['sys'] - $stat1[$i]['sys'];
            $dif['idle'] = $stat2[$i]['idle'] - $stat1[$i]['idle'];
            $dif['iowait'] = $stat2[$i]['iowait'] - $stat1[$i]['iowait'];
            $dif['irq'] = $stat2[$i]['irq'] - $stat1[$i]['irq'];
            $dif['softirq'] = $stat2[$i]['softirq'] - $stat1[$i]['softirq'];
            $total = array_sum($dif);
            $cpu = array();
            foreach($dif as $x=>$y) $cpu[$x] = round($y / $total * 100, 2);
            $cpus[] = $cpu;
        }
        return $cpus;
    }
    public function getProcess(){
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
    public function getSystemLoad(){
        $uptimestr=exec("uptime");
        $uptimestr=substr($uptimestr,strpos($uptimestr,"load average:")+13);
        @preg_match_all("/([\d.]+)/", $uptimestr, $b);
        return $b[0];
    }
    public function getMemory(){
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

        $res['total']=array($res['total']."",formatSizeUnit($res['total'],1));
        $res['free']=array($res['free']."",formatSizeUnit($res['free'],1));
        $res['buffers']=array($res['buffers']."",formatSizeUnit($res['buffers'],1));
        $res['cached']=array($res['cached']."",formatSizeUnit($res['cached'],1));
        $res['used']=array($res['used']."",formatSizeUnit($res['used'],1));
        $res['realUsed']=array($res['realUsed']."",formatSizeUnit($res['realUsed'],1));
        $res['realFree']=array($res['realFree']."",formatSizeUnit($res['realFree'],1));
        $res['swapTotal']=array($res['swapTotal']."",formatSizeUnit($res['swapTotal'],1));
        $res['swapFree']=array($res['swapFree']."",formatSizeUnit($res['swapFree'],1));
        $res['swapUsed']=array($res['swapUsed']."",formatSizeUnit($res['swapUsed'],1));
        
        
        return $res;
    }
    public function getSpace(){
        $sizeTotal=@disk_total_space("/home");
        $sizeFree=@disk_free_space("/home");
        $sizeUsed=$sizeTotal-$sizeFree;
        return array(
            'total'=>array($sizeTotal."",formatSizeUnit($sizeTotal)),
            'free'=>array($sizeFree."",formatSizeUnit($sizeFree)),
            'used'=>array($sizeUsed."",formatSizeUnit($sizeUsed)),
            'percent'=>round($sizeUsed/$sizeTotal*100,2)
        );
    }
    public function getNet(){
        $net=array();
        $netdata=@file("/proc/net/dev");
        for($a=2;$a<count($netdata);$a++){
            $name=trim(substr($netdata[$a],0,strpos($netdata[$a],":")));
            $data=substr($netdata[$a],strpos($netdata[$a],":")+1);
            @preg_match_all("/(\S+)/", $data, $b);
            $temp=array(
                'name'=>$name,
                'receive'=>array(
                    'bytes'=>$b[0][0],
                    'packets'=>$b[0][1]
                ),
                'transmit'=>array(
                    'bytes'=>$b[0][8],
                    'packets'=>$b[0][9]
                )
            );
            $net[]=$temp;

        }
        return $net;
    }
    public function formatSizeUnit($size,$o=0){
        $unit=array(" B"," KB"," MB"," GB"," TB");
        $value=$size;
        $a=$o;
        for($a=$o;$value>=1024;$a++){
            $value/=1024;
        }
        return round($value,2).$unit[$a];
    }
    public function getNetSpeed(){
        $old=$this->getNet();
        $d_time=1;
        sleep($d_time);
        $net=$this->getNet();
        $speed=array();
        for($a=0;$a<count($net);$a++){
            for($b=0;$b<count($old);$b++){
                if($net[$a]['name']==$old[$b]['name']){
                    $d_upload=$net[$a]['transmit']['bytes']-$old[$b]['transmit']['bytes'];
                    $d_download=$net[$a]['receive']['bytes']-$old[$b]['receive']['bytes'];
                    if($d_upload<0)$d_upload=0;
                    if($d_download<0)$d_download=0;
                    $download=$d_download/$d_time;
                    $upload=$d_upload/$d_time;                    
                    $speed[]=array(
                        'name'=>$net[$a]['name'],
                        'download'=>array($download,formatSizeUnit($download,0)."/s"),
                        'upload'=>array($upload,formatSizeUnit($upload,0)."/s"),
                    );
                    break;
                }
            }
        }
        return $speed;
    }
}

?>
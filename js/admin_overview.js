var cpuChart = echarts.init($("#overview_cpu .graph")[0]);
var blankData=[];
for(var a=0;a<20;a++)blankData[a]=0;
var optionCpu = {
    title:{
        right:30,
        textStyle:{fontSize:14,fontWeight:400}
    },
    color:[
        "rgba(65, 160, 221,0.5)",
        "rgba(85, 171, 128,0.5)",
        "rgba(102, 153, 204,0.5)",
    ],
    animation:false,
    tooltip: {
        trigger: 'axis',
    },
    grid:{
        x:30,y:10,x2:20,y2:10
    },
    xAxis: {
        show : false,
        type : 'category',
        splitLine: {
            show: false
        },
        data:blankData.concat()
    },
    yAxis: {
        type: 'value',
        splitLine: {
            show: false
        },
        max:100
    },
    series: []
};
var templateCpu=function(data){return '<div class="cpu_core"><p class="title title5 model">'+data.model+'</p><div><p class="titlep1"><span class="titlebig1 mhz">'+data.mhz+'</span>MHz</p></div><div><p class="text titlep1">Cache：<span class="cache">'+data.cache+'</span></p><p class="text titlep1">BogoMIPS：<span class="bogomips">'+data.bogomips+'</span></p></div></div>';};
function updateDataCpu(ele,data,data2){
    for(var a=0;a<ele.length && a<data.length;a++){
        var keys=Object.keys(data[a]);
        for(var b=0;b<keys.length;b++){
            $(ele[a]).find("."+keys[b]).text(data[a][keys[b]]);
        }
    }
    var time=new Date();
    optionCpu.xAxis.data.push(time.toLocaleTimeString());
    optionCpu.title.text="";
    for(var a=0;a<data2.length;a++){
        var temp=parseFloat(data2[a].user)+parseFloat(data2[a].sys);
        temp=Math.round(temp*100)/100;
        optionCpu.series[a].data.push(temp);
        optionCpu.title.text+="cpu"+a+":"+temp+"%   ";
        if(optionCpu.series[a].data.length>30){
            optionCpu.series[a].data.shift();
        }
    }
    if(optionCpu.xAxis.data.length>30){
        optionCpu.xAxis.data.shift();
    }
    cpuChart.setOption(optionCpu, true);
}
function initDataCpu(data){
    optionCpu.series=[];
    for(var a=0;a<data.length;a++){
        $("#overview_cpu .status").append(templateCpu(data[a]));
        optionCpu.series.push({
            name: 'cpu'+a,
            type: 'line',
            smooth:true, 
            data: blankData.concat(),
            areaStyle: {normal: {}},
            lineStyle: {normal: {}},

        });
    }
}
function updateCpu(){
    BDVTP.api({
        api:"overview",
        action:"cpu"
    },function(resp){
        if(BDVTP.current!="overview")return;
        var info=resp.data.info;
        var usage=resp.data.usage;
        var cpuNum=$("#overview_cpu .status .cpu_core").length;
        if(cpuNum==0){
            initDataCpu(info);
        }
        else if(cpuNum!=info.length){
            $("#overview_cpu .status").html();
            initDataCpu(info);
        }
        else{
            updateDataCpu($("#overview_cpu .status .cpu_core"),info,usage);

        }
    });
}
updateCpu();
var timerCpu=setInterval(function(){
    if(BDVTP.current!="overview"){
        clearInterval(timerCpu);
        return;
    }
    updateCpu();
},2000);


// 内存详情//////////////
var memoryChartRAM = echarts.init($("#overview_memory .graph")[0]);
var optionMemoryRAM = {
    title:{
        text: '物理内存： 10 GB /12 GB',
        left: 'center',
        top:'bottom',
        textStyle:{fontWeight:300,fontSize:14}
    },
    grid:{x:0,y:0,x2:0,y2:10},
    series: [
        {
            radius:'90%',
            center:['50%','55%'],
            name: 'RAM',
            type: 'gauge',
            axisLine: {            // 坐标轴线
                lineStyle: {       // 属性lineStyle控制线条样式
                    width: 6
                },
                color:[[0, '#91c7ae'], [0.8, '#63869e'], [1, '#c23531']]
            },
            axisTick: {            // 坐标轴小标记
                show:false,
                length: 8,        // 属性length控制线长
                lineStyle: {       // 属性lineStyle控制线条样式
                    color: 'auto',
                }
            },
            splitLine: {           // 分隔线
                show:false,
                length: 8,         // 属性length控制线长
                lineStyle: {       // 属性lineStyle（详见lineStyle）控制线条样式
                    color: 'auto',
                }
            },
            pointer: {width:5},
            detail: {formatter:'{value}%',textStyle:{fontSize:20}},
            data: [{value: 0,name:"RAM"}]
        }
    ]
};
memoryChartRAM.setOption(optionMemoryRAM,true);
var memoryChartSwap = echarts.init($("#overview_memory .graph")[1]);
var optionMemorySwap = {
    title:{
        text: 'Swap交换： 100 MB/5 GB',
        left: 'center',
        top:'bottom',
        textStyle:{fontWeight:300,fontSize:14}
    },
    grid:{x:0,y:0,x2:0,y2:10},
    series: [
        {
            radius:'90%',
            center:['50%','50%'],
            name: 'Swap',
            type: 'gauge',
            axisLine: {            // 坐标轴线
                lineStyle: {       // 属性lineStyle控制线条样式
                    width: 6
                }
            },
            axisTick: {            // 坐标轴小标记
                show:false,
                length: 8,        // 属性length控制线长
                lineStyle: {       // 属性lineStyle控制线条样式
                    color: 'auto',
                }
            },
            splitLine: {           // 分隔线
                show:false,
                length: 8,         // 属性length控制线长
                lineStyle: {       // 属性lineStyle（详见lineStyle）控制线条样式
                    color: 'auto',
                }
            },
            pointer: {width:5},
            detail: {formatter:'{value}%',textStyle:{fontSize:20}},
            data: [{value: 0,name:"Swap"}]
        }
    ]
};
memoryChartSwap.setOption(optionMemorySwap,true);
function updateMemory(){
    BDVTP.api({
        api:"overview",
        action:"memory"
    },function(resp){
        if(BDVTP.current!="overview")return;
        optionMemoryRAM.title.text="物理内存："+resp.data.used[1]+"/"+resp.data.total[1];
        optionMemoryRAM.series[0].data[0].value=resp.data.percent;
        optionMemorySwap.title.text="Swap交换："+resp.data.swapUsed[1]+"/"+resp.data.swapTotal[1];
        optionMemorySwap.series[0].data[0].value=resp.data.swapPercent;
        memoryChartRAM.setOption(optionMemoryRAM,true);
        memoryChartSwap.setOption(optionMemorySwap,true);
    });
    
}
updateMemory();
var timerMemory=setInterval(function(){
    if(BDVTP.current!="overview"){
        clearInterval(timerMemory);
        return;
    }
    updateMemory();
},10000);


//磁盘空间////////
var spaceChart = echarts.init($("#overview_space .graph")[0]);
var optionSpace = {
    title: {
        text: '磁盘空间：65 GB/100 GB',
        left: 'center',
        top:'bottom',
        textStyle:{fontWeight:300,fontSize:14}
    },
    tooltip : {
        trigger: 'item',
        formatter: "{a} <br/>{b} : {c} ({d}%)"
    },
    series : [
        {
            name:'磁盘空间',
            type:'pie',
            radius : '75%',
            center: ['50%', '50%'],
            data:[
                {value:0, name:'已用',itemStyle:{normal: {color:'#5aa9dc'}}},
                {value:100, name:'空闲',itemStyle:{normal: {color:'#dedede'}}},
                
            ],
            animationType: 'scale',
            animationEasing: 'elasticOut',
        }
    ]
};
spaceChart.setOption(optionSpace,true);
function updateSpace(){
    BDVTP.api({
        api:"overview",
        action:"space"
    },function(resp){
        if(BDVTP.current!="overview")return;        
        optionSpace.title.text="磁盘空间："+resp.data.used[1]+"/"+resp.data.total[1];
        optionSpace.series[0].data[0].value=resp.data.used[0];
        optionSpace.series[0].data[1].value=resp.data.free[0];
        spaceChart.setOption(optionSpace,true);
    });
}
updateSpace();
var timerSpace=setInterval(function(){
    if(BDVTP.current!="overview"){
        clearInterval(timerSpace);
        return;
    }
    updateSpace();
},60000);



//网络/////////
var optionNetTemplate = {
    title:{
        right:30,
        textStyle:{fontSize:14,fontWeight:400}
    },
    color:[
        "rgba(65, 160, 221,0.5)",
        "rgba(85, 171, 128,0.5)",
    ],
    animation:false,
    tooltip: {
        trigger: 'axis',
    },
    grid:{
        x:50,y:10,x2:20,y2:10
    },
    xAxis: {
        show : false,
        type : 'category',
        splitLine: {
            show: false
        },
        data:blankData.concat()
    },
    yAxis: {
        type: 'value',
        splitLine: {
            show: false
        },
        axisLabel:{
            formatter:function(value){
                function unit(size){
                    var size_=parseFloat(size);
                    var unitS=['B','K','M','G'];
                    var index=0;
                    while(size_>1024){
                        size_/=1024;
                        index++;
                    }
                    size_=Math.round(size_*10)/10;
                    return size_+unitS[index];
                }
                return unit(value);
            }
        }
    },
    series: [
        {
            name: '下载速度',
            type: 'line',
            smooth:true, 
            data: blankData.concat(),
            areaStyle: {normal: {}},
            lineStyle: {normal: {}},
                        
        },
        {
            name: '上传速度',
            type: 'line',
            smooth:true, 
            data: blankData.concat(),
            areaStyle: {normal: {}},
            lineStyle: {normal: {}},
                        
        }
    ]
};
var netChart=[];
var optionNet=[];
var templateNet=function(data){return '<div class="graph"></div>';};
function updateDataNet(data){
    var time=new Date();
    for(var a=0;a<data.length;a++){
        optionNet[a].xAxis.data.push(time.toLocaleTimeString());
        optionNet[a].title.text="上传:"+data[a].upload[1]+"   下载:"+data[a].download[1]+" < "+data[a].name;
        var download=data[a].download[0];
        var upload=data[a].upload[0];
        optionNet[a].series[0].data.push(download);
        optionNet[a].series[1].data.push(upload);
        if(optionNet[a].series[0].data.length>30){
            optionNet[a].series[0].data.shift();
            optionNet[a].series[1].data.shift();
        }
        if(optionNet[a].xAxis.data.length>30){
            optionNet[a].xAxis.data.shift();
        }
        netChart[a].setOption(optionNet[a]);
    }
}
function initDataNet(data){
    netChart=[];
    optionNet=[];
    for(var a=0;a<data.length;a++){
        $("#overview_net").append(templateNet(data[a]));
    }
    var all=$("#overview_net .graph");
    for(var a=0;a<data.length;a++){
        netChart.push(echarts.init(all[a]));
        optionNet.push(jQuery.extend(true, {}, optionNetTemplate));
    }
}
function updateNet(){
    BDVTP.api({
        api:"overview",
        action:"netSpeed"
    },function(resp){
        if(BDVTP.current!="overview")return;
        var netNum=$("#overview_net .graph").length;
        if(netNum==0){
            initDataNet(resp.data);
        }
        else if(netNum!=resp.data.length){
            $("#overview_net .graph").remove();
            initDataNet(resp.data);
        }
        else{
            updateDataNet(resp.data);
        }
    });
}
updateNet();
var timerNet=setInterval(function(){
    if(BDVTP.current!="overview"){
        clearInterval(timerNet);
        return;
    }
    updateNet();
},3000);
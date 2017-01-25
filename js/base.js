var BDVTP={
    containerId:"admin_panel_operation",
    current:undefined,
    load:function(m){
        var ele=document.getElementById(BDVTP.containerId);
        BDVTP.current=m;
        ele.innerHTML='<div class="load"><div class="loader"></div><span>Loading...</span></div>';
        $.ajax({
            url:"./admin/api.php",
            type:"get",
            data:{
                api:"frame",
                action:m,
            },
            success:function(resp){
                
                if(!ele)return;
                if(BDVTP.current!=m)return;
                ele.innerHTML=resp;
                var scripts=ele.getElementsByTagName("script");
                var scriptsNew=[];
                for(var a=0;a<scripts.length;a++){
                    var script=document.createElement("script");
                    script.src=scripts[a].src;
                    script.innerHTML=scripts[a].innerHTML;
                    scriptsNew.push(script);
                    scripts[a].remove();
                }
                for(var a=0;a<scriptsNew.length;a++){
                    ele.appendChild(scriptsNew[a]);
                    eval(scriptsNew[a].innerHTML);
                }
            }
        });
    },
    api:function(data,resp_call){
        $.ajax({
            url:"./admin/api.php?admin/api.php",
            type:"get",
            data:data,
            success:function(resp){
                if(resp.code!=0){
                    if(resp.msg){alert(resp.msg);}
                    return;
                }
                if(resp_call)resp_call(resp);
            }
        });
    }
};
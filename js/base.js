var BDVTP={
    containerId:"admin_panel_operation",
    current:undefined,
    submitProcess:0,
    submitCount:0,
    submitComplete:undefined,
    readySubmit:function(complete){
        this.submitCount=0;
        this.submitProcess=0;
        this.submitComplete=complete;
    },
    load:function(m,target){
        var ele=$("#"+BDVTP.containerId);
        if(target)ele=target;
        BDVTP.current=m;
        ele.html('<div class="load"><div class="loader"></div><span>Loading...</span></div>');
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
                ele.html(resp);
            }
        });
    },
    refresh:function(){
        if(this.current)this.load(this.current);
    },
    api:function(api,resp_call){
        $.ajax({
            url:"./admin/api.php",
            type:"get",
            data:api,
            success:function(resp){
                if(resp.code!=0){
                    if(resp.msg){alert(resp.msg);}
                    return;
                }
                if(resp_call)resp_call(resp);
            }
        });
    },
    submit:function(api,data,resp_call){
        var url="./admin/api.php?";
        var keys=Object.keys(api);
        for(var a=0;a<keys.length;a++){
            url+=keys[a]+"="+api[keys[a]];
            if(a<keys.length-1)url+="&";
        }
        this.submitCount++;
        $.ajax({
            url:url,
            type:"post",
            data:data,
            success:function(resp){
                if(resp.code!=0){
                    if(resp.msg){alert(resp.msg);}
                    return;
                }
                BDVTP.submitProcess++;
                if(resp_call)resp_call(resp);
                if(BDVTP.submitProcess>=BDVTP.submitCount){
                    if(BDVTP.submitComplete)BDVTP.submitComplete();
                    BDVTP.submitComplete=undefined;
                }
            }
        });
    },
    checkData:function(type,data){
        var match=undefined;
        switch(type){
            case "email":
                match=/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/g;
                break;
            case "phone":
                match=/^(13[0-9]|14[5|7]|15[0|1|2|3|5|6|7|8|9]|18[0|1|2|3|5|6|7|8|9])\d{8}$/g;
                break;
            case "letter+number":
                match=/(^[a-zA-Z0-9]*$)/g;
                break;
            case "legalString":
                match=/(^[a-zA-Z _0-9\u4e00-\u9fa5]*$)/g;
                break;
            case "int":
                match=/^-?[1-9]\d*$/g;
                break;
            case "float":
                match=/^[-]?[1-9]\d*[\.]?\d*|0[\.]?\d*[1-9]\d*$/g;
                break;
            case "+int":
                match=/^[1-9]\d*$/g;
                break;
            case "+float":
                match=/^[1-9]\d*[\.]?\d*|0[\.]?\d*[1-9]\d*$/g;
                break;
            case "-int":
                match=/^-[1-9]\d*$/g;
                break;
            case "-float":
                match=/^[-][1-9]\d*[\.]?\d*|0[\.]?\d*[1-9]\d*$/g;
                break;
        }
        return match.test(data);
    }
};
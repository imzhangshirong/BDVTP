BDVTP.api({
    api:"user",
    action:"info"
},function(resp){
    var headerFile=resp.data.headerFile;
    $("#user_header").css("backgroundImage","url('"+resp.data.header+"')");
    $("#user_info_username").text(resp.data.username+"（"+resp.data.permission.name+"）");
    $("#user_info_nickname").val(resp.data.nickname);
    $("#user_info_email").val(resp.data.email);
    $("#user_info_phone").val(resp.data.phone);
    $("#user_limit_cpu").text(resp.data.permission.cpu+" %");
    $("#user_limit_mem").text(resp.data.permission.memory+" MB");
    $("#user_limit_space").text(resp.data.permission.space+" MB");
    $("#user_limit_task").text(resp.data.permission.task);
    $("#user_limit_process").text(resp.data.permission.process);
    $("#user_limit_process_time").text(resp.data.permission.process_time+" min");
    $("#user_limit_upload").text(resp.data.permission.upload+" KB/s");
    $("#user_limit_download").text(resp.data.permission.download+" KB/s");
    $("#user_header").bind("click",function(){
        $("#user_header_upload input").remove();
        var input=$("<input name='header' type='file'/>");
        $("#user_header_upload").append(input);
        input.bind("change",function(){
            var ajaxFormOption = {
                type: "post",  //提交方式  
                dataType: 'json', //数据类型
                resetForm: true, 
                url: "./admin/api.php?api=user&action=uploadHeader", //请求url  
                success: function (resp) { //提交成功的回调函数  
                    if(resp.code!=0){
                        if(resp.msg){alert(resp.msg);}
                        return;
                    }
                    else{
                        $("#user_header").css("backgroundImage","url('"+resp.data.header+"')");
                        alert("上传成功");
                    }
                }
            };
            $("#user_header_upload").ajaxSubmit(ajaxFormOption);
        })
        input.click();
        
    });
    $("#user_modify_button").bind("click",function(){
        if(!BDVTP.checkData("legalString",$("#user_info_nickname").val())){
            alert("昵称不合法");return;
        }
        if(!BDVTP.checkData("email",$("#user_info_email").val())){
            alert("邮箱不合法");return;
        }
        if(!BDVTP.checkData("phone",$("#user_info_phone").val())){
            alert("手机号不合法");return;
        }
        var pass=$("#user_info_password").val();
        BDVTP.readySubmit(function(){
            alert("修改成功");
            BDVTP.refresh();
        });
        if(pass.length>0){
            if(pass.length<8){
                alert("密码至少8位");return;
            }
            BDVTP.submit({
                api:"user",
                action:"modifyPass",
                sid:BDVTP.sid
            },{
                password:pass
            });
        }
        BDVTP.submit({
            api:"user",
            action:"edit",
            sid:BDVTP.sid
        },{
            nickname:$("#user_info_nickname").val(),            
            email:$("#user_info_email").val(),
            phone:$("#user_info_phone").val(),
            header:headerFile
        });
    });
});
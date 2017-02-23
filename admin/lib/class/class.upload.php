<?php
class Upload{
    private $uploadField;
    public function __construct($field){
        $this->uploadFile=$field;
    }
    public function uploadFile($root,$fileSize,$types,$newFileName){
        $root_=$root;
        $success=true;
        if(substr($root_,-1)!="/")$root_.="/";
        $files=array();
        $fileField=$_FILES[$this->uploadFile];
        if(is_string($fileField['name'])){
            $files[]=$fileField;
        }
        elseif(is_array($fileField['name'])){  
            foreach($fileField['name'] as $key=>$val){
                $temp=array(
                    'name'=>$fileField['name'][$key],
                    'type'=>$fileField['type'][$key],
                    'tmp_name'=>$fileField['tmp_name'][$key],
                    'error'=>$fileField['error'][$key],
                    'size'=>$fileField['size'][$key],
                );
                $files[]=$temp;
            }
        }
        if(isset($newFileName)){
            if(isset($files[0]) && $files[0]['error']==UPLOAD_ERR_OK){
                if($files[0]['size']<=$fileSize){
                    $canNext=false;
                    if(isset($types) && $types!=null && count($types)>0){
                        for($b=0;$b<count($types);$b++){
                            if($files[0]['type']==$types[$b]){
                                $canNext=true;
                                break;
                            }
                        }
                    }
                    else{
                        $canNext=true;
                    }
                    if($canNext){
                        if(!move_uploaded_file($files[0]['tmp_name'],$root_.$newFileName)){
                            $success=false;
                            $files[0]['successed']=false;
                            $files[0]['msg']="上传文件失败";
                        }
                        else{
                            $files[0]['successed']=true;
                            $files[0]['msg']="成功";
                        }
                    }
                    else{
                        $files[0]['successed']=false;
                        $files[0]['msg']="文件类型不符合";
                    }
                }
                else{
                    $files[0]['successed']=false;
                    $files[0]['msg']="文件大小超过限制";
                }
                unset($files[0]['tmp_name']);
            }
            else{
                $files[0]=array('successed'=>false);
                $files[0]['msg']="上传失败";
            }

        }
        else{
            for($a=0;$a<count($files);$a++){
                if($files[$a]['error']==UPLOAD_ERR_OK){
                    if($files[$a]['size']<=$fileSize){
                        $canNext=false;
                        if(isset($types)){
                            for($b=0;$b<count($types);$b++){
                                if($files[$a]['type']==$types[$b]){
                                    $canNext=true;
                                    break;
                                }
                            }
                        }
                        else{
                            $canNext=true;
                        }
                        if($canNext){
                            if(!move_uploaded_file($files[$a]['tmp_name'],$root_.$files[$a]['name'])){
                                $success=false;
                                $files[$a]['successed']=false;
                                $files[$a]['msg']="上传失败";
                            }
                            else{
                                $files[$a]['successed']=true;
                                $files[$a]['msg']="成功";
                            }
                        }
                        else{
                            $files[$a]['successed']=false;
                            $files[$a]['msg']="文件类型不符合";
                        }
                    }
                    else{
                        $files[$a]['successed']=false;
                        $files[$a]['msg']="文件大小超过限制";
                    }
                }
                else{
                    $files[$a]=array('successed'=>false);
                    $files[$a]['msg']="上传失败";
                }
                unset($files[$a]['tmp_name']);
            }
        }
        return $files;
    }
}
?>
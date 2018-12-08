<?php

namespace app\admin\model;

use think\Model;

class Form extends Model
{
    //设置表名
    protected $table = 'form';

    //关联表结构
    public function structure(){
        return $this->hasMany('FormStructure','form_id','Id');
    }
    //关联用户
    public function users(){
        return $this->hasMany('User','form_id','Id');
    }

    public function formAdd($question,$form){
        if ($this->save(['name'=>$question,'create_time'=>time()])){
            //表结构存储室type字段代表问题的类型，1代表单选，2代表多选，3代表单行文本框，4代表多行文本框
            if($this->structure()->saveAll($form)){
                //1代表存储成功
                return 1;
            }else{
                return '问卷结构存储失败';
            }
        }else{
            return '问卷存储失败';
        }
    }
}

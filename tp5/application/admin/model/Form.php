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

    public function formAdd($question,$form){
        if ($this->save(['question'=>$question,'time'=>time()])){
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

<?php

namespace app\user\model;

use think\Model;

class Form extends Model
{
    //设置表名以及主键
    protected $tabel='form';
    protected $pk   ='Id';

    //关联问卷结构
    public function formStructure(){
        return $this->hasMany('FormStructure','form_id','Id');
    }

    //关联用户
    public function users(){
        return $this->hasMany('User','form_id','Id');
    }
}

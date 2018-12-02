<?php

namespace app\admin\model;

use think\Model;

class Admin extends Model
{
    //定义表名
    protected $table='admin';

    public function login($data){
        $result = $this->where($data)->find();

        //只有账户的status字段的值为1，才代表用户的账户是正常可用的
        if($result['status']!=1){
            return '用户状态异常';
        }

        if ($result){
            return 0;
        }else{
            return '用户名或密码错误';
        }
    }

}

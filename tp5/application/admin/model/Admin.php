<?php

namespace app\admin\model;

use think\Model;
use think\facade\Session;

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
            //登录时将用户的数据存入session中
            Session::set('admin',$result['username']);
            return 1;
        }else{
            return '用户名或密码错误';
        }
    }

    public function passwordModify($password){
        //根据session中的username找到对应的用户
        $result = $this->where('username',Session::get('admin'))->find();
        $result['password'] = $password;
        if ($result->save()){
            //1代表密码修改成功
            return 1;
        }else{
            return '密码修改失败';
        }
    }

}

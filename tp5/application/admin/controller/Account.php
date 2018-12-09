<?php

namespace app\admin\controller;

use think\Controller;
use think\facade\Session;
use think\Request;

class Account extends Controller
{
    //在访问登录页面时先检查Session中是否已有用户数据，防止重复登录
    public function initialize(){
        if(Session::has('admin')){
            return $this->redirect('/admin/index/index');
        }
    }

    //后台登录
    public function login(Request $request){
        if ($request->isAjax()){
            //从前台接受账号登陆数据
            $data=[
                'username' => input('post.username'),
                'password' => input('post.password')
            ];
            //将数据交给模型检验并返回验证信息
            $result = model('admin')->login($data);
            //对结果进行处理，将数据返回前端
            if ($result==1){
                return json(['code'=>1,'msg'=>'登录成功','url'=>'/admin/index/index']);
            }else{
                return json(['code'=>0,'msg'=>$result]);
            }
        }
        return view();
    }

}

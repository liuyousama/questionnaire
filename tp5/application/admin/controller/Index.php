<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

class Index extends Controller
{
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
                return json(['code'=>1,'msg'=>'登录成功','url'=>'index']);
            }else{
                return json(['code'=>0,'msg'=>$result]);
            }
        }
        return view();
    }
}

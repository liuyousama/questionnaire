<?php

namespace app\admin\controller;

use think\Controller;
use think\facade\Session;
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

    //用户注销
    public function loginOut(Request $request){
        if ($request->isAjax()){
            Session::delete('admin');
            if(!Session::has('admin')){
                return json(['code'=>1,'msg'=>'注销成功','url'=>'login']);
            }else{
                return json(['code'=>0,'msg'=>'注销失败']);
            }
        }
    }

    //后台首页
    public function index(){
        //判断用户是否已经登录
        if(Session::has('admin')){
//            $data = model('form')->with('user')->all();
            $data = model('form')->all();
            //将数据赋给前端模板
            $this->assign('data',$data);
            return view();
        }else{
            //如果没有登录跳回登陆页面
            $this->redirect('admin/index/login');
        }
    }

    //用户修改密码
    public function passwordModify(Request $request){
        if ($request->isAjax()){
            //接收数据
            $password = input('post.password');
            //将数据交给模型处理,并接受返回信息
            $result = model('admin')->passwordModify($password);
            //对结果进行判断,并返回给前端特定数据
            if ($result==1){
                return json(['code'=>1,'msg'=>'密码修改成功']);
            }else{
                return json(['code'=>0,'msg'=>$result]);
            }
        }
    }

    //添加表单
    public function addForm(){
        return view();
    }
}

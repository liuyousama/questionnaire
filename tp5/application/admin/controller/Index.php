<?php

namespace app\admin\controller;

use app\admin\model\Form;
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
        //判断用户是否已经登录?
            $data = model('form')->with('users')->all();
//            $data = model('form')->all();
            //将数据赋给前端模板
            $this->assign('data',$data);
            return view();
//        }else{
//            //如果没有登录跳回登陆页面
//            $this->redirect('admin/index/login');
//        }
    }

    //查看问卷结果（详细信息）
    public function detail($id){
        //根据传进来的问卷的id来查询数据
        $form = model('Form')->with('structure')->with('users')->where('Id',$id)->find();
        //将用户的答案数据转换为一个数组
        foreach ($form['users'] as $user){
            $user['answer']=explode('&&',$user['answer']);
        }
        //定义一个变量，用来记录问题的个数
        $i = 1;
        //将$form['structure']作为待渲染数据，对数据做一系列的处理
        foreach ($form['structure'] as $item){
            if($item['type']==1){
                $item['choice']=explode('&&',$item['choice']);
                //定义一个数组，将本题所有的答案存入该数组中
                $temp=[];
                foreach ($form['users'] as $user){
                    array_push($temp,$user['answer'][$i]);
                }
                //将各个答案出现的次数存入带渲染数据中
                $item['count'] = array_count_values($temp);
                $i++;
            }elseif ($item['type']==2){
                $item['choice']=explode('&&',$item['choice']);
                //定义一个数组，将本题所有的答案存入该数组中
                $temp=[];
                foreach ($form['users'] as $user){
                    array_push($temp,$user['answer'][$i]);
                }
                //将各个答案出现的次数存入带渲染数据中
                $i++;
                //当问题类型不是选择型时（单选或多选），则不用对数据做任何处理，题号自增即可
            }else{
                $i++;
            }
        }
        $this->assign('data',$form['structure']);
        return view();
    }

    //用户修改密码
    public function passwordModify(Request $request){
        if ($request->isPost()){
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
    public function formAdd(Request $request){
        if ($request->isPost()){
            //从前台接收数据
            $question = input('post.title');
            $form = input('post.json');
            //将数据交给模型处理存进数据库，并接受返回信息
            $model = new Form;
            $result = $model->formAdd($question,$form);
            //返回特定数据给前台
            if ($result==1){
                return json(['code'=>1,'msg'=>'问卷创建成功']);
            }else{
                return json(['code'=>0,'msg'=>$result]);
            }
        }
        return $this->fetch();
    }

    public function test(){
        $a = ['1','1','2','4','5'];
        $this->assign('test',$a);
        return view();
    }
}

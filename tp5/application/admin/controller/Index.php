<?php

namespace app\admin\controller;

use app\admin\model\Form;
use think\Controller;
use think\facade\Session;
use think\Request;

class Index extends Controller
{
    //定义初始化方法，在用户执行方法之前都先检查session中是否有用户数据，以免没有登陆直接通过链接进入首页
    public function initialize(){
        if (!Session::has('admin')){
            return $this->redirect('admin/account/login');
        }
    }

    //后台首页
    public function index(){
            $data = model('form')->with('users')->all();
            //将数据赋给前端模板
            $this->assign('data',$data);
            return view();
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
                //将各个答案出现的次数存入待渲染数据中
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

    //删除问卷
    public function delete($id){
        if(model('Form')->get($id)->delete()){
            model('FormStructure')::destroy(model('FormStructure')->where('form_id',$id)->select());
            return $this->success('删除成功','admin/index/index',$wait=1.5);
        }
    }

    //用户注销
    public function loginOut(Request $request){
        if ($request->isAjax()){
            Session::delete('admin');
            if(!Session::has('admin')){
                return json(['code'=>1,'msg'=>'注销成功','url'=>'/admin/account/login']);
            }else{
                return json(['code'=>0,'msg'=>'注销失败']);
            }
        }
    }
}

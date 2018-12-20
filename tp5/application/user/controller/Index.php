<?php
namespace app\user\controller;

use think\Controller;
use think\Request;

class Index extends Controller
{
    //用户首页，展示所有的问卷
    public function index(){
        $form = model('Form')->all();
        $this->assign('form',$form);
        return view();
    }

    //问卷详情，用户可以在首页点击进入并作答
    public function detail($id,Request $request){
        if ($request->isPost()){
            //从前台接受生成数据
            $data = ['time'    => time(),
                     'form_id' => $id,
                     'ip'      => $request->host(),
                     'answer'  => input('post.answer')];
            //将数据传入模拟器验证，并接受返回结果
            $result = model('User')->answer($data);
            //对结果进行判断，并返回对应的数据给前端
            if ($result==1){
                return json(['code'=>1,'msg'=>'问卷提交成功！','url'=>'/user/index/index']);
            }else{
                return json(['code'=>0,'msg'=>$result]);
            }
        }
        $form = model('Form')->where('Id',$id)->with('FormStructure')->find();
        foreach ($form['FormStructure'] as $item) {
            if ($item['type'] == 1 || $item['type'] == 2) {
                $item['choice'] = explode('&&', $item['choice']);
            }
        }
        $this->assign('form',$form);
        $this->assign('formStructure',$form['FormStructure']);
        return view();
    }
}

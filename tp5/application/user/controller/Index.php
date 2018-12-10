<?php
namespace app\user\controller;

use think\Controller;

class Index extends Controller
{
    //用户首页，展示所有的问卷
    public function index(){
        $form = model('Form')->with('FormStructure')->all();
        $this->assign('form',$form);
        return view();
    }
}

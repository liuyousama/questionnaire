<?php

namespace app\user\model;

use think\facade\Cookie;
use think\Model;

class User extends Model
{
    //表名
    protected $table='user';

    //用户提交问卷的答案
    public function answer($data){
        //查询所有提交该问卷的用户ip
        $users = $this->where('form_id',$data['form_id'])->column('ip');
        //如果ip地址相同，说明该设备已经提交过问卷
        if (in_array($data['ip'],$users)){
            return '同一问卷不可重复提交!!!';
        }
        //检查cookie，form_status为0代表该设备已经提交过问卷
        if (Cookie::has('form_status')&&Cookie::get('form_status')==0){
            return '同一问卷不可重复提价!!!';
        }
        //存储数据
        if($this->save($data)){
            //将用户已经提交过问卷的状态永久存入cookie
            Cookie::forever('form_id',0);
            return 1;
        }else{
            return '未知错误，问卷提交失败';
        }
    }
}

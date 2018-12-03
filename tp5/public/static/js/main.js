$(function () {
    $("#modify").click(function (e) {
        e.preventDefault();
        $(".panel").slideUp();
        $(".modify-panel").slideDown();
    });

    $("#index").click(function (e) {
        e.preventDefault();
        $(".panel").slideUp();
        $(".index-panel").slideDown();
    });

    $("#loginOut").click(function (e) {
        e.preventDefault();
        $.ajax({
            url:'loginOut',
            type: 'post',
            success:function (data) {
                if (data.code==1){
                    layer.msg(data.msg,{
                        icon:6,
                        time:1500
                    },function () {
                        location.href = data.url;
                    });
                } else{
                    layer.open({
                       title:'注销失败',
                       content:data.msg,
                       icon:5,
                       anim:6
                    });
                }
            }
        });
    });

    $("#modify-btn").click(function () {
        if($("#password").val()!=$("#confirm").val()){
            layer.open({
                title:'修改密码失败',
                content:'两次输入密码不一致！！',
                icon:5,
                anim:6
            });
            return false;
        }
        $.ajax({
            url:'passwordModify',
            type:"post",
            data:$("#modify-form").serialize(),
            dataType:'json',
            success:function (data) {
                if (data.code==1){
                    layer.msg(data.msg,{
                        icon:6,
                        time:1500
                    },function () {
                        location.reload();
                    });
                } else{
                    later.open({
                        title:'修改密码失败',
                        content:data.msg,
                        icon:5,
                        anim:6
                    });
                }
            }
        });
    });
});
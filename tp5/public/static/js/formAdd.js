var count = 1;

$("#single").click(function(e){
    e.preventDefault();
    $(".panel-body").append("<form class='form-group single'><hr style='height: 2px;border:none;border-top: 2px solid;color: #337ab7'><label>"+count+":请输入问题：</label><input type='text' class='form-control' placeholder='请输入问题'><br><button class='btn btn-primary btn-add' type='button'>添加选项</button><button class='btn btn-primary btn-danger btn-delete' type='button'>删除选项</button><br><br><input type='text' class='form-control choice' placeholder='选项' style='max-width: 600px;'><br><input type='text' class='form-control choice' placeholder='选项' style='max-width: 600px;'><br></form>");
    count++;
});

$("#several").click(function(e){
    e.preventDefault();
    $(".panel-body").append("<form class='form-group many'><hr style='height: 2px;border:none;border-top: 2px solid;color: #337ab7'><label>"+count+":请输入问题：</label><input type='text' class='form-control' placeholder='请输入问题'><br><button class='btn btn-primary btn-add'>添加选项</button><button class='btn btn-primary btn-danger btn-delete'>删除选项</button>&emsp;&emsp;&emsp;<label>输入最多选择项数:</label><input type='number' class='form-control maxCount' value=2 style='max-width: 150px;display: inline;'><br><br><input type='text' class='form-control choice' placeholder='选项' style='max-width: 600px;'><br><input type='text' class='form-control choice' placeholder='选项' style='max-width: 600px;'><br></form>");
    count++;
});

$("#singleText").click(function(){
    $(".panel-body").append("<form class='form-group singleText'><hr style='height: 2px;border:none;border-top: 2px solid;color: #337ab7'><label>"+count+":请输入问题：</label><input type='text' class='form-control' placeholder='请输入问题'><input type='text' class='form-control' placeholder='这里是回答区'></form>");
    count++;
});

$("#severalText").click(function(){
    $(".panel-body").append("<form class='form-group manyText'><hr style='height: 2px;border:none;border-top: 2px solid;color: #337ab7'><label>"+count+":请输入问题：</label><input type='text' class='form-control' placeholder='请输入问题'><textarea name='' id='' cols='30' rows='10' class='form-control' style='resize: none;' placeholder='这里是回答区'></textarea></form>");
    count++;
});


$("body").on('click','.btn-add',function(e){
    e.preventDefault();
    $(this).parent().append("<input type='text' class='form-control choice' placeholder='选项' style='max-width: 600px;'><br>");
});

$("body").on('click','.btn-delete',function(e){
    e.preventDefault();
    if($(this).siblings('.choice').length<=2){
        alert('最少要有两个选项！');
    }else{
        $(this).siblings('.choice').eq(-1).remove();
        $(this).siblings('br').eq(-1).remove();
    }
});

//表单创建时相关数据的判断与处理
$(function () {
    $("#submit").click(function(e){
        e.preventDefault();
        var json=[];
        var flag=true;
        $("input").each(function(){
            if (!$(this).val()) {
                flag=false;
            }
        });
        $("form").each(function(){
            if (!flag) {alert('未填写完整!!');return false;}
            var form = new Object();
            if ($(this).hasClass("single")) {
                form.question = $(this).children('input').eq(0).val();
                form.choice = [];
                $(this).children('.choice').each(function(){
                    form.choice.push($(this).val());
                });
                form.type = 1;
                json.push(form);
            }
            if ($(this).hasClass("many")) {
                form.question = $(this).children('input').eq(0).val();
                form.choice = [];
                form.maxCount = $(this).children('.maxCount').val();
                $(this).children('.choice').each(function(){
                    form.choice.push($(this).val());
                });
                form.type = 2;
                json.push(form);
            }
            if ($(this).hasClass("singleText")) {
                form.question = $(this).children('input').eq(0).val();
                form.type = 3;
                json.push(form);
            }
            if ($(this).hasClass("manyText")) {
                form.question = $(this).children('input').eq(0).val();
                form.type = 4;
                json.push(form);
            }
            if (flag) {console.log(json);}
        });
    });
});

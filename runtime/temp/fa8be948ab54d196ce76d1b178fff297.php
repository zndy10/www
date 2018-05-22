<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:51:"./application/admin/view/goods\_goodsAttribute.html";i:1522317281;s:61:"D:\phpStudy\WWW\www\application\admin\view\public\layout.html";i:1522317281;}*/ ?>
<!doctype html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<!-- Apple devices fullscreen -->
<meta name="apple-mobile-web-app-capable" content="yes">
<!-- Apple devices fullscreen -->
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<link href="/public/static/css/main.css" rel="stylesheet" type="text/css">
<link href="/public/static/css/page.css" rel="stylesheet" type="text/css">
<link href="/public/static/font/css/font-awesome.min.css" rel="stylesheet" />
<!--[if IE 7]>
  <link rel="stylesheet" href="/public/static/font/css/font-awesome-ie7.min.css">
<![endif]-->
<link href="/public/static/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
<link href="/public/static/js/perfect-scrollbar.min.css" rel="stylesheet" type="text/css"/>
<style type="text/css">html, body { overflow: visible;}</style>
<script type="text/javascript" src="/public/static/js/jquery.js"></script>
<script type="text/javascript" src="/public/static/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="/public/static/js/layer/layer.js"></script><!-- 弹窗js 参考文档 http://layer.layui.com/-->
<script type="text/javascript" src="/public/static/js/admin.js"></script>
<script type="text/javascript" src="/public/static/js/jquery.validation.min.js"></script>
<script type="text/javascript" src="/public/static/js/common.js"></script>
<script type="text/javascript" src="/public/static/js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="/public/static/js/jquery.mousewheel.js"></script>
<script src="/public/js/myFormValidate.js"></script>
<script src="/public/js/myAjax2.js"></script>
<script src="/public/js/global.js"></script>
    <script type="text/javascript">
    function delfunc(obj){
    	layer.confirm('确认删除？', {
    		  btn: ['确定','取消'] //按钮
    		}, function(){
    		    // 确定
   				$.ajax({
   					type : 'post',
   					url : $(obj).attr('data-url'),
   					data : {act:'del',del_id:$(obj).attr('data-id')},
   					dataType : 'json',
   					success : function(data){
						layer.closeAll();
   						if(data.status==1){
                            layer.msg(data.msg, {icon: 1, time: 2000},function(){
                                location.href = '';
//                                $(obj).parent().parent().parent().remove();
                            });
   						}else{
   							layer.msg(data, {icon: 2,time: 2000});
   						}
   					}
   				})
    		}, function(index){
    			layer.close(index);
    			return false;// 取消
    		}
    	);
    }

    function selectAll(name,obj){
    	$('input[name*='+name+']').prop('checked', $(obj).checked);
    }

    function get_help(obj){

		window.open("http://www.tp-shop.cn/");
		return false;

        layer.open({
            type: 2,
            title: '帮助手册',
            shadeClose: true,
            shade: 0.3,
            area: ['70%', '80%'],
            content: $(obj).attr('data-url'),
        });
    }

    function delAll(obj,name){
    	var a = [];
    	$('input[name*='+name+']').each(function(i,o){
    		if($(o).is(':checked')){
    			a.push($(o).val());
    		}
    	})
    	if(a.length == 0){
    		layer.alert('请选择删除项', {icon: 2});
    		return;
    	}
    	layer.confirm('确认删除？', {btn: ['确定','取消'] }, function(){
    			$.ajax({
    				type : 'get',
    				url : $(obj).attr('data-url'),
    				data : {act:'del',del_id:a},
    				dataType : 'json',
    				success : function(data){
						layer.closeAll();
    					if(data == 1){
    						layer.msg('操作成功', {icon: 1});
    						$('input[name*='+name+']').each(function(i,o){
    							if($(o).is(':checked')){
    								$(o).parent().parent().remove();
    							}
    						})
    					}else{
    						layer.msg(data, {icon: 2,time: 2000});
    					}
    				}
    			})
    		}, function(index){
    			layer.close(index);
    			return false;// 取消
    		}
    	);
    }

    /**
     * 全选
     * @param obj
     */
    function checkAllSign(obj){
        $(obj).toggleClass('trSelected');
        if($(obj).hasClass('trSelected')){
            $('#flexigrid > table>tbody >tr').addClass('trSelected');
        }else{
            $('#flexigrid > table>tbody >tr').removeClass('trSelected');
        }
    }
    /**
     * 批量公共操作（删，改）
     * @returns {boolean}
     */
    function publicHandleAll(type){
        var ids = '';
        $('#flexigrid .trSelected').each(function(i,o){
//            ids.push($(o).data('id'));
            ids += $(o).data('id')+',';
        });
        if(ids == ''){
            layer.msg('至少选择一项', {icon: 2, time: 2000});
            return false;
        }
        publicHandle(ids,type); //调用删除函数
    }
    /**
     * 公共操作（删，改）
     * @param type
     * @returns {boolean}
     */
    function publicHandle(ids,handle_type){
        layer.confirm('确认当前操作？', {
                    btn: ['确定', '取消'] //按钮
                }, function () {
                    // 确定
                    $.ajax({
                        url: $('#flexigrid').data('url'),
                        type:'post',
                        data:{ids:ids,type:handle_type},
                        dataType:'JSON',
                        success: function (data) {
                            layer.closeAll();
                            if (data.status == 1){
                                layer.msg(data.msg, {icon: 1, time: 2000},function(){
                                    location.href = data.url;
                                });
                            }else{
                                layer.msg(data.msg, {icon: 2, time: 2000});
                            }
                        }
                    });
                }, function (index) {
                    layer.close(index);
                }
        );
    }
</script>  

</head>
<body style="background-color: #FFF; overflow: auto;"> 
<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="javascript:history.back();" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>商品属性</h3>
        <h5>商品属性添加与管理</h5>
      </div>
    </div>
  </div>
    <!--表单数据-->
    <form method="post" id="addEditGoodsAttributeForm">
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="ac_name"><em>*</em>属性名称：</label>
        </dt>
        <dd class="opt">
            <input type="text" value="<?php echo $goodsAttribute['attr_name']; ?>" name="attr_name"  class="input-txt" />
            <span class="err" id="err_attr_name" style="color:#F00; display:none;"></span>                                  
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="parent_id"><em>*</em>所属商品模型：</label>
        </dt>
        <dd class="opt">
           
            <select name="type_id" id="type_id" class="small form-control">
                 <option value="">请选择</option>
                <?php if(is_array($goodsTypeList) || $goodsTypeList instanceof \think\Collection || $goodsTypeList instanceof \think\Paginator): $i = 0; $__LIST__ = $goodsTypeList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                 <option value="<?php echo $vo[id]; ?>" <?php if($vo[id] == $goodsAttribute[type_id]): ?>selected="selected"<?php endif; ?>><?php echo $vo[name]; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>                                        
            </select>
            <span  class="err" id="err_type_id" style="color:#F00; display:none;"></span>      

        </dd>
      </dl>      
		<dl class="row">
        <dt class="tit">
          <label>能否进行检索:</label>
        </dt>
        <dd class="opt">                    
                <input type="radio" value="0" name="attr_index" <?php if(($goodsAttribute[attr_index] == 0)  or ($goodsAttribute[attr_index] == NULL)): ?>checked="checked"<?php endif; ?>  .>不需要检索
                <input type="radio" value="1" name="attr_index" <?php if($goodsAttribute[attr_index] == 1): ?>checked="checked"<?php endif; ?>  />关键字检索          
          <p class="notic"></p>
        </dd>
      </dl>		       
      <dl class="row">
        <dt class="tit">
          <label for="ac_sort">该属性值的录入方式：</label>
        </dt>
        <dd class="opt">
            <input type="radio" value="0" name="attr_input_type" <?php if(($goodsAttribute[attr_input_type] == 0) or ($goodsAttribute[attr_input_type] == NULL)): ?>checked="checked"<?php endif; ?> />手工录入
            <input type="radio" value="1" name="attr_input_type" <?php if($goodsAttribute[attr_input_type] == 1): ?>checked="checked"<?php endif; ?>  />从下面的列表中选择（一行代表一个可选值）
            <input type="radio" value="2" name="attr_input_type" <?php if($goodsAttribute[attr_input_type] == 2): ?>checked="checked"<?php endif; ?>  />多行文本框           
        </dd>
      </dl>
	  <dl class="row">
        <dt class="tit">
          <label for="ac_sort">可选值列表：</label>
        </dt>
        <dd class="opt">
            <textarea rows="10" cols="30" name="attr_values" class="input-txt" style="height:100px;"><?php echo $goodsAttribute['attr_values']; ?></textarea>
            <span id="err_attr_values" class="err" style="color:#F00; display:none;"></span>                  
            <p class="notic">录入方式为手工或者多行文本时，此输入框不需填写。</p>
        </dd>
      </dl>	                 
      <div class="bot"><a href="JavaScript:void(0);" onclick="ajax_submit_form('addEditGoodsAttributeForm','<?php echo U('Goods/addEditGoodsAttribute?is_ajax=1'); ?>');"  class="ncap-btn-big ncap-btn-green" id="submitBtn">确认提交</a></div>
    </div>
	   <input type="hidden" name="attr_id" value="<?php echo $goodsAttribute['attr_id']; ?>">
  </form>
</div>
 
</body>
</html>
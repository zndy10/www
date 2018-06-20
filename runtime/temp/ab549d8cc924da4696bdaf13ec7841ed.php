<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:45:"./application/admin/view/order\add_order.html";i:1522317281;s:61:"D:\phpStudy\WWW\www\application\admin\view\public\layout.html";i:1522317281;}*/ ?>
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
  
<style type="text/css">
html, body {
	overflow: visible;
}

a.btn {
    background: #fff none repeat scroll 0 0;
    border: 1px solid #f5f5f5;
    border-radius: 4px;
    color: #999;
    cursor: pointer !important;
    display: inline-block;
    font-size: 12px;
    font-weight: normal;
    height: 20px;
    letter-spacing: normal;
    line-height: 20px;
    margin: 0 5px 0 0;
    padding: 1px 6px;
    vertical-align: top;
}

 a.red:hover {
    background-color: #e84c3d;
    border-color: #c1392b;
    color: #fff;
}

</style>  
<body style="background-color: #FFF; overflow: auto;">
<div id="toolTipLayer" style="position: absolute; z-index: 9999; display: none; visibility: visible; left: 95px; top: 573px;"></div>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="javascript:history.back();" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>添加订单</h3>
        <h5>管理员在后台添加一个新订单</h5>
      </div>
    </div>
  </div>
  <form class="form-horizontal" action="<?php echo U('Admin/Order/addOrder'); ?>" id="order-add" method="post">
    <div class="ncap-form-default">
   	<dl class="row">
       <dt class="tit">
         <label><em></em>用户名</label>
       </dt>
       <dd class="opt">
         <input type="text" name="user_name" id="user_name" class="input-txt" placeholder="用户昵称搜索" />
         <select name="user_id" id="user_id">
             <option value="0">选择用户</option>
         </select>
         <a href="javascript:void(0);" onclick="search_user();" class="ncap-btn ncap-btn-green" ><i class="fa fa-search"></i>搜索</a>
       </dd>
      </dl>
	  <dl class="row">
        <dt class="tit">
          <label for="consignee"><em>*</em>收货人</label>
        </dt>
        <dd class="opt">
          <input type="text" name="consignee" id="consignee" class="input-txt" placeholder="收货人名字" />
        </dd>
      </dl>  
      <dl class="row">
        <dt class="tit">
          <label for="consignee"><em>*</em>手机</label>
        </dt>
        <dd class="opt">
          <input type="text" name="mobile" id="mobile" class="input-txt" placeholder="收货人联系电话" />
        </dd>
      </dl>      
      <dl class="row">
        <dt class="tit">
          <label for="consignee"><em>*</em>地址</label>
        </dt>
        <dd class="opt">
          <select onchange="get_city(this)" id="province" name="province"  title="请选择所在省份">
               <option value="">选择省份</option>
               <?php if(is_array($province) || $province instanceof \think\Collection || $province instanceof \think\Paginator): $i = 0; $__LIST__ = $province;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                   <option value="<?php echo $vo['id']; ?>" ><?php echo $vo['name']; ?></option>
               <?php endforeach; endif; else: echo "" ;endif; ?>
           </select>
           <select onchange="get_area(this)" id="city" name="city" title="请选择所在城市">
                <option value="">选择城市</option>
                <?php if(is_array($city) || $city instanceof \think\Collection || $city instanceof \think\Paginator): $i = 0; $__LIST__ = $city;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <option value="<?php echo $vo['id']; ?>"><?php echo $vo['name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <select id="district" name="district" title="请选择所在区县">
                <option value="">选择区域</option>
                <?php if(is_array($area) || $area instanceof \think\Collection || $area instanceof \think\Paginator): $i = 0; $__LIST__ = $area;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <option value="<?php echo $vo['id']; ?>"><?php echo $vo['name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <input type="text" name="address" id="address" class="input-txt"   placeholder="详细地址"/>
        </dd>
      </dl>
    <dl class="row">
        <dt class="tit">
            <label for="consignee"><em>*</em>邮编</label>
        </dt>
        <dd class="opt">
            <input type="text" name="zipcode" id="zipcode" class="input-txt" placeholder="收货地址邮编" />
        </dd>
    </dl>
      <dl class="row">
        <dt class="tit">
          <label for="invoice_title">发票抬头</label>
        </dt>
        <dd class="opt">
          <input type="text" name="invoice_title" value="<?php echo $order['invoice_title']; ?>" class="input-txt"  placeholder="发票抬头"/>
        </dd>
      </dl>
        <dl class="row">
            <dt class="tit">
                <label for="invoice_title">纳税人编号</label>
            </dt>
            <dd class="opt">
                <input type="text" name="taxpayer" value="<?php echo $order['taxpayer']; ?>" class="input-txt"  placeholder="纳税人识别号"/>
            </dd>
        </dl>

     <dl class="row">
        <dt class="tit">
          <label for="invoice_title">添加商品</label>
        </dt>
        <dd class="opt">
          <a href="javascript:void(0);" onclick="selectGoods()" class="ncap-btn ncap-btn-green" ><i class="fa fa-search"></i>添加商品</a>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="invoice_title"><em>*</em>商品列表</label>
        </dt>
        <dd class="opt">
          	<div class="ncap-order-details" id="goods_list_div" style="display:none">
		      <div class="hDivBox" id="ajax_return" >
		        <div class="form-group">                                       
                       <div class="col-xs-10" id="goods_td" >
                           <table class="table table-bordered"></table>
                       </div>
               </div>  
		      </div>
		    </div>
          	 
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">管理员备注</dt>
        <dd class="opt">
	      <textarea class="tarea" style="width:440px; height:150px;" name="admin_note" id="admin_note">管理员添加订单</textarea>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
      <div class="bot"><a href="JavaScript:void(0);" onClick="checkSubmit()" class="ncap-btn-big ncap-btn-green" id="submitBtn">确认提交</a></div>
    </div>
        
  </form>
</div>
<script type="text/javascript">

    $(function () {
        $("#order-add").validate({
            debug: false, //调试模式取消submit的默认提交功能
            focusInvalid: false, //当为false时，验证无效时，没有焦点响应
            onkeyup: false,
            submitHandler: function(form){   //表单提交句柄,为一回调函数，带一个参数：form
                 if($("input[name^='goods_id']").length ==0){
                       layer.alert('订单中至少要有一个商品', {icon: 2});
                       return ;
                  }else{
                      form.submit();   //提交表单
                  }
            },
            ignore:":button",	//不验证的元素
            rules:{
                consignee:{
                    required:true
                },
                mobile:{
                    required:true
                },
                province:{
                    required:true
                },
                city:{
                    required:true
                },
                district:{
                    required:true
                },
                address:{
                    required:true
                },
                zipcode:{
                    required:true
                },
            },
            messages:{
                consignee:{
                    required:"请填写收货人"
                },
                mobile:{
                    required:"收货人联系电话"
                },
                province:{
                    required:"请选择所在省份"
                },
                city:{
                    required:"请选择所在城市"
                },
                district:{
                    required:"请选择所在区县"
                },
                address:{
                    required:"请填写详细地址"
                },
                zipcode:{
                    required:"请填写收货地邮编"
                },
            }
        });
    });

    //搜索用户
    function search_user(){
        var user_name = $('#user_name').val();
        if($.trim(user_name) == '')
            return false;
            $.ajax({
                type : "POST",
                url:"/index.php?m=Admin&c=User&a=search_user",//+tab,
                data :{search_key:$('#user_name').val()},// 你的formid
                dataType :'json',
                success: function(data){
                    if(data.status == 1){
                        var html='';
                        for(var i=0 ; i<data.result.length ;i++){
                                html +="<option value='"+data.result[i].user_id+"' nickname='"+data.result[i].nickname+"'>"+data.result[i].nickname+"</option>"
                        }
                        $('#user_id').html(html);
                    }else{
                        layer.msg(data.msg, {icon: 2});
                    }
                }
            });
    }
    //选择用户
    $(document).on('change','#user_id',function(){
        $('#user_name').val($(this).find("option:selected").attr('nickname'));
    })
    //选择商品
    function selectGoods(){
        var url = "<?php echo U('Admin/Order/search_goods'); ?>";
        layer.open({
            type: 2,
            title: '选择商品',
            shadeClose: true,
            shade: 0.8,
            area: ['60%', '60%'],
            content: url,
        });
    }

    // 选择商品返回
    function call_back(table_html){
        $('#goods_list_div').show();

        $('#goods_td').find('.table-bordered').append(table_html);
        //过滤选择重复商品
        $('input[name*="spec"]').each(function(i,o){
            if($(o).val()){
                var name='goods_id['+$(o).attr('rel')+']['+$(o).val()+'][goods_num]';
                $('input[name="'+name+'"]').parent().parent().parent().remove();
            }
        });
        layer.closeAll('iframe');
    }

    function checkSubmit(){

        $('#order-add').submit();

    }

    function delRow(obj){
        $(obj).parent().parent().parent().remove();
    }
</script>
</body>
</html>
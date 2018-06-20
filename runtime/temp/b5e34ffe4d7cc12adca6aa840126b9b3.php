<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:42:"./application/admin/view/order\detail.html";i:1522317281;s:61:"D:\phpStudy\WWW\www\application\admin\view\public\layout.html";i:1522317281;}*/ ?>
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
<style>
.ncm-goods-gift {
	text-align: left;
}
.ncm-goods-gift ul {
    display: inline-block;
    font-size: 0;
    vertical-align: middle;
}
.ncm-goods-gift li {
    display: inline-block;
    letter-spacing: normal;
    margin-right: 4px;
    vertical-align: top;
    word-spacing: normal;
}
.ncm-goods-gift li a {
    background-color: #fff;
    display: table-cell;
    height: 30px;
    line-height: 0;
    overflow: hidden;
    text-align: center;
    vertical-align: middle;
    width: 30px;
}
.ncm-goods-gift li a img {
    max-height: 30px;
    max-width: 30px;
}

a.green{
	
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

a.green:hover { color: #FFF; background-color: #1BBC9D; border-color: #16A086; }

.ncap-order-style .ncap-order-details{
	margin:20px auto;
}
.contact-info h3,.contact-info .form_class{
  display: inline-block;
  vertical-align: middle;
}
.form_class i.fa{
  vertical-align: text-bottom;
}
</style>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="javascript:history.go(-1)" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>商品订单</h3>
        <h5>商城实物商品交易订单查询及管理</h5>
      </div>
      <div class="subject" style="width:62%">
	     <?php if($order['order_status'] < 2 and $order['pay_status'] == 0): ?>
      		<a href="<?php echo U('Admin/order/edit_order',array('order_id'=>$order['order_id'])); ?>" style="float:right;margin-right:10px" class="ncap-btn-big ncap-btn-green" ><i class="fa fa-pencil-square-o"></i>修改订单</a>
      	 <?php endif; if(($split == 1) and ($order['order_status'] < 2)): ?>
      		<a href="<?php echo U('Admin/order/split_order',array('order_id'=>$order['order_id'])); ?>" style="float:right;margin-right:10px" class="ncap-btn-big ncap-btn-green" ><i class="fa fa-external-link-square"></i>拆分订单</a>
      	 <?php endif; ?>
      	 <a href="<?php echo U('Order/order_print',array('order_id'=>$order['order_id'])); ?>" target="_blank"  style="float:right;margin-right:10px" class="ncap-btn-big ncap-btn-green" ><i class="fa fa-print"></i>打印订单</a>
      </div>
    </div>
      
  </div>
  <div class="ncap-order-style">
    <div class="titile">
      <h3></h3>
    </div>
 
    <div class="ncap-order-details">
      <form id="order-action">
        <div class="tabs-panels">
            <div class="misc-info">
                <h3>基本信息</h3>
                <dl>
                    <dt>订单 ID：</dt>
                    <dd><?php echo $order['order_id']; ?></dd>
                    <dt>订单号：</dt>
                    <dd><?php echo $order['order_sn']; ?></dd>
                    <dt>会员：</dt>
                    <dd><?php echo $user['nickname']; ?>  ID:<?php echo $order['user_id']; ?></dd>
                </dl>
                <dl>
                    <dt>E-Mail：</dt>
                    <dd><?php echo $order['email']; ?></dd>
                    <dt>电话：</dt>
                    <dd><?php echo $order['mobile']; ?></dd>
                    <dt>应付金额：</dt>
                    <dd><?php echo $order['order_amount']; ?></dd>
                </dl>
                <dl>
                    <dt>订单状态：</dt>
                    <dd><?php echo $order_status[$order[order_status]]; ?> / <?php echo $pay_status[$order[pay_status]]; if($order['pay_code'] == 'cod'): ?><span style="color: red">(货到付款)</span><?php endif; ?>
                        / <?php echo $shipping_status[$order[shipping_status]]; ?>
                    </dd>
                    <dt>下单时间：</dt>
                    <dd><?php echo date('Y-m-d H:i',$order['add_time']); ?></dd>
                    <dt>支付时间：</dt>
                    <dd>
                        <?php if($order['pay_time'] != 0): ?><?php echo date('Y-m-d H:i',$order['pay_time']); else: ?>
                            N
                        <?php endif; ?>
                    </dd>
                </dl>
                <dl>
                    <dt>支付方式：</dt>
                    <dd><?php echo (isset($order['pay_name']) && ($order['pay_name'] !== '')?$order['pay_name']:'其他方式'); ?></dd>
                    <dt>发票抬头：</dt>
                    <dd><?php echo (isset($order['invoice_title']) && ($order['invoice_title'] !== '')?$order['invoice_title']:'N'); ?></dd>
                    <dt>纳税人识别号：</dt>
                    <dd><?php echo (isset($order['taxpayer']) && ($order['taxpayer'] !== '')?$order['taxpayer']:'N'); ?></dd>
                </dl>
            </div>
        <div class="addr-note">
          <h4>收货信息</h4>
          <dl>
            <dt>收货人：</dt>
            <dd><?php echo $order['consignee']; ?></dd>
            <dt>联系方式：</dt>
            <dd><?php echo $order['mobile']; ?></dd>
          </dl>
          <dl>
            <dt>收货地址：</dt>
            <dd><?php echo $order['full_address']; ?></dd>
          </dl>
          <dl>
            <dt>邮编：</dt>
            	<dd><?php echo (isset($order['zipcode']) && ($order['zipcode'] !== '')?$order['zipcode']:'N'); ?></dd>
          </dl>
          <dl>
           		<dt>配送方式：</dt>
                  <?php if(is_array($express) || $express instanceof \think\Collection || $express instanceof \think\Paginator): if( count($express)==0 ) : echo "" ;else: foreach($express as $key=>$v): ?>
                        <dd><?php echo $v['shipping_name']; ?> -- <?php echo $v['invoice_no']; ?></dd>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
          	</dl>
          	<dl>
           		<dt>留言：</dt>
            	<dd><?php echo (isset($order['user_note']) && ($order['user_note'] !== '')?$order['user_note']:''); ?></dd>
          	</dl>
        </div>
  
         
        <div class="goods-info">
          <h4>商品信息</h4>
          <table>
            <thead>
              <tr>
                <th >商品编号</th>
                <th colspan="2">商品</th>
                <th>规格属性</th>
                <th>数量</th>
                <th>单品价格</th>
                <th>会员折扣价</th>
                <th>单品小计</th>
              </tr>
            </thead>
            <tbody>
            <?php if(is_array($order['orderGoods']) || $order['orderGoods'] instanceof \think\Collection || $order['orderGoods'] instanceof \think\Paginator): $i = 0; $__LIST__ = $order['orderGoods'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$good): $mod = ($i % 2 );++$i;?>
           	<tr>
                <td class="w60"><?php echo $good['goods_sn']; ?></td>
                <td class="w30"><div class="goods-thumb"><a href="<?php echo U('Home/Goods/goodsInfo',array('id'=>$good['goods_id'])); ?>" target="_blank"><img alt="" src="<?php echo goods_thum_images($good['goods_id'],200,200); ?>" /> </a></div></td>
                <td style="text-align: left;"><a href="<?php echo U('Home/Goods/goodsInfo',array('id'=>$good['goods_id'])); ?>" target="_blank"><?php echo $good['goods_name']; ?></a><br/></td>
                <td class="w80"><?php echo $good['spec_key_name']; ?></td>
                <td class="w60"><?php echo $good['goods_num']; ?></td>
                <td class="w100"><?php echo $good['goods_price']; ?></td>
                <td class="w60"><?php echo $good['member_goods_price']; ?></td>
                <td class="w80"><?php echo $good['goods_total']; ?></td>
              </tr>
              <?php endforeach; endif; else: echo "" ;endif; ?>
          </table>
        </div>
        <div class="total-amount contact-info">
          <h3>订单总额：￥<?php echo $order['goods_price']; ?></h3>
        </div>
         <?php if($order['shipping_status'] == 1): if(is_array($express) || $express instanceof \think\Collection || $express instanceof \think\Paginator): if( count($express)==0 ) : echo "" ;else: foreach($express as $key=>$v): ?>
                <div class="goods-info">
                    <h4>物流信息</h4>
                    <table id="express_info">
                        <thead>
                        <tr>
                            <th>操作时间</th>
                            <th>状态</th>
                        </tr>
                        </thead>
                        <tbody>

                    </table>
                </div>
                <script>
                    $(function(){
                        var shipping_code = "<?php echo $v['shipping_code']; ?>";
                        var invoice_no = "<?php echo $v['invoice_no']; ?>";
                        $.ajax({
                            type : "GET",
                            dataType: "json",
                            url:"/index.php?m=Home&c=Api&a=queryExpress&shipping_code="+shipping_code+"&invoice_no="+invoice_no,//+tab,
                            success: function(data){
                                var html = '';
                                if(data.status == 200){
                                    $.each(data.data, function(i,n){
                                        if(i == 0){
                                            html += '<tr><td class="text-center">'+n.time+'</td><td class="text-center">'+n.context+'</td></tr>'
                                        }else{
                                            html += '<tr><td class="text-center">'+n.time+'</td><td class="text-center">'+n.context+'</td></tr>'
                                        }
                                    });
                                }else{
                                    html += "<tr><td class='text-center'>"+data.message+"</td></tr>"
                                }
                                $("#express_info").append(html);
                            }
                        });
                    })
                </script>
            <?php endforeach; endif; else: echo "" ;endif; endif; ?>
        <div class="contact-info">
          <h3>费用信息 </h3>
            <?php if($order['pay_status'] == 0): ?>
              <div class="form_class">
                    <a class="btn green" href="<?php echo U('Admin/Order/editprice',array('order_id'=>$order['order_id'])); ?>"><i class="fa fa-pencil-square-o"></i>修改费用</a>
              </div>
            <?php endif; ?>
          <dl>
            <dt>小计：</dt>
            <dd><?php echo $order['goods_price']; ?></dd>
            <dt>运费：</dt>
            <dd>+<?php echo $order['shipping_price']; ?></dd>
            <dt>积分 (-<?php echo $order['integral']; ?>)：</dt>
            <dd>-<?php echo $order['integral_money']; ?></dd>
          </dl>
          <dl>
            <dt>余额抵扣：</dt>
            <dd>-<?php echo $order['user_money']; ?></dd>
            <dt>优惠券抵扣：</dt>
            <dd>-<?php echo $order['coupon_price']; ?></dd>
            <dt>价格调整：</dt>
            <dd>减：<?php echo $order['discount']; ?></dd>
          </dl>
          <dl>
            <dt>应付：</dt>
            <dd><strong class="red_common"><?php echo $order['order_amount']; ?></strong></dd>
           </dl>
        </div>
        <div class="contact-info">
          <h3>操作信息</h3>
          <dl class="row">
	        <dt class="tit">
	          <label for="note">操作备注</label>
	        </dt>
	        <dd class="opt" style="margin-left:10px">
	         <textarea id="note" name="note" style="width:600px" rows="6"  placeholder="请输入操作备注" class="tarea"><?php echo $keyword['text']; ?></textarea>
	        </dd>
	      </dl> 
	      <dl class="row">
	        <dt class="tit">
	          <label for="note">可执行操作</label>
	        </dt>
	        <dd class="opt" style="margin-left:10px">
	         	<?php if(is_array($order['adminOrderButton']) || $order['adminOrderButton'] instanceof \think\Collection || $order['adminOrderButton'] instanceof \think\Paginator): if( count($order['adminOrderButton'])==0 ) : echo "" ;else: foreach($order['adminOrderButton'] as $k=>$vo): if($k == 'pay_cancel'): ?>
               			<!--<a class="ncap-btn-big ncap-btn-green" href="javascript:void(0)" data-url="<?php echo U('Order/pay_cancel',array('order_id'=>$order['order_id'])); ?>" onclick="pay_cancel(this)"><?php echo $vo; ?></a>-->
                        <a class="ncap-btn-big ncap-btn-green" onclick="verifyForm('order-action','<?php echo U('Admin/order/order_action',array('order_id'=>$order['order_id'],'type'=>$k)); ?>');" ><?php echo $vo; ?></a>
               		<?php elseif($k == 'delivery'): ?>                                                 
               			<a class="ncap-btn-big ncap-btn-green" href="<?php echo U('Order/delivery_info',array('order_id'=>$order['order_id'])); ?>"><?php echo $vo; ?></a>
               		<?php elseif($k == 'refund'): else: ?>
               		<a class="ncap-btn-big ncap-btn-green" onclick="verifyForm('order-action','<?php echo U('Admin/order/order_action',array('order_id'=>$order['order_id'],'type'=>$k)); ?>');" >
               		<?php echo $vo; ?></a>
               		<?php endif; endforeach; endif; else: echo "" ;endif; ?>
	        </dd>
	      </dl> 
        </div>
        <div class="goods-info">
          <h4>操作记录</h4>
          <table>
            <thead>
              <tr>
                <th>操作者</th>
                <th>操作时间</th>
                <th>订单状态</th>
                <th>付款状态</th>
                <th>发货状态</th>
                <th>描述</th>
                <th>备注</th>
              </tr>
            </thead>
              <tbody id="order_action">
              </tbody>
          </table>
        </div>
      </div>
      </form>
  	</div>
  </div>
</div>
<script type="text/javascript">
    $(function () {
        getOrderAction();
    })
    function delfun() {
        // 删除按钮
        layer.confirm('确认删除？', {
            btn: ['确定'] //按钮
        }, function () {
            console.log("确定");
        }, function () {
            console.log("取消");
        });
    }

    before_request = 1; // 标识上一次ajax 请求有没回来, 没有回来不再进行下一次
    function verifyForm(form_id,submit_url){
        if(before_request == 0)
            return false;
        $("[id^='err_']").hide();  // 隐藏提示
        $.ajax({
            type : "POST",
            url  : submit_url,
            data : $('#'+form_id).serialize(),// 你的formid
            dataType: 'json',
            error: function(request) {
                layer.msg("服务器繁忙, 请联系管理员!", {icon: 1, time: 2000});
            },
            success: function(data) {
                before_request = 1; // 标识ajax 请求已经返回
                // 验证成功提交表单
                if(data.status == 1)
                {
                    layer.msg(data.msg, {icon: 1, time: 2000},function () {
                    	window.location.href = data.url;
                    });
                }else{
                    layer.msg(data.msg, {icon: 2, time: 2000});
                }
            }
        });
        before_request = 0; // 标识ajax 请求已经发出
    }

    function getOrderAction(){
        var order_id = "<?php echo $order['order_id']; ?>";
        $.ajax({
            type : "get",
            url  : "<?php echo U('Admin/order/getOrderAction'); ?>",
            data : {order_id:order_id},
            dataType: 'json',
            error: function(request) {
                layer.msg("服务器繁忙, 请联系管理员!", {icon: 1, time: 2000});
            },
            success: function(data) {
                // 验证成功提交表单
                if(data.status == 1)
                {
                    var html = '';
                    for (var i=0; i<data.data.length;i++){
                        html +='<tr><td class="text-center">'+data.data[i].action_user_name+'</td>' +
                                '<td class="text-center">'+data.data[i].log_time+'</td>' +
                                '<td class="text-center">'+data.data[i].order_status+'</td>' +
                                '<td class="text-center">'+data.data[i].pay_status+'</td>' +
                                '<td class="text-center">'+data.data[i].shipping_status+'</td>' +
                                '<td class="text-center">'+data.data[i].status_desc+'</td>' +
                                '<td class="text-center">'+data.data[i].action_note+'</td></tr>'
                    }
                    $('#order_action').html(html);
                }else{
                    layer.msg(data.msg, {icon: 2, time: 2000});
                }
            }
        });
    }
</script>
</body>
</html>
<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:49:"./application/admin/view/order\delivery_info.html";i:1522317281;s:61:"D:\phpStudy\WWW\www\application\admin\view\public\layout.html";i:1522317281;}*/ ?>
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
        <h3>订单发货</h3>
        <h5>订单发货编辑</h5>
      </div>
      <div class="subject" style="width:62%">
      		<a href="<?php echo U('Order/delivery_print',array('print_ids'=>$order['order_id'])); ?>" style="float:right;margin-right:10px" class="ncap-btn-big ncap-btn-green" ><i class="fa fa-print"></i>打印配货单</a>
      	 </a>	
      </div>
    </div>
      
  </div>
  <div class="ncap-order-style">
    <div class="titile">
      <h3></h3>
    </div>
 <form id="delivery-form" action="<?php echo U('Admin/order/deliveryHandle'); ?>" method="post">
     <input type="hidden" name="shipping" value="<?php echo $order['shipping_status']; ?>">
     <input type="hidden" id="shipping_name" name="shipping_name" value="<?php if($order['shipping_status'] == 1): ?><?php echo $order['shipping_name']; else: ?><?php echo $shipping_list['0']['shipping_name']; endif; ?>">
    <div class="ncap-order-details">
      <div class="tabs-panels">
        <div class="misc-info">
           <h3>基本信息</h3>
           		<dl>
           			<dt>订单号：</dt>
		            <dd><?php echo $order['order_sn']; ?></dd>
		            <dt>下单时间：</dt>
		            <dd><?php echo date('Y-m-d H:i',$order['add_time']); ?></dd>
		            <dt>物流公司：</dt>
		            <dd><select id="shipping_code" name="shipping_code" onchange="set_shipping_name()">
		             	<?php if(is_array($shipping_list) || $shipping_list instanceof \think\Collection || $shipping_list instanceof \think\Paginator): $i = 0; $__LIST__ = $shipping_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$shipping): $mod = ($i % 2 );++$i;?>
		                 <option <?php if($order['shipping_code'] == $shipping['shipping_code']): ?>selected<?php endif; ?> value="<?php echo $shipping['shipping_code']; ?>" ><?php echo $shipping['shipping_name']; ?></option>
		             	<?php endforeach; endif; else: echo "" ;endif; ?>
		         		</select>
         			</dd>
		          </dl>
	              <dl>
	              	<dt>配送费用：</dt>
		            <dd><?php echo $order['shipping_price']; ?></dd>
		            <dt>发货方式：</dt>
		            <dd>
		            	<select id="send_type" name="send_type" onchange="change_send();">
		            		<option value="0">手填物流单号</option>
		            		<?php if(($order['shipping_status'] == 0) && ($express_switch == 1)): ?>
		            		<option value="1">在线预约发货</option>
		            		<option value="2">电子面单发货</option>
		            		<?php endif; ?>
		            		<option value="3">无需物流</option>
		            	</select>
		            </dd>
		            <dt class="invoice">配送单号：</dt>
		            <dd class="invoice"><input class="input-txt" name="invoice_no" id="invoice_no" value="<?php echo $order['invoice_no']; ?>" onkeyup="this.value=this.value.replace(/[^\d]/g,'')"></dd>
			       </dl>
        	</div>
        
        <div class="addr-note">
          <h4>收货信息</h4>
          <dl>
            <dt>收货人：</dt>
            <dd><?php echo $order['consignee']; ?></dd>
            <dt>电子邮件：</dt>
            <dd><?php echo $order['email']; ?></dd>
          </dl>
          <dl>
            <dt>收货地址：</dt>
            <dd><?php echo $order['address']; ?></dd>
          </dl>
          <dl>
            <dt>邮编：</dt>
            	<dd><?php if($order['zipcode'] != ''): ?> <?php echo $order['zipcode']; else: ?>N<?php endif; ?></dd>
          </dl>
          <dl>
           		<dt>电话：</dt>
            	<dd><?php echo $order['mobile']; ?></dd>
            	<dt>发票抬头：</dt>
            	<dd><?php echo $order['invoice_title']; ?></dd>
                <dt>纳税人识别号：</dt>
                <dd><?php echo $order['taxpayer']; ?></dd>
          	</dl>
            <dl>
           		<dt>用户备注：</dt>
            	<dd><?php echo $order['user_note']; ?></dd>
          	</dl>
        </div>
  
         
        <div class="goods-info">
          <h4>商品信息</h4>
          <table>
            <thead>
              <tr>
                <th colspan="2">商品</th>
                <th>规格属性</th>
                <th>购买数量</th>
                <th>商品单价</th>
                <th>选择发货</th>
              </tr>
            </thead>
            <tbody>
            <?php if(is_array($orderGoods) || $orderGoods instanceof \think\Collection || $orderGoods instanceof \think\Paginator): $i = 0; $__LIST__ = $orderGoods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$good): $mod = ($i % 2 );++$i;?>
           	<tr>
                <td class="w30"><div class="goods-thumb"><a href="<?php echo U('Goods/addEditGoods',array('id'=>$good[goods_id])); ?>" target="_blank"><img alt="" src="<?php echo goods_thum_images($good['goods_id'],200,200); ?>" /> </a></div></td>
                <td style="text-align: left;"><a href="<?php echo U('Goods/addEditGoods',array('id'=>$good[goods_id])); ?>" target="_blank"><?php echo $good['goods_name']; ?></a><br/></td>
                <td class="w80"><?php echo $good['spec_key_name']; ?></td>
                <td class="w60"><?php echo $good['goods_num']; ?></td>
                <td class="w100"><?php echo $good['goods_price']; ?></td>
                <td class="w60">
                	<?php if($good['is_send'] == 1): ?>
                        	已发货
                   <?php else: ?>
                        	<input type="checkbox" name="goods[]" value="<?php echo $good['rec_id']; ?>" checked="checked">
                    <?php endif; ?>
                </td>
              </tr>
              <?php endforeach; endif; else: echo "" ;endif; ?>
          </table>
        </div>
        <div class="contact-info"  style="margin-top:10px;">
          <h3>发货单备注</h3>
          <dl class="row">
	        <dt class="tit">
	          <label for="note">发货单备注</label>
	        </dt>
	        <dd class="opt" style="margin-left:10px">
	        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
	         <textarea id="note" name="note" style="width:600px" rows="6"  placeholder="请输入操作备注" class="tarea" id="note"><?php echo $keyword['text']; ?></textarea>
	        </dd>
	      </dl> 
	      <dl class="row">
	        <dt class="tit">
	          <label for="note">可执行操作</label>
	        </dt>
	        <dd class="opt" style="margin-left:10px">
                <?php if($order['shipping_status'] != 1): ?>
               		<a class="ncap-btn-big ncap-btn-green"  onclick="dosubmit()">确认发货</a>
                <?php endif; if($order['shipping_status'] == 1): ?>
                    <a class="ncap-btn-big ncap-btn-green"  onclick="dosubmit()">修改</a>
                <?php endif; ?>
	        </dd>
	      </dl> 
        </div>
        <div class="goods-info">
          <h4>发货记录</h4>
          <table>
            <thead>
              <tr>
                <th>操作者</th>
                <th>发货时间</th>
                <th>发货单号</th>
                <th>收货人</th>
                <th>快递公司</th>
                <th>备注</th>
                <!--<th>查看</th>-->
              </tr>
            </thead>
            <tbody>
            <?php if(is_array($delivery_record) || $delivery_record instanceof \think\Collection || $delivery_record instanceof \think\Paginator): $i = 0; $__LIST__ = $delivery_record;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$log): $mod = ($i % 2 );++$i;?>
	           	<tr>
	                 <td class="text-center"><?php echo $log['user_name']; ?></td>
	                 <td class="text-center"><?php echo date('Y-m-d H:i:s',$log['create_time']); ?></td>
	                 <td class="text-center"><?php echo $log['invoice_no']; ?></td>
	                 <td class="text-center"><?php echo $log['consignee']; ?></td>
	                 <td class="text-center"><?php echo $log['shipping_name']; ?></td>
	                 <td class="text-center"><?php echo $log['note']; ?></td>
	                 <!--<td class="text-center"></td>-->
	             </tr>
              <?php endforeach; endif; else: echo "" ;endif; ?>
          </table>
        </div>
      </div>
  	</div>
  	</form>
  </div>
  
</div>
<script type="text/javascript">
function dosubmit(){
    var shipping = $('input[name="shipping"]').val();
    if ($('#invoice_no').val() == '' && $('#send_type').val() == 0) {
        layer.alert('请输入配送单号', {icon: 2});  // alert('请输入配送单号');
        return;
    }
    if(shipping != 1) {
        var a = [];
        $('input[name*=goods]').each(function (i, o) {
            if ($(o).is(':checked')) {
                a.push($(o).val());
            }
        });

		if(a.length == 0){
			layer.alert('请选择发货商品', {icon: 2});  //alert('请选择发货商品');
			return;
		}
    }
	$('#delivery-form').submit();
}

function set_shipping_name(){
	var shipping_name = $("#shipping_code").find("option:selected").text();
	$('#shipping_name').val(shipping_name);
}

function change_send(){
	var send_type = $('#send_type').val();
	if(send_type == 0){
		$('.invoice').show();
	}else{
		$('.invoice').hide();
	}
}
</script>
</body>
</html>
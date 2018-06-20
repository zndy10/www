<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:48:"./application/admin/view/order\search_goods.html";i:1522317281;s:61:"D:\phpStudy\WWW\www\application\admin\view\public\layout.html";i:1522317281;}*/ ?>
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
<script type="text/javascript" src="/public/static/js/layer/laydate/laydate.js"></script>

<body style="background-color: rgb(255, 255, 255); overflow: auto; cursor: default; -moz-user-select: inherit;">
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page" style="padding:10px">
  <div class="flexigrid" >
    <div class="mDiv">
      <div class="ftitle">
        <h3>商品列表</h3>
        <h5>(共<?php echo $totalSize; ?>条记录)</h5>
      </div>
      <div title="刷新数据" class="pReload"><i class="fa fa-refresh"></i></div>
	  <form class="navbar-form form-inline"  method="post" action="<?php echo U('Admin/Order/search_goods'); ?>"  name="search-form2" id="search-form2">  
      <div class="sDiv">
        <div class="sDiv2">	 
        	<select name="cat_id" id="cat_id" class="select">
                 <option value="">所有分类</option>
                 <?php if(is_array($categoryList) || $categoryList instanceof \think\Collection || $categoryList instanceof \think\Paginator): if( count($categoryList)==0 ) : echo "" ;else: foreach($categoryList as $k=>$v): ?>
					<option value="<?php echo $v['id']; ?>" <?php if($v[id] == $cat_id): ?>selected<?php endif; ?> ><?php echo  str_pad('',($v[level] * 5),'-',STR_PAD_LEFT);  ?> <?php echo $v['name']; ?></option>
				<?php endforeach; endif; else: echo "" ;endif; ?>
             </select>
        </div>
        <div class="sDiv2">	   
            <select name="brand_id" id="brand_id" class="select">
                <option value="">所有品牌</option>
                    <?php if(is_array($brandList) || $brandList instanceof \think\Collection || $brandList instanceof \think\Paginator): if( count($brandList)==0 ) : echo "" ;else: foreach($brandList as $k=>$v): ?>
                       <option value="<?php echo $v['id']; ?>" <?php if($v[id] == $brand_id): ?>selected<?php endif; ?> ><?php echo $v['name']; ?></option>
					<?php endforeach; endif; else: echo "" ;endif; ?>
             </select>
         </div>
         <div class="sDiv2">	 
             <select name="intro" class="select">
                        <option value="0">全部</option>
                        <option value="is_new">新品</option>
                        <option value="is_recommend">推荐</option>
                    </select>
          </div>
         <div class="sDiv2" style="border:0px">	 
          <input type="text" name="keywords" value="<?php echo $keywords; ?>" placeholder="搜索词" id="input-order-id" class="input-txt">
        </div>
        <div class="sDiv2" style="border:0px">	 
          <input type="submit" class="btn" value="搜索">
        </div>
      </div>
     </form>
    </div>
    <div class="hDiv">
      <div class="hDivBox" id="ajax_return">
        <table cellspacing="0" cellpadding="0" id="table_head">
          <thead>
	        	<tr>
	              <th class="sign" axis="col0">
	                <div style="width: 24px;">
                        <!--<i class="ico-check"></i>-->
                    </div>
	              </th>
	              <th align="left" abbr="order_sn" axis="col3" class="">
	                <div style="text-align: left; width: 560px;" class="">商品名称</div>
	              </th>
	              <th align="left" abbr="consignee" axis="col4" class="">
	                <div style="text-align: left; width: 120px;" class="">价格</div>
	              </th>
	              <th align="center" abbr="article_show" axis="col5" class="">
	                <div style="text-align: center; width: 80px;" class="">库存</div>
	              </th>
	              <th align="center" abbr="article_show" axis="col5" class="" style="display:none;">
	                <div style="text-align: center; width: 80px;" class="">购买数量
	                <input type="checkbox" checked="checked" style="display:none;" /></div>
	              </th>
	              <th align="center" abbr="article_time" axis="col6" class="">
	                <div style="text-align: center; width: 80px;" class="">操作</div>
	              </th>
	              <th style="width:100%" axis="col7">
	                <div></div>
	              </th>
	            </tr>
	          </thead>
        </table>
      </div>
    </div>
    <div class="bDiv" style="height: auto;">
      <div id="flexigrid" cellpadding="0" cellspacing="0" border="0">
      <table cellspacing="0" cellpadding="0" id="goos_table">
         <tbody>
      	<?php if(is_array($goodsList) || $goodsList instanceof \think\Collection || $goodsList instanceof \think\Paginator): $i = 0; $__LIST__ = $goodsList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?>
      	<!--如果有商品规格-->
        <?php if($list['spec_goods'] != null): if(is_array($list['spec_goods']) || $list['spec_goods'] instanceof \think\Collection || $list['spec_goods'] instanceof \think\Paginator): $i = 0; $__LIST__ = $list['spec_goods'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$spec_goods): $mod = ($i % 2 );++$i;?>
			<tr date-id="<?php echo $list['goods_id']; ?>">
	              <td class="sign" axis="col0">
	                <div style="width: 24px;"><i class="ico-check"></i></div>
	              </td>
	              <td align="left" abbr="order_sn" axis="col3" class="">
	                <div style="text-align: left; width: 560px;" class=""><?php echo getSubstr($list['goods_name'],0,33); ?>&nbsp;&nbsp;&nbsp;(&nbsp;<?php echo $spec_goods[key_name]; ?>&nbsp;)</div>
	              </td>
	              <td align="left" abbr="consignee" axis="col4" class="">
	                <div style="text-align: left; width: 120px;" class=""><?php echo $spec_goods[price]; ?></div>
	              </td>
	              <td align="center" abbr="article_show" axis="col5" class="">
	                <div style="text-align: center; width: 80px;" class=""><?php echo $spec_goods[store_count]; ?></div>
	              </td>
	              <td align="center" abbr="article_show" axis="col5" class="" style="display:none;">
	                <div style="text-align: center; width: 120px;" class="">
	                	<input type="text" name="goods_id[<?php echo $list['goods_id']; ?>][<?php echo $spec_goods[key]; ?>][goods_num]"  value="1" class="input-txt" style="width:60px !important;text-align:center" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" style="display:none;" />
	                	<input type="checkbox" style="display:none;" />
	                </div>
	              </td>
	              <td align="center" abbr="article_time" axis="col6" class="">
	                <div style="text-align: center; width: 80px;" class="">
	                	<a class="btn red" href="javascript:void(0);" onclick="delRow(this)"><i class="fa fa-trash-o"></i>删除</a>
	                </div>
	              </td>
	              <td style="width:100%" axis="col7">
	                <div></div>
	              </td>
	          	</tr>
			<?php endforeach; endif; else: echo "" ;endif; else: ?>
        		<tr date-id="<?php echo $list['goods_id']; ?>">
	              <td class="sign" axis="col0">
	                <div style="width: 24px;"><i class="ico-check"></i></div>
	              </td>
	              <td align="left" abbr="order_sn" axis="col3" class="">
	                <div style="text-align: left; width: 560px;" class=""><?php echo getSubstr($list['goods_name'],0,33); ?></div>
	              </td>
	              <td align="left" abbr="consignee" axis="col4" class="">
	                <div style="text-align: left; width: 120px;" class=""><?php echo $list['shop_price']; ?></div>
	              </td>
	              <td align="center" abbr="article_show" axis="col5" class="">
	                <div style="text-align: center; width: 80px;" class="">
	                	<?php echo $list['store_count']; ?>
	                </div>
	              </td>
	              <td align="center" abbr="article_show" axis="col5" class="" style="display:none;" >
	                <div style="text-align: center; width: 120px;" class=""   >
	                	<input type="text" name="goods_id[<?php echo $list['goods_id']; ?>][key][goods_num]" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" class="input-txt" style="width:60px !important;text-align:center" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" value="1" class="input-sm"  style="display:none;" />
	                	<input type="checkbox" style="display:none;" />
	                </div>
	              </td>
	              <td align="center" abbr="article_time" axis="col6" class="">
	                <div style="text-align: center; width: 80px;" class="">
	                	<a class="btn red" href="javascript:void(0);" onclick="javascript:$(this).parent().parent().parent().remove();"><i class="fa fa-trash-o"></i>删除</a>
	                </div>
	              </td>
	              <td style="width:100%" axis="col7">
	                <div></div>
	              </td>
	          	</tr>
        <?php endif; endforeach; endif; else: echo "" ;endif; ?>
	    </tbody>
	    </table>
	    <div class="sDiv" style="float:left;margin-top:10px">
        <div class="sDiv2" style="border:0px">
			    <input type="button" onclick="select_goods()"  class="btn" value="确定">
        </div>
 </div>
 
      </div>
      <div class="iDiv" style="display: none;"></div>
    </div>
    <!--分页位置-->
      <?php echo $page; ?>
   	</div>
</div>
<script type="text/javascript">

$(document).ready(function(){	
	
	$('#flexigrid > table>tbody >tr').click(function(){
	    $(this).toggleClass('trSelected');
	    
	    var checked = $(this).hasClass('trSelected');	
		 $(this).find('input[type="checkbox"]').attr('checked',checked); 
 
	});
	
	$('.ico-check ' , '.hDivBox').click(function(){
		$('tr' ,'.hDivBox').toggleClass('trSelected' , function(index,currentclass){
    		var hasClass = $(this).hasClass('trSelected');
    		$('tr' , '#flexigrid').each(function(){
    			if(hasClass){
    				$(this).addClass('trSelected');
    			}else{
    				$(this).removeClass('trSelected');
    			}
    		});  
    	});
	});
});
 
	
function select_goods()
{	  
 
	   if($("input[type='checkbox']:checked").length == 0)
	   {
		   layer.alert('请选择商品', {icon: 2}); //alert('请选择商品');
		   return false;
	   }
	  // 将没选中的复选框所在的  tr  remove  然后删除复选框
	    $("input[type='checkbox']").each(function(){
		   if($(this).is(':checked') == false)
		   {
			    $(this).parent().parent().parent().remove();
		   }
		   $(this).parent().parent().show();
		   $(this).siblings().show();
		   $(this).remove();
	    });
		$(".btn-info").remove();
		var tabHtml = $('#table_head').append($('#goos_table')).html();
      javascript:window.parent.call_back(tabHtml.replace(/选择/,'购买数量'));
}    
</script>
</body>
</html>
<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:42:"./application/admin/view/plugin\index.html";i:1522317281;s:61:"D:\phpStudy\WWW\www\application\admin\view\public\layout.html";i:1522317281;}*/ ?>
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
<body style="background-color: rgb(255, 255, 255); overflow: auto; cursor: default; -moz-user-select: inherit;">
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>插件管理</h3>
                <h5>网站系统插件索引与管理</h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a data-href="#tab_pay" class="<?php if($type == 'payment' or $type == ''): ?>current<?php endif; ?> tab" data-id=""><span>支付插件</span></a></li>
                <li><a data-href="#tab_login" class="<?php if($type == 'login'): ?>current<?php endif; ?> tab"><span>登录插件</span></a></li>
                <!--li><a data-href="#tab_function" class="<?php if($type == 'function'): ?>current<?php endif; ?>tab"><span>功能插件</span></a></li -->
                <!-- li><a href="http://www.tp-shop.cn/articleList_cat_id_30.html" target="_blank"><span>云插件</span></a></li -->
            </ul>
        </div>
    </div>
    <!-- 操作说明 -->
    <div id="explanation" class="explanation" style="color: rgb(44, 188, 163); background-color: rgb(237, 251, 248); width: 99%; height: 100%;">
        <div id="checkZoom" class="title"><i class="fa fa-lightbulb-o"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span title="收起提示" id="explanationZoom" style="display: block;"></span>
        </div>
        <ul>
            <li>插件内部包括: 支付插件、登录插件、物流插件、</li>
        </ul>
    </div>
    <div class="flexigrid">
        <div class="mDiv">
            <div class="ftitle">
                <h3>插件列表</h3>
                <!--<h5>(共<?php echo $pager->totalRows; ?>条记录)</h5>-->
            </div>
            <div title="刷新数据" class="pReload"><i class="fa fa-refresh"></i></div>
        </div>
        <div class="hDiv">
            <div class="hDivBox">
                <table cellspacing="0" cellpadding="0">
                    <thead>
                    <tr>
                        <th class="sign" axis="col0">
                            <div style="width: 24px;"><i class="ico-check"></i></div>
                        </th>
                        <th align="left" abbr="article_title" axis="col3" class="">
                            <div style="text-align: left; width: 120px;" class="">插件名称</div>
                        </th>
                        <th align="left" abbr="ac_id" axis="col4" class="">
                            <div style="text-align: left; width: 120px;" class="">插件描述</div>
                        </th>
                        <th align="left" abbr="article_show" axis="col5" class="">
                            <div style="text-align: center; width: 120px;" class="">插件图片</div>
                        </th>
                        <th align="center" axis="col1" class="handle">
                            <div style="text-align: center; width: 1250px;">操作</div>
                        </th>
                        <th style="width:100%" axis="col7">
                            <div></div>
                        </th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!--支付插件-->
        <div class="bDiv" id="tab_pay" style="height: auto;<?php if($type != 'payment' AND $type != ''): ?>display: none;<?php endif; ?>">
            <div id="flexigrid" cellpadding="0" cellspacing="0" border="0">
                <table>
                    <tbody>
                    <?php if(is_array($payment) || $payment instanceof \think\Collection || $payment instanceof \think\Paginator): $i = 0; $__LIST__ = $payment;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$p): $mod = ($i % 2 );++$i;?>
                        <tr>
                            <td class="sign">
                                <div style="width: 24px;"><i class="ico-check"></i></div>
                            </td>
                            <td align="left" class="">
                                <div style="text-align: left; width: 120px;"><?php echo $p['name']; ?></div>
                            </td>
                            <td align="left" class="">
                                <div style="text-align: left; width: 120px;"><?php echo $p['desc']; ?></div>
                            </td>
                            <td align="left" class="">
                                <div style="text-align: center; width: 120px;">
                                    <a href="/plugins/payment/<?php echo $p['code']; ?>/<?php echo $p['icon']; ?>" class="pic-thumb-tip" onMouseOver="layer.tips('<img src=/plugins/payment/<?php echo $p['code']; ?>/<?php echo $p['icon']; ?>>',this,{tips: [1, '#fff']});" onMouseOut="layer.closeAll();"><i class="fa fa-picture-o"></i><?php echo $p['code']; ?> - <?php echo $p['icon']; ?></a>
                                </div>
                            </td>
                            <td align="center" class="handle">
                                <div style="text-align: center; width: 250px; max-width:250px;">
                                    <?php if($p['status'] == 0): ?>
                                        <a onClick="installPlugin('<?php echo $p['type']; ?>','<?php echo $p['code']; ?>',1)" class="btn blue"><i class="fa fa-check"></i>一键安装</a>
                                        <?php else: ?>
                                        <a href="<?php echo U('Admin/Plugin/setting',array('type'=>$p['type'],'code'=>$p['code'])); ?>" class="btn blue"><i class="fa fa-pencil-square-o"></i>配置</a>
                                        <a class="btn red" onClick="installPlugin('<?php echo $p['type']; ?>','<?php echo $p['code']; ?>',0)"><i class="fa fa-trash-o"></i>卸载</a>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td align="" class="" style="width: 100%;">
                                <div>&nbsp;</div>
                            </td>
                        </tr>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!--支付插件-->
        <!--登录插件-->
        <div class="bDiv" id="tab_login" style="height: auto;<?php if($type != 'login'): ?>display: none;<?php endif; ?>">
            <div id="flexigrid" cellpadding="0" cellspacing="0" border="0">
                <table>
                    <tbody>
                    <?php if(is_array($login) || $login instanceof \think\Collection || $login instanceof \think\Paginator): $i = 0; $__LIST__ = $login;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$l): $mod = ($i % 2 );++$i;?>
                        <tr>
                            <td class="sign">
                                <div style="width: 24px;"><i class="ico-check"></i></div>
                            </td>
                            <td align="left" class="">
                                <div style="text-align: left; width: 120px;"><?php echo $l['name']; ?></div>
                            </td>
                            <td align="left" class="">
                                <div style="text-align: left; width: 120px;"><?php echo $l['desc']; ?></div>
                            </td>
                            <td align="left" class="">
                                <div style="text-align: center; width: 120px;">
                                    <a href="/plugins/login/<?php echo $l['code']; ?>/<?php echo $l['icon']; ?>" class="pic-thumb-tip" onMouseOver="layer.tips('<img src=/plugins/login/<?php echo $l['code']; ?>/<?php echo $l['icon']; ?>>',this,{tips: [1, '#fff']});" onMouseOut="layer.closeAll();"><i class="fa fa-picture-o"></i></a>
                                </div>
                            </td>
                            <td align="center" class="handle">
                                <div style="text-align: center; width: 170px; max-width:170px;">
                                    <?php if($l['status'] == 0): ?>
                                        <a onClick="installPlugin('<?php echo $l['type']; ?>','<?php echo $l['code']; ?>',1)" class="btn blue"><i class="fa fa-check"></i>一键安装</a>
                                        <?php else: ?>
                                        <a class="btn red" onClick="installPlugin('<?php echo $l['type']; ?>','<?php echo $l['code']; ?>',0)"><i class="fa fa-trash-o"></i>卸载</a>
                                        <a class="btn blue" href="<?php echo U('Admin/Plugin/setting',array('type'=>$l['type'],'code'=>$l['code'])); ?>"><i class="fa fa-pencil-square-o"></i>配置</a>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td align="" class="" style="width: 100%;">
                                <div>&nbsp;</div>
                            </td>
                        </tr>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!--登录插件-->
        <!--功能插件-->
        <div class="bDiv" id="tab_function" style="height: auto;<?php if($type != 'function'): ?>display: none;<?php endif; ?>">
            <div id="flexigrid" cellpadding="0" cellspacing="0" border="0">
                <table>
                    <tbody>
                    <?php if(is_array($function) || $function instanceof \think\Collection || $function instanceof \think\Paginator): $i = 0; $__LIST__ = $function;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$l): $mod = ($i % 2 );++$i;?>
                        <tr>
                            <td class="sign">
                                <div style="width: 24px;"><i class="ico-check"></i></div>
                            </td>
                            <td align="left" class="">
                                <div style="text-align: left; width: 120px;"><?php echo $l['name']; ?></div>
                            </td>
                            <td align="left" class="">
                                <div style="text-align: left; width: 120px;"><?php echo $l['desc']; ?></div>
                            </td>
                            <td align="left" class="">
                                <div style="text-align: center; width: 120px;">
                                    <a href="/plugins/function/<?php echo $l['code']; ?>/<?php echo $l['icon']; ?>" class="pic-thumb-tip" onMouseOver="layer.tips('<img src=/plugins/function/<?php echo $l['code']; ?>/<?php echo $l['icon']; ?>>',this,{tips: [1, '#fff']});" onMouseOut="layer.closeAll();"><i class="fa fa-picture-o"></i></a>
                                </div>
                            </td>
                            <td align="center" class="handle">
                                <div style="text-align: center; width: 170px; max-width:170px;">
                                    <?php if($l['status'] == 0): ?>
                                        <a onClick="installPlugin('<?php echo $l['type']; ?>','<?php echo $l['code']; ?>',1)" class="btn blue"><i class="fa fa-check"></i>一键安装</a>
                                        <?php else: ?>
                                        <a class="btn red" onClick="installPlugin('<?php echo $l['type']; ?>','<?php echo $l['code']; ?>',0)"><i class="fa fa-trash-o"></i>卸载</a>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td align="" class="" style="width: 100%;">
                                <div>&nbsp;</div>
                            </td>
                        </tr>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!--功能插件-->
     </div>
</div>
<script>
    $(document).ready(function(){
        var type = "<?php echo $type; ?>";
        if(type !== ''){
            $('#tab_plugin_'+type).trigger('click');
        }
    });
    $(document).ready(function(){
        // 表格行点击选中切换
        $('#flexigrid > table>tbody >tr').click(function(){
            $(this).toggleClass('trSelected');
        });
        //插件切换列表
        $('.tab-base').find('.tab').click(function(){
            $('.tab-base').find('.tab').each(function(){
                $(this).removeClass('current');
            });
            $(this).addClass('current');
            $('.bDiv').hide();
            var id = $(this).attr('data-href');
            $(id).show();
        });


        // 点击刷新数据
        $('.fa-refresh').click(function(){
            location.href = location.href;
        });

    });



    //插件安装(卸载)
    function installPlugin(type,code,type2){
        var url = '/index.php?m=Admin&c=Plugin&a=install&type='+type+'&code='+code+'&install='+type2;
        $.get(url,function(data){
            var obj = JSON.parse(data);
            if(obj.status == 1){
                layer.alert(obj.msg, {icon: 1});
//                window.location.reload();
                window.location.href='/index.php?m=Admin&c=Plugin&a=index&type='+type;
            }else{
                layer.alert(obj.msg, {icon: 2});
            }
        });
    }
</script>
</body>
</html>
<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:42:"./application/admin/view/freight\info.html";i:1522317281;s:61:"D:\phpStudy\WWW\www\application\admin\view\public\layout.html";i:1522317281;}*/ ?>
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
    .err{color:#F00; display:none;}
    .flexigrid input[type="text"]{width: 120px;}
    .flexigrid {width: 1100px;}
    .w150{text-align: center; width: 150px;}
    .w80{text-align: center; width: 80px;}
</style>
<script src="/public/static/js/layer/laydate/laydate.js"></script>
<body style="background-color: #FFF; overflow: auto;">
<div id="toolTipLayer" style="position: absolute; z-index: 9999; display: none; visibility: visible; left: 95px; top: 573px;"></div>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title"><a class="back" href="javascript:history.back();" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
            <div class="subject">
                <h3>运费模板</h3>
                <h5>运费模板详情页</h5>
            </div>
        </div>
    </div>
    <form class="form-horizontal" id="handleposition" method="post" onsubmit="return false;">
        <input type="hidden" name="template_id" value="<?php echo $freightTemplate['template_id']; ?>">
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>模板名称</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="template_name" value="<?php echo $freightTemplate['template_name']; ?>" class="input-txt">
                    <span class="err" id="err_template_name"></span>
                    <span class="err" id="err_template_id"></span>
                    <p class="notic">请填写模板名称</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>计价方式</label>
                </dt>
                <dd class="opt ctype">
                    <?php if(is_array(\think\Config::get('FREIGHT_TYPE')) || \think\Config::get('FREIGHT_TYPE') instanceof \think\Collection || \think\Config::get('FREIGHT_TYPE') instanceof \think\Paginator): $i = 0; $__LIST__ = \think\Config::get('FREIGHT_TYPE');if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$type): $mod = ($i % 2 );++$i;?>
                        <input name="type" class="type" type="radio" value="<?php echo $key; ?>" <?php if($freightTemplate['type'] === $key): ?>checked='checked'<?php endif; ?>><label><?php echo $type; ?></label>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </dd>
                <span class="err" id="err_type"></span>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>是否启用默认配送配置</label>
                </dt>
                <dd class="opt ctype">
                    <input name="is_enable_default" class="is_enable_default" type="radio" value="0" <?php if($freightTemplate['is_enable_default'] === 0): ?>checked='checked'<?php endif; ?>><label>否</label>
                    <input name="is_enable_default" class="is_enable_default" type="radio" value="1" <?php if($freightTemplate['is_enable_default'] === 1): ?>checked='checked'<?php endif; ?>><label>是</label>
                </dd>
                <span class="err" id="err_enable_default"></span>
            </dl>
            <div class="flexigrid" id="config_table" style="display: none;">
                <div class="hDiv">
                    <div class="hDivBox">
                        <table cellspacing="0" cellpadding="0">
                            <thead>
                            <tr>
                                <th class="left">
                                    <div class="w80"></div>
                                </th>
                                <th align="left">
                                    <div class="w150">配送区域</div>
                                </th>
                                <th align="left">
                                    <div class="first_unit w150">首件</div>
                                </th>
                                <th align="left">
                                    <div class="w150">运费</div>
                                </th>
                                <th align="center">
                                    <div class="continue_unit w150">续件</div>
                                </th>
                                <th align="center">
                                    <div class="w150">运费</div>
                                </th>
                                <th align="left" class="handle">
                                    <div class="w150">操作</div>
                                </th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="tDiv">
                    <div class="tDiv2">
                        <a class="new_config">
                            <div class="fbutton">
                                <div title="新增自定义区域" class="add">
                                    <span><i class="fa fa-plus"></i>新增自定义区域</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="tDiv2">
                        <span class="err" id="err_config_list" style="line-height:25px;font-size: 12px;">请添加配送区域</span>
                    </div>
                    <div style="clear:both"></div>
                </div>
                <div class="bDiv" style="height: auto;">
                    <div id="flexigrid">
                        <table>
                            <tbody id="config_list">
                            <?php if(is_array($freightTemplate[freightConfig]) || $freightTemplate[freightConfig] instanceof \think\Collection || $freightTemplate[freightConfig] instanceof \think\Paginator): $i = 0; $__LIST__ = $freightTemplate[freightConfig];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$config): $mod = ($i % 2 );++$i;if($config[is_default] == 1): ?>
                                    <tr class="default_config">
                                        <td class="left">
                                            <div class="w80">默认配置<input name="is_default[]" value="<?php echo $config['is_default']; ?>" type="hidden"></div>
                                        </td>
                                        <td align="left">
                                            <div class="w150">
                                                <input class="select_area" readonly name="" value="中国" type="text">
                                                <input name="area_ids[]" class="area_ids" value="0" type="hidden">
                                                <input name="config_id[]" value="<?php echo $config['config_id']; ?>" type="hidden">
                                            </div>
                                        </td>
                                        <td align="left">
                                            <div class="w150">
                                                <input name="first_unit[]" value="<?php echo $config['first_unit']; ?>" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" type="text">
                                                <span class="first_unit_span">克</span>
                                            </div>
                                        </td>
                                        <td align="left">
                                            <div class="w150">
                                                <input name="first_money[]" value="<?php echo $config['first_money']; ?>" type="text"><span>元</span>
                                            </div>
                                        </td>
                                        <td align="left">
                                            <div class="w150"><input name="continue_unit[]" value="<?php echo $config['continue_unit']; ?>"  onpaste="this.value=this.value.replace(/[^\d.]/g,'')" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" type="text">
                                                <span class="continue_unit_span">克</span>
                                            </div>
                                        </td>
                                        <td align="left">
                                            <div class="w150"><input name="continue_money[]" value="<?php echo $config['continue_money']; ?>" type="text"><span>元</span>
                                            </div>
                                        </td>
                                        <td align="left" class="handle">
                                            <div class="w150">
                                                <a class="btn red" href="javascript:void(0)" onclick="$(this).parent().parent().parent().remove();"><i class="fa fa-trash-o"></i>删除</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <tr>
                                        <td class="left">
                                            <div class="w80"><input name="is_default[]" value="<?php echo $config['is_default']; ?>" type="hidden"></div>
                                        </td>
                                        <?php $region_name = '';$region_id = ''; if(is_array($config[freightRegion]) || $config[freightRegion] instanceof \think\Collection || $config[freightRegion] instanceof \think\Paginator): $i = 0; $__LIST__ = $config[freightRegion];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$freight_region): $mod = ($i % 2 );++$i;$region_name = $region_name . $freight_region->region[name] . ',';$region_id = $region_id . $freight_region->region[id] . ','; endforeach; endif; else: echo "" ;endif; $region_name = trim($region_name,',');$region_id = trim($region_id,','); ?>
                                        <td align="left">
                                            <div class="w150">
                                                <input class="select_area" readonly name="" value="<?php echo $region_name; ?>" type="text">
                                                <input name="area_ids[]" class="area_ids" value="<?php echo $region_id; ?>" type="hidden">
                                                <input name="config_id[]" value="<?php echo $config['config_id']; ?>" type="hidden">
                                            </div>
                                        </td>
                                        <td align="left">
                                            <div class="w150">
                                                <input name="first_unit[]" value="<?php echo $config['first_unit']; ?>" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" type="text">
                                                <span class="first_unit_span">克</span>
                                            </div>
                                        </td>
                                        <td align="left">
                                            <div class="w150">
                                                <input name="first_money[]" value="<?php echo $config['first_money']; ?>" type="text"><span>元</span>
                                            </div>
                                        </td>
                                        <td align="left">
                                            <div class="w150"><input name="continue_unit[]" value="<?php echo $config['continue_unit']; ?>"  onpaste="this.value=this.value.replace(/[^\d.]/g,'')" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" type="text">
                                                <span class="continue_unit_span">克</span>
                                            </div>
                                        </td>
                                        <td align="left">
                                            <div class="w150"><input name="continue_money[]" value="<?php echo $config['continue_money']; ?>" type="text"><span>元</span>
                                            </div>
                                        </td>
                                        <td align="left" class="handle">
                                            <div class="w150">
                                                <a class="btn red" href="javascript:void(0)" onclick="$(this).parent().parent().parent().remove();"><i class="fa fa-trash-o"></i>删除</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; endforeach; endif; else: echo "" ;endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="iDiv" style="display: none;"></div>
                </div>
            </div>

            <div class="bot"><a onclick="verifyForm()" class="ncap-btn-big ncap-btn-green">确认提交</a></div>
        </div>
    </form>
</div>
<script type="text/javascript">
    var type;//计价方式
    var unit = '件';
    $(function () {
        $(document).on("click", '#submit', function (e) {
            $('#submit').attr('disabled',true);
            verifyForm();
        })
    })
    //运费配置单个对象
    function ConfigItem(config_id, area_ids, first_unit, first_money, continue_unit, continue_money, is_default) {
        this.config_id = config_id;
        this.area_ids = area_ids;
        this.first_unit = first_unit;
        this.first_money = first_money;
        this.continue_unit = continue_unit;
        this.continue_money = continue_money;
        this.is_default = is_default;
    }
    function verifyForm(){
        $('span.err').hide();
        var config_list = new Array();
        var template_id = $("input[name='template_id']").val();
        var template_name = $("input[name='template_name']").val();
        var type = $("input[name='type']:checked").val();
        var is_enable_default = $("input[name='is_enable_default']:checked").val();
        var config_item = $("#config_list").find('tr');
        config_item.each(function(i,o){
            var area_ids_input = $(this).find("input[name^='area_ids']");
            var first_unit_val = $(this).find("input[name^='first_unit']").val();
            var config_id_val = $(this).find("input[name^='config_id']").val();
            var first_money_val = $(this).find("input[name^='first_money']").val();
            var continue_unit_val = $(this).find("input[name^='continue_unit']").val();
            var continue_money_val = $(this).find("input[name^='continue_money']").val();
            var is_default_val = $(this).find("input[name^='is_default']").val();
            if (area_ids_input.val().length > 0 || $('.default_config').length > 0) {
                var configItem = new ConfigItem(config_id_val, area_ids_input.val(), first_unit_val, first_money_val, continue_unit_val, continue_money_val, is_default_val);
                config_list.push(configItem);
            }
        })
        $.ajax({
            type: "POST",
            url: "<?php echo U('Freight/save'); ?>",
            data: {template_id:template_id,template_name:template_name,type:type,config_list:config_list,is_enable_default:is_enable_default},
            async:false,
            dataType: "json",
            error: function () {
                layer.alert("服务器繁忙, 请联系管理员!");
            },
            success: function (data) {
                if (data.status == 1) {
                    layer.msg(data.msg,{icon: 1,time: 2000},function(){
                        location.href = "<?php echo U('Freight/index'); ?>";
                    });
                } else {
                    $('#submit').attr('disabled',false);
                    $.each(data.result, function (index, item) {
                        $('span.err').show();
                        $('#err_'+index).text(item);
                    });
                    layer.msg(data.msg, {icon: 2,time: 3000});
                }
            }
        });
    }
    $(function () {
        $(document).on("click", '.select_area', function (e) {
            $('.select_area').removeClass('select_area_focus');
            $(this).addClass('select_area_focus');
            var url = "/index.php?m=Admin&c=Freight&a=area";
            layer.open({
                type: 2,
                title: '选择地区',
                shadeClose: true,
                shade: 0.2,
                area: ['420px', '400px'],
                content: url
            });
        })
    })
    $(function () {
        $(document).on("click", '.new_config', function (e) {
            var html =  '<tr><td class="left"> <div class="w80">' +
                    '<input name="is_default[]" value="0" type="hidden"></div></td> <td align="left"> <div class="w150"> ' +
                    '<input class="select_area" readonly name="" value="" type="text"> <input name="area_ids[]" class="area_ids" value="" type="hidden"> ' +
                    '<input name="config_id[]" value="" type="hidden"> </div> </td> <td align="left"> <div class="w150"> ' +
                    '<input name="first_unit[]" value="" onpaste="this.value=this.value.replace(/[^\\d.]/g,\'\')" onkeyup="this.value=this.value.replace(/[^\\d.]/g,\'\')" type="text"> ' +
                    '<span class="first_unit_span">'+unit+'</span> </div> </td> <td align="left"> <div class="w150"> ' +
                    '<input name="first_money[]" value="" type="text"><span>元</span> </div> </td> <td align="left"> <div class="w150">' +
                    '<input name="continue_unit[]" value="" onpaste="this.value=this.value.replace(/[^\\d.]/g,\'\')" onkeyup="this.value=this.value.replace(/[^\\d.]/g,\'\')" type="text"> ' +
                    '<span class="continue_unit_span">'+unit+'</span> </div> </td> <td align="left"> <div class="w150">' +
                    '<input name="continue_money[]" value="" type="text"><span>元</span> </div> </td> <td align="left" class="handle"> <div class="w150"> ' +
                    '<a class="btn red" onclick="$(this).parent().parent().parent().remove();"><i class="fa fa-trash-o"></i>删除</a> </div> </td> </tr>';
            $('#config_list').append(html);
        })
    })

    $(function () {
        $(document).on("click", '.is_enable_default', function (e) {
            initDefault();
        })
    })
    function initDefault(){
        var default_config_length = $('.default_config').length;
        var is_enable_default = $("input[name='is_enable_default']:checked").val();
        if (is_enable_default == 1 && default_config_length == 0) {
            var html =  '<tr class="default_config"><td class="left"> <div class="w80">' +
                    '默认配置<input name="is_default[]" value="1" type="hidden"></div></td> <td align="left"> <div class="w150"> ' +
                    '<input readonly name="" value="中国" type="text"> <input name="area_ids[]" class="area_ids" value="" type="hidden"> ' +
                    '<input name="config_id[]" value="" type="hidden"> </div> </td> <td align="left"> <div class="w150"> ' +
                    '<input name="first_unit[]" value="" onpaste="this.value=this.value.replace(/[^\\d.]/g,\'\')" onkeyup="this.value=this.value.replace(/[^\\d.]/g,\'\')" type="text"> ' +
                    '<span class="first_unit_span">'+unit+'</span> </div> </td> <td align="left"> <div class="w150"> ' +
                    '<input name="first_money[]" value="" type="text"><span>元</span> </div> </td> <td align="left"> <div class="w150">' +
                    '<input name="continue_unit[]" value="" onpaste="this.value=this.value.replace(/[^\\d.]/g,\'\')" onkeyup="this.value=this.value.replace(/[^\\d.]/g,\'\')" type="text"> ' +
                    '<span class="continue_unit_span">'+unit+'</span> </div> </td> <td align="left"> <div class="w150">' +
                    '<input name="continue_money[]" value="" type="text"><span>元</span> </div> </td> <td align="left" class="handle"> <div class="w150"> ' +
                    '</div> </td> </tr>';
            $('#config_list').prepend(html);
        }else if(is_enable_default == 0){
            $('.default_config').remove();
        }
    }
    $(document).ready(function(){
        type = $("input[name='type']:checked").val();
        initType();
        initDefault();
    });
    $(function () {
        $(document).on("click", ".type", function (e) {
            if(typeof(type) != 'undefined' && type != $(this).val()){
                type = $(this).val();
                clear_freight_config();
            }else{
                type = $("input[name='type']:checked").val();
                initType();
            }
        })
    })
    function initType(){
        var config_table = $('#config_table');
        if(parseInt(type) >= 0){
            config_table.show();
        }
        var first_unit = $('.first_unit');
        var continue_unit = $('.continue_unit');
        var first_unit_span = $('.first_unit_span');
        var continue_unit_span = $('.continue_unit_span');
        switch(parseInt(type))
        {
            case 0:
                unit = "件";
                first_unit.html('首件');
                continue_unit.html('续件');
                break;
            case 1:
                unit = "克";
                first_unit.html('首重');
                continue_unit.html('续重');
                break;
            case 2:
                unit = "立方米";
                first_unit.html('首体积');
                continue_unit.html('续体积');
                break;
        }
        first_unit_span.html(unit);
        continue_unit_span.html(unit);
    }

    /**
     * 清空运费模板信息
     */
    function clear_freight_config() {
        var template_id = $("input[name='template_id']").val();
        layer.confirm('切换计价方式后，当前模板的运费信息将被清空，确定继续吗？', {
            btn: ['确定', '取消']
        }, function () {
            if (template_id > 0) {
                $('#config_list').empty();
                initType();
                layer.closeAll();
            }else{
                layer.closeAll();
                type = $("input[name='type']:checked").val();
                initType();
            }
        }, function (index) {
            $("input[name='type']").attr("checked",false);
            $("input[name='type'][value="+type+"]").attr("checked",true);
            type = $("input[name='type']:checked").val();
            initType();
            layer.close(index);
        });
    }
    function call_back(area_list) {
        var area_list_name = '';
        var area_list_id = '';
        $.each(area_list, function (index, item) {
            area_list_name += item.name + ',';
            area_list_id += item.id + ',';
        });
        var area_focus = $('.select_area_focus');
        if(area_list_id.length > 1){
            area_list_id = area_list_id.substr(0,area_list_id.length-1);
            area_list_name = area_list_name.substr(0,area_list_name.length-1);
        }
        area_focus.val(area_list_name);
        area_focus.parent().find('.area_ids').val(area_list_id);
        layer.closeAll('iframe');
    }
</script>
</body>
</html>
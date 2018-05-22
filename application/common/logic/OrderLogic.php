<?php
/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 采用最新Thinkphp5助手函数特性实现单字母函数M D U等简写方式
 * ============================================================================
 * Author: 当燃
 * Date: 2016-03-19
 */

namespace app\common\logic;

use app\common\logic\wechat\WechatUtil;
use app\common\model\Order;
use app\common\model\SpecGoodsPrice;
use think\Db;
/**
 * Class orderLogic
 * @package Common\Logic
 */
class OrderLogic
{
    protected $user_id=0;
    public function setUserId($user_id){
        $this->user_id=$user_id;
    }
	/**
	 * 取消订单
	 * @param $user_id|用户ID
	 * @param $order_id|订单ID
	 * @param string $action_note 操作备注
	 * @return array
	 */
	public function cancel_order($user_id,$order_id,$action_note='您取消了订单'){
		$order = M('order')->where(array('order_id'=>$order_id,'user_id'=>$user_id))->find();
		//检查是否未支付订单 已支付联系客服处理退款
		if(empty($order))
			return array('status'=>-1,'msg'=>'订单不存在','result'=>'');
		if($order['order_status'] == 3){
			return array('status'=>-1,'msg'=>'该订单已取消','result'=>'');
		}
		//检查是否未支付的订单
		if($order['pay_status'] > 0 || $order['order_status'] > 0)
			return array('status'=>-1,'msg'=>'支付状态或订单状态不允许','result'=>'');
		//获取记录表信息
		//$log = M('account_log')->where(array('order_id'=>$order_id))->find();
		//有余额支付的情况
		if($order['user_money'] > 0 || $order['integral'] > 0){
			accountLog($user_id,$order['user_money'],$order['integral'],"订单取消，退回{$order['user_money']}元,{$order['integral']}积分",0,$order['order_id'],$order['order_sn']);
		}

		if($order['coupon_price'] >0){
			$res = array('use_time'=>0,'status'=>0,'order_id'=>0);
			M('coupon_list')->where(array('order_id'=>$order_id,'uid'=>$user_id))->save($res);
		}

		$row = M('order')->where(array('order_id'=>$order_id,'user_id'=>$user_id))->save(array('order_status'=>3));
		$reduce = tpCache('shopping.reduce');
		if($reduce == 1 || empty($reduce)){
			$this->alterReturnGoodsInventory($order);
		}
		$data['order_id'] = $order_id;
		$data['action_user'] = 0;
		$data['action_note'] = $action_note;
		$data['order_status'] = 3;
		$data['pay_status'] = $order['pay_status'];
		$data['shipping_status'] = $order['shipping_status'];
		$data['log_time'] = time();
		$data['status_desc'] = '用户取消订单';
		M('order_action')->add($data);//订单操作记录
		if(!$row)
			return array('status'=>-1,'msg'=>'操作失败','result'=>'');
		return array('status'=>1,'msg'=>'操作成功','result'=>'');

	}

	public function addReturnGoods($rec_id,$order)
	{
		$data = I('post.');
		$confirm_time_config = tpCache('shopping.auto_service_date');//后台设置多少天内可申请售后
		$confirm_time = $confirm_time_config * 24 * 60 * 60;
		if ((time() - $order['confirm_time']) > $confirm_time && !empty($order['confirm_time'])) {
			return ['result'=>-1,'msg'=>'已经超过' . ($confirm_time_config ?: 0) . "天内退货时间"];
		}
        
        $img = $this->uploadReturnGoodsImg();
        if ($img['status'] !== 1) {
            return $img;
        }
        $data['imgs'] = $img['result'] ?: ($data['imgs'] ?: ''); //兼容小程序，多传imgs
	
		$data['addtime'] = time();
		$data['user_id'] = $order['user_id'];
		$order_goods = M('order_goods')->where(array('rec_id'=>$rec_id))->find();
		if($data['type'] < 2){
            $useRapplyReturnMoney = $order_goods['final_price']*$data['goods_num'];    //要退的总价 商品购买单价*申请数量
            $userExpenditureMoney = $order['goods_price']-$order['order_prom_amount']-$order['coupon_price'];    //用户实际使用金额
            $rate = round($useRapplyReturnMoney/$userExpenditureMoney,8);
            $data['refund_integral'] = floor($rate*$order['integral']);//该退积分支付
            $integralDeductionMoney = $data['refund_integral']/tpCache('shopping.point_rate') ;  //积分抵了多少钱，要扣掉
            if($order['order_amount']>0){
                $order_amount = $order['order_amount']+$order['paid_money'];   //三方支付总额，预售要退定金
                if($order_amount>$order['shipping_price']){
                    $data['refund_money'] = round($rate*($order_amount - $order['shipping_price']),2); //退款金额
                    $data['refund_deposit'] = $rate*$order['user_money'];
                }else{
                    $data['refund_deposit'] = round($rate*($order['user_money'] - $order['shipping_price']+$order['paid_money'])-$integralDeductionMoney,2);//该退余额支付部分
                }
            }else{
                $data['refund_deposit'] = round($useRapplyReturnMoney-$integralDeductionMoney,2);//该退余额支付部分
            }
		}

		if(!empty($data['id'])){
			$result = M('return_goods')->where(array('id'=>$data['id']))->save($data);
		}else{
			$result = M('return_goods')->add($data);
		}
	
		if($result){
			return ['status'=>1,'msg'=>'申请成功'];
		}
		return ['status'=>-1,'msg'=>'申请失败'];
	}
    
    /**
     * 上传退换货图片，兼容小程序
     * @return array
     */
    public function uploadReturnGoodsImg()
    {
        $return_imgs = '';
        if ($_FILES['return_imgs']['tmp_name']) {
			$files = request()->file("return_imgs");
            if (is_object($files)) {
                $files = [$files]; //可能是一张图片，小程序情况
            }
			$image_upload_limit_size = config('image_upload_limit_size');
			$validate = ['size'=>$image_upload_limit_size,'ext'=>'jpg,png,gif,jpeg'];
			$dir = UPLOAD_PATH.'return_goods/';
			if (!($_exists = file_exists($dir))){
				$isMk = mkdir($dir);
			}
			$parentDir = date('Ymd');
			foreach($files as $key => $file){
				$info = $file->rule($parentDir)->validate($validate)->move($dir, true);
				if($info){
					$filename = $info->getFilename();
					$new_name = '/'.$dir.$parentDir.'/'.$filename;
					$return_imgs[]= $new_name;
				}else{
                    return ['status' => -1, 'msg' => $file->getError()];//上传错误提示错误信息
				}
			}
			if (!empty($return_imgs)) {
				$return_imgs = implode(',', $return_imgs);// 上传的图片文件
			}
		}
        
        return ['status' => 1, 'msg' => '操作成功', 'result' => $return_imgs];
    }
	
    /**
     * 获取可申请退换货订单商品
     * @param $sale_t
     * @param $keywords
     * @param $user_id
     * @return array
     */
    public function getReturnGoodsIndex($sale_t, $keywords, $user_id)
    {
        if($keywords){
            $condition['order_sn'] = $keywords;
        }
        if($sale_t == 1){
            //三个月内
            $condition['add_time'] = array('gt' , 'DATE_SUB(CURDATE(), INTERVAL 3 MONTH)');
        }else if($sale_t == 2){
            //三个月前
            $condition['add_time'] = array('lt' , 'DATE_SUB(CURDATE(), INTERVAL 3 MONTH)');
        }
    	$condition['user_id'] = $user_id;
    	$condition['pay_status'] = 1;
    	$condition['shipping_status'] = 1;
    	$condition['deleted'] = 0;
    	$count = M('order')->where($condition)->count();
    	$Page  = new \think\Page($count,10);
    	$show = $Page->show();
    	$order_list = M('order')->where($condition)->order('order_id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
    	
    	foreach ($order_list as $k=>$v) {
            $order_list[$k] = set_btn_order_status($v);  // 添加属性  包括按钮显示属性 和 订单状态显示属性
            $data = M('order_goods')->where(['order_id'=>$v['order_id'],'is_send'=>['lt',2]])->select();
            if(!empty($data)){
                $order_list[$k]['goods_list'] = $data;
            }else{
                unset($order_list[$k]);  //除去没有可申请的订单
            }

    	}

        return [
            'order_list' => $order_list,
            'page' => $show
        ];
    }

    /**
     * 获取退货列表
     * @param type $keywords
     * @param type $addtime
     * @param type $status
     * @return type
     */
    public function getReturnGoodsList($keywords, $addtime, $status, $user_id = 0)
	{
		if($keywords){
            $where['order_sn|goods_name'] = array('like',"%$keywords%");
    	}
    	if($status === '0' || !empty($status)){
            $where['status'] = $status;
    	}
    	if($addtime == 1){
            $where['addtime'] = array('gt',(time()-90*24*3600));
    	}
    	if($addtime == 2){
            $where['addtime'] = array('lt',(time()-90*24*3600));
    	}
    	$query = M('return_goods')->alias('r')->field('r.*,g.goods_name')
                ->join('__ORDER__ o', 'r.order_id = o.order_id AND o.deleted = 0 AND o.user_id='.$user_id)
                ->join('__GOODS__ g', 'r.goods_id = g.goods_id', 'LEFT')
                ->where($where);
        $query2 = clone $query;
        $count = $query->count();
    	$page = new \think\Page($count,10);
    	$list = $query2->order("id desc")->limit($page->firstRow, $page->listRows)->select();
    	$goods_id_arr = get_arr_column($list, 'goods_id');
    	if(!empty($goods_id_arr)) {
            $goodsList = M('goods')->where("goods_id in (".  implode(',',$goods_id_arr).")")->getField('goods_id,goods_name');
        }
        
        return [
            'goodsList' => $goodsList,
            'return_list' => $list,
            'page' => $page->show()
        ];
	}
    
    /**
     * 删除订单
     * @param type $order_id
     * @return type
     */
    public function delOrder($order_id)
    {
        $validate = validate('order');
        if (!$validate->scene('del')->check(['order_id' => $order_id])) {
            return ['status' => 0, 'msg' => $validate->getError()];
        }
        if(empty($this->user_id))return ['status'=>-1,'msg'=>'非法操作'];
        $row = M('order')->where(['user_id'=>$this->user_id,'order_id'=>$order_id])->update(['deleted'=>1]);
        if (!$row) {
            M('order_goods')->where(['order_id'=>$order_id])->update(['deleted'=>1]);
            return ['status'=>-1,'msg'=>'删除失败'];
        }
        return ['status'=>1,'msg'=>'删除成功'];
    }
    
    /**
     * 记录取消订单
     */
    public function recordRefundOrder($user_id, $order_id, $user_note, $consignee, $mobile)
    {
    	$order = M('order')->where(['order_id' => $order_id, 'user_id' => $user_id])->find();
    	if (!$order) {
    		return ['status' => -1, 'msg' => '订单不存在'];
    	}
    	$order_return_num = M('return_goods')->where(['order_id' => $order_id, 'user_id' => $user_id,'status'=>['neq',5]])->count();
    	if($order_return_num > 0){
    		return ['status' => -1, 'msg' => '该订单中有商品正在申请售后'];
    	}
    	$order_status = 3;//已取消
    	$order_info = ['order_status'=> $order_status];
		if($mobile){
			$order_info['mobile'] = $mobile;
		}
		if($consignee){
			$order_info['consignee'] = $consignee;
		}
		if($user_note){
			$order_info['user_note'] = $user_note;
			$data['action_note'] = $user_note;
		}
    
    	$result = M('order')->where(['order_id' => $order_id])->update($order_info);
    	if (!$result) {
    		return ['status' => 0, 'msg' => '操作失败'];
    	}
    
    	$data['order_id'] = $order_id;
    	$data['action_user'] = $user_id;
    	$data['order_status'] = $order_status;
    	$data['pay_status'] = $order['pay_status'];
    	$data['shipping_status'] = $order['shipping_status'];
    	$data['log_time'] = time();
    	$data['status_desc'] = '用户取消已付款订单';
    	M('order_action')->add($data);//订单操作记录
    	return ['status' => 1, 'msg' => '提交成功'];
    }

	/**
	 * 	生成兑换码
	 * 长度 =3位 + 4位 + 2位 + 3位  + 1位 + 5位随机  = 18位
	 * @param $order
	 * @return mixed
	 */
	function make_virtual_code($order){
		$order_goods = M('order_goods')->where(array('order_id'=>$order['order_id']))->find();
		$goods = M('goods')->where(array('goods_id'=>$order_goods['goods_id']))->find();
		M('order')->where(array('order_id'=>$order['order_id']))->save(array('order_status'=>1,'shipping_time'=>time()));
		$perfix = mt_rand(100,999);
		$perfix .= sprintf('%04d', $order['user_id'] % 10000)
				. sprintf('%02d', (int) $order['user_id'] % 100).sprintf('%03d', (float) microtime() * 1000);

		for ($i = 0; $i < $order_goods['goods_num']; $i++) {
			$order_code[$i]['order_id'] = $order['order_id'];
			$order_code[$i]['user_id'] = $order['user_id'];
			$order_code[$i]['vr_code'] = $perfix. sprintf('%02d', (int) $i % 100) . rand(5,1);
			$order_code[$i]['pay_price'] = $goods['shop_price'];
			$order_code[$i]['vr_indate'] = $goods['virtual_indate'];
			$order_code[$i]['vr_invalid_refund'] = $goods['virtual_refund'];
		}

		$res = checkEnableSendSms("7");

		//生成虚拟订单, 向用户发送短信提醒
		if($res && $res['status'] ==1){
			$sender = $order['mobile'];
			$goods_name = $goods['goods_name'];
			$goods_name = getSubstr($goods_name, 0, 10);
			$params = array('goods_name'=>$goods_name);
			sendSms("7", $sender, $params);
		}

		return M('vr_order_code')->insertAll($order_code);
	}

	/**
	 * 自动取消订单
	 */
	public function abolishOrder(){
		$set_time=1; //自动取消时间/天 默认1天
		$abolishtime = strtotime("-$set_time day");
		$order_where = [
				'user_id'      =>$this->user_id,
				'add_time'     =>['lt',$abolishtime],
				'pay_status'   =>0,
				'order_status' => 0
		];
		$order = Db::name('order')->where($order_where)->getField('order_id',true);
		foreach($order as $key =>$value){
			$result = $this->cancel_order($this->user_id,$value);
		}
		return $result;
	}

	/**
	 * 添加预售商品订单
	 * @param $user_id
	 * @param $address_id
	 * @param $invoice_title
	 * @param $act_id
	 * @param $pre_sell_price
	 * @param $taxpayer
	 * @return array
	 */
	public function addPreSellOrder($user_id,$address_id,$invoice_title,$act_id,$pre_sell_price, $taxpayer="")
	{
		// 仿制灌水 1天只能下 50 单
		$order_count = M('Order')->where("user_id= $user_id and order_sn like '".date('Ymd')."%'")->count(); // 查找购物车商品总数量
		if($order_count >= 50){
			return array('status'=>-9,'msg'=>'一天只能下50个订单','result'=>'');
		}
		$address = M('UserAddress')->where(array('address_id' => $address_id))->find();
		$data = array(
				'order_sn'         => date('YmdHis').rand(1000,9999), // 订单编号
				'user_id'          =>$user_id, // 用户id
				'consignee'        =>$address['consignee'], // 收货人
				'province'         =>$address['province'],//'省份id',
				'city'             =>$address['city'],//'城市id',
				'district'         =>$address['district'],//'县',
				'twon'             =>$address['twon'],// '街道',
				'address'          =>$address['address'],//'详细地址',
				'mobile'           =>$address['mobile'],//'手机',
				'zipcode'          =>$address['zipcode'],//'邮编',
				'email'            =>$address['email'],//'邮箱',
				'invoice_title'    =>$invoice_title, //'发票抬头',
				'taxpayer'    	   =>$taxpayer, //'纳税人识别号',
				'goods_price'      =>$pre_sell_price['cut_price'] * $pre_sell_price['goods_num'],//'商品价格',
				'total_amount'     =>$pre_sell_price['cut_price'] * $pre_sell_price['goods_num'],// 订单总额
				'add_time'         =>time(), // 下单时间
				'prom_type'        => 4,
				'prom_id'          => $act_id
		);
		if($pre_sell_price['deposit_price'] == 0){
			//无定金
			$data['order_amount'] = $pre_sell_price['cut_price'] * $pre_sell_price['goods_num'];//'应付款金额',
		}else{
			//有定金
			$data['order_amount'] = $pre_sell_price['deposit_price'] * $pre_sell_price['goods_num'];//'应付款金额',
		}
		$order_id = Db::name('order')->insertGetId($data);
//        M('goods_activity')->where(array('act_id'=>$act_id))->setInc('act_count',$pre_sell_price['goods_num']);
		if($order_id === false){
			return array('status'=>-8,'msg'=>'添加订单失败','result'=>NULL);
		}
		logOrder($order_id,'您提交了订单，请等待系统确认','提交订单',$user_id);
		$order = M('Order')->where("order_id = $order_id")->find();
		$goods_activity = M('goods_activity')->where(array('act_id'=>$act_id))->find();
		$goods = M('goods')->where(array('goods_id'=>$goods_activity['goods_id']))->find();
		$data2['order_id']           = $order_id; // 订单id
		$data2['goods_id']           = $goods['goods_id']; // 商品id
		$data2['goods_name']         = $goods['goods_name']; // 商品名称
		$data2['goods_sn']           = $goods['goods_sn']; // 商品货号
		$data2['goods_num']          = $pre_sell_price['goods_num']; // 购买数量
		$data2['final_price']        = $pre_sell_price['cut_price']; // 市场价
		$data2['goods_price']        = $goods['shop_price']; // 商品团价
		$data2['cost_price']         = $goods['cost_price']; // 成本价
		$data2['member_goods_price'] = $pre_sell_price['cut_price']; //预售价钱
		$data2['give_integral']      = $goods_activity['integral']; // 购买商品赠送积分
		$data2['prom_type']          = 4; // 0 普通订单,1 限时抢购, 2 团购 , 3 促销优惠 ,4 预售商品
		$data2['prom_id']    		 = $goods_activity['act_id'];
		Db::name('order_goods')->insert($data2);
		// 如果有微信公众号 则推送一条消息到微信
		$user = M('OauthUsers')->where(['user_id'=>$user_id , 'oauth'=>'weixin' , 'oauth_child'=>'mp'])->find();
		if ($user['oauth']== 'weixin') {
			$wx_content = "你刚刚下了一笔预售订单:{$order['order_sn']} 尽快支付,过期失效!";
            $wechat = new WechatUtil;
            $wechat->sendMsg($user['openid'], 'text', $wx_content);
		}
		return array('status'=>1,'msg'=>'提交订单成功','result'=>$order['order_sn']); // 返回新增的订单id
	}
	
	/**
	 * 获取订单 order_sn
	 * @return string
	 */
	public function get_order_sn()
	{
	    $order_sn = null;
	    // 保证不会有重复订单号存在
	    while(true){
	        $order_sn = date('YmdHis').rand(1000,9999); // 订单编号	        
	        $order_sn_count = M('order')->where("order_sn = ".$order_sn)->count();
	        if($order_sn_count == 0)
	            break;
	    }
	    
	    return $order_sn;
	}

    /*
     * 订单操作记录
     */
    public function orderActionLog($order_id,$action,$note=''){
        $order = M('order')->where(array('order_id'=>$order_id))->find();
        $data['order_id'] = $order_id;
        $data['action_user'] = session('admin_id');
        $data['action_note'] = $note;
        $data['order_status'] = $order['order_status'];
        $data['pay_status'] = $order['pay_status'];
        $data['shipping_status'] = $order['shipping_status'];
        $data['log_time'] = time();
        $data['status_desc'] = $action;
        return M('order_action')->add($data);//订单操作记录
    }
	/**
	 * 取消订单后改变库存，根据不同的规格，商品活动修改对应的库存
	 * @param $order
     * @param $rec_id|订单商品表id 如果有只返还订单某个商品的库存,没有返还整个订单
     */
    public function alterReturnGoodsInventory($order, $rec_id='')
	{
        if($rec_id){
            $orderGoodsWhere['rec_id'] = $rec_id;
            $retunn_info = Db::name('return_goods')->where($orderGoodsWhere)->select(); //查找购买数量和购买规格
            $order_goods_prom = Db::name('order_goods')->where($orderGoodsWhere)->find(); //购买时参加的活动
            $order_goods = $retunn_info;
            $order_goods[0]['prom_type'] = $order_goods_prom['prom_type'];
            $order_goods[0]['prom_id'] = $order_goods_prom['prom_id'];
        }else{
            $orderGoodsWhere = ['order_id'=>$order['order_id']];
            $order_goods = Db::name('order_goods')->where($orderGoodsWhere)->select(); //查找购买数量和购买规格
        }
		foreach($order_goods as $key=>$val){
			if(!empty($val['spec_key'])){ // 先到规格表里面扣除数量
				$SpecGoodsPrice = new SpecGoodsPrice();
				$specGoodsPrice = $SpecGoodsPrice::get(['goods_id' => $val['goods_id'], 'key' => $val['spec_key']]);
				if($specGoodsPrice){
					$specGoodsPrice->store_count = $specGoodsPrice->store_count + $val['goods_num'];
					$specGoodsPrice->save();//有规格则增加商品对应规格的库存
				}
			}else{
				M('goods')->where(['goods_id' => $val['goods_id']])->setInc('store_count', $val['goods_num']);//没有规格则增加商品库存
			}
			update_stock_log($order['user_id'], $val['goods_num'], $val, $order['order_sn']);//库存日志

			Db::name('Goods')->where("goods_id", $val['goods_id'])->setDec('sales_sum', $val['goods_num']); // 减少商品销售量
			//更新活动商品购买量
			if ($val['prom_type'] == 1 || $val['prom_type'] == 2) {
				$GoodsPromFactory = new GoodsPromFactory();
				$goodsPromLogic = $GoodsPromFactory->makeModule($val, $specGoodsPrice);
				$prom = $goodsPromLogic->getPromModel();
				if ($prom['is_end'] == 0) {
					$tb = $val['prom_type'] == 1 ? 'flash_sale' : 'group_buy';
					M($tb)->where("id", $val['prom_id'])->setDec('buy_num', $val['goods_num']);
					M($tb)->where("id", $val['prom_id'])->setDec('order_num',1);
				}
			}
		}
	}
}
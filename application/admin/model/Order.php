<?php

namespace app\admin\model;
use think\Model;
use think\Db;
use traits\model\SoftDelete;

class Order extends Model
{
	use SoftDelete;

	protected static $deleteTime = 'delete_time';

	//查询所有订单
	public function info()
	{
		$arr = [];
		$list = Order::all();
		$count = count($list);
		$arr = [$list,$count];
		return $arr;
	}
	//查询待发货
	public function back_order()
	{
		$arr = [];
		$list = Order::all(['back_orders'=>0]);
		$count = count($list);
		$arr = [$list,$count];
		return $arr;
	}
	//处理待发货
	public function doback_order($data)
	{
		$time = time();
		$sel = Db::name('order')->where('order_id',$data['order_id'])->update([
			'back_orders'=>1,
			'express'=>$data['express'],
			'express_number' => $data['express_number'],
			'order_time' => $time
			]);

		return $sel;
	}
	//查找已发货
	public function select_order()
	{
		$arr = [];
		$list = Order::all(['back_orders'=>1]);
		$select = Db::name('order')->where('back_orders',1)->select();
		$Vcount = count($select);
		$money = 0;
		foreach($select as $value){
			$money += $value['total_price'];
		}
		$count = count($list);
		//不带软删除已发货  总数 ，已发货金额 已发货数量 , 所有已发货信息
		$arr = [$list,$count,$money,$Vcount,$select];
		return $arr;
	}
	//查找已收货
	public function overOrder()
	{
		$arr = [];
		$list = Order::all(['back_orders'=>2]);
		$select = Db::name('order')->where('back_orders',2)->select();
		$money = 0;
		foreach($select as $value){
			$money += $value['total_price'];
		}
		$total = count($select);
		$count = count($list);
		$arr = [$list,$count,$money,$total,$select];
		return $arr;
	}

	//删除待发货订单
	public function order_delete($data)
	{
		$result = self::destroy($data['order_id']);
		return $result;
	}
	//还原已发货为待发货
	public function order_back($data)
	{
		$result = Db::name('order')->where('order_id',$data['order_id'])->update([
				'back_orders' => 0
			]);
		return $result;
	}
	//还原已收货为已发货
	public function over_back($data)
	{
		$result = Db::name('order')->where('order_id',$data['order_id'])->update([
				'back_orders' => 1
			]);
		return $result;
	}
	//删除已发货订单 已收货订单
	public function orderDelete($data)
	{
		$result = self::destroy($data['order_id']);
		return $result;
	}
	//回收站 获取软删除数据
	public function back_goods()
	{
		$result = self::onlyTrashed()->select();
		return $result;
	}
	//还原回收站
	public function goods_back($data)
	{
		$result = Db::name('order')->where('order_id',$data['order_id'])->update([
				'delete_time' => null
			]);
		return $result;
	}

	//删除回收站
	public function delback($data)
	{
		return Db('order')->delete($data['order_id']);
	}

	//删除评论
	public function del_notice($data)
	{
		$result = Db::name('order')->where('order_id',$data['order_id'])->update([
				'order_msg' => null
			]);
		return $result;
	}
}



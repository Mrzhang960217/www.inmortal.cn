<?php

namespace app\index\model;
use think\Model;
use think\Db;

class Indent extends Model
{
	// 查询所有订单
	public function deliver($uid)
	{
		return Db::name('order')->where(['user_id' => $uid])->select();
	}

	// 查询未发货的订单
	public function notSed($uid)
	{
		return Db::name('order')->where(['user_id' => $uid, 'back_orders' => 0])->select();
	}

	// 查询已发货的订单
	public function hasSed($uid)
	{
		return Db::name('order')->where(['user_id' => $uid, 'back_orders' => 1])->select();
	}

	// 查询已发货的订单
	public function yetSed($uid)
	{
		return Db::name('order')->where(['user_id' => $uid, 'back_orders' => 2])->select();
	}

	// 处理是否收货
	public function doYet($oid)
	{
		$data['back_orders'] = 2;
		return Db::name('order')->where('order_id', $oid)->update($data);
	}

	// 处理订单评论
	public function msg($args)
	{
		$oid = $args['oid'];
		$data['order_msg'] = $args['msg'];
		$db = Db::name('order')->where('order_id', $oid)->update($data);

		if ($db) {
			echo json_encode(['status' => 1, 'msg' => '评论成功']);
			die();
		} else {
			echo json_encode(['status' => 0, 'msg' => '评论失败']);
			die();
		}
	}

	// 处理订单搜索
	public function seek($args)
	{
		$data = $args['keywords'];
		return Db::name('order')->where('express_number|goods_name','like',"%$data%")->select();
	}
}
<?php

namespace app\buy\model;
use think\Model;
use think\Db;

class Quecar extends Model
{
	// 查询用户的购物车
	public function usercar($args)
	{
		return Db::name('car')->where('uid', $args)->select();
	}

	public function pri($args)
	{
		
		$db = Db::name('car')->where('car_id', $args['cid'])->select();

		echo json_encode(['goods' => $db]);
		die();
	}

	public function pul($args)
	{
		$id = $args['cid'];
		$data['goods_num'] = $args['number'];
		$data['total_price'] = $args['totalPrice'];
		Db::name('car')->where('car_id', $id)->update($data);

		$db = Db::name('car')->where('car_id', $id)->select();
		echo json_encode(['goodsPul' => $db]);
		die();
	}

	// 处理删除购物车中的商品
	public function del($args)
	{
		return Db::name('car')->delete($args['cid']);
	}

	// 处理添加订单
	public function add($args)
	{
		// 查询用户信息
		$user = Db::name('user')->where('user_id', $args['uid'])->select();

		foreach ($user as $value) {
			$data['user_id'] = $args['uid'];
			$data['user_name'] = $value['username'];
			$data['user_phone'] = $value['user_phone'];
			$data['address'] = $value['address'];
			// 用户账号余额
			$currentBalance = $value['current_balance'];
		}
		
		// 查询购物车信息
		$car = Db::name('car')->where('car_id', $args['cid'])->select();

		foreach ($car as $value) {
			$goodsname = $value['gname'].' '.$value['vname'].' '.$value['cname'];
			$data['goods_name'] = $goodsname;
			$data['goods_number'] = $value['goods_num'];
			$data['unit_price'] = $value['unit_price'];
			$data['total_price'] = $value['total_price'];
			$data['goods_pic'] = $value['car_pic'];
			$gid = $value['gid'];
		}

		$data['create_time'] = time();

		// 生成订单
		if ($currentBalance < $data['total_price']) {
			echo json_encode(['status' => 0, 'msg' => '支付失败，您的余额不足']);
			die();
		} else {
			$order = Db::name('order')->insert($data);
		
			if ($order) {
				// 支付成功后 修改用户账余额
				$balance['current_balance'] = $currentBalance - $data['total_price'];
				Db::name('user')->where('user_id', $args['uid'])->update($balance);

				// 支付成功后 修改对应的商品总数
				$db = Db::name('goods')->where('id', $gid)->select();
				foreach ($db as $val) {
					$newNum['number'] = $val['number'] - $data['goods_number'];
				}

				Db::name('goods')->where('id', $gid)->update($newNum);

				// 支付成功后 删除购物车内对应的商品
				Db::name('car')->where('car_id', $args['cid'])->delete();
				echo json_encode(['status' => 1, 'msg' => '支付成功、查看订单']);
				die();
			} else {
				echo json_encode(['status' => 0, 'msg' => '支付失败']);
				die();
			}
		}
	}
}
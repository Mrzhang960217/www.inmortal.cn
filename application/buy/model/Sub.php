<?php

namespace app\buy\model;
use think\Model;
use think\Db;

class Sub extends Model
{
	public function car($args)
	{
		$data['uid'] = $args['userId'];
		$data['gid'] = $args['goodsId'];
		$data['gname'] = $args['goodsName'];
		$data['vid'] = $args['goodsVerid'];
		$data['vname'] = $args['goodsVersion'];
		$data['cid'] = $args['goodsColid'];
		$data['cname'] = $args['goodsColor'];
		$data['car_pic'] = $args['goodsColimg'];
		$data['unit_price'] = $args['goodsPrice'];
		$data['total_price'] = $args['goodsPrice'];
		$data['create_time'] = time();
		$db = Db::name('car')->insert($data);

		if ($db) {
			echo json_encode(['status' => 1, 'msg' => '添加成功,去购物车结算']);
		} else {
			echo json_encode(['status' => 0, 'msg' => '添加失败']);
		}
	}
}
<?php

namespace app\index\model;
use think\Model;
use think\Db;

class Shopping extends Model
{
	// 查询用户购物车的商品
	public function shop($uid)
	{
		return Db::name('car')->where(['uid' => $uid])->select();
	}
}
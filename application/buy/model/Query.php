<?php

namespace app\buy\model;
use think\Model;
use think\Db;

class Query extends Model
{
	public function goodsVer($args)
	{
		return Db::name('version')->where('gid', $args)->select();
	}

	public function goodsCol($args)
	{
		return Db::name('color')->where('vid', $args)->select();
	}

	public function goodsDetail($args)
	{
		return Db::name('goods')->where('id', $args)->select();
	}

	// 查询用户收货地址
	public function address($uid)
	{
		return Db::name('user')->where('user_id', $uid)->field('address')->find();
	}
}
<?php

namespace app\index\model;
use think\Model;
use think\Db;

class Query extends Model
{
	public function goodsDetail($args)
	{
		return Db::name('goods')->where('id', $args)->select();
	}
}
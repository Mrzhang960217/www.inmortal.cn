<?php

namespace app\index\model;
use think\Model;
use think\Db;

class Product extends Model
{
	public function proName()
	{
		return Db::name('product_type')->select();
	}

	public function goodsName()
	{
		return Db::name('goods')->select();
	}
}
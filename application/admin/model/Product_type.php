<?php

namespace app\admin\model;
use think\Model;
use think\Db;
use traits\model\SoftDelete;

class Product_type extends Model
{
	use SoftDelete;
	//禁用产品类型（软删除）
	public function soft_del($data)
	{
		$result = self::destroy($data['id']);
		return $result;
	} 

	//删除商品类型
	public function type_delete($data)
	{
		$result = Db('product_type')->delete($data['id']);
		return $result;
	}
}
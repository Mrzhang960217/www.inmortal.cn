<?php

namespace app\admin\model;
use think\Model;
use think\Db;
use traits\model\SoftDelete;

class Version extends Model
{
	use SoftDelete;

	protected static $deleteTime = 'delete_time';



	public function products_list()
	{
		$arr = [];
		$list = self::name('version')->select();
		$count = count($list);
		$arr = [$list,$count];
		return $arr;
	}
	//产品属性
	public function attribute()
	{
		return Db::name('attrbute')->select();
	}
	//手机类型
	public function phoneType()
	{
		return Db::name('product_type')->select();
	}
	//添加商品
	public function add($post,$pic)
	{	
		$time = time();
		$data = [];
		foreach ($pic as $key => $value) {
			$data["$key"] = $value;
			$data2[$key] = $value;
		}

		//插入 goods 表
		$data = [
			'name' => $post['name'],
			'pid' => $post['type_id'],
			'number' => $post['number'],
			'goods_pic0' => $pic['goods_pic0'],
			'create_time' => $time,
			'goods_as' => $post['goods_as'],
			'price_start' => $post['price_start'],
		];

		$result = Db::name('goods')->insertGetId($data);
		
		//插入version表
		// version_name original_price version_price(现价) editorValue gid(对应产品id)
		$data1 = [
			'version_name' => $post['version_name'],
			'original_price' => $post['original_price'],
			'version_price' => $post['version_price'],
			'introduction' => $post['introduction'],
			'editorValue' => $post['editorValue'],
			'create_time' => $time,
			'version_pic' => $pic['goods_pic0'],
			'gid' => $result
		];

		$result1 = Db::name('version')->insertGetId($data1);
		
		//插入颜色
		//goods_pic1 color_name vid=version_id(版本id)
		$data2 = [
			'color_name' => $post['color_name'],
			'create_time' => $time,
			'goods_pic1' => $pic['goods_pic1'],
			'vid' => $result1
		];
		$result2 = Db::name('color')->insertGetId($data2);
		if($result2){
			return true;
		} else {
			return false;

		}
	}

	//删除商品
	public function goods_delete($data)
	{		
		$result = self::destroy($data['version_id']);
		return $result;
	}

	//商品状态
	public function goods_status($data)
	{
		if($data['status']){
			$sel = Db::name('version')->where('version_id',$data['version_id'])->update(['status'=>0]);
			if($sel){
				return json_encode(['status'=>0,'msg'=>'下架成功']);
			} else {
				return json_encode(['status'=>0,'msg'=>'下架失败']);

			}
			
		} else {
			$sel = Db::name('version')->where('version_id',$data['version_id'])->update(['status'=>1]);
			if($sel){
				return json_encode(['status'=>1,'msg'=>'上架成功']);
			} else {
				return json_encode(['status'=>1,'msg'=>'上架失败']);

			}
			
		}
	}

	//上下架数量
	public function upDown()
	{
		$arr = [];
		$list0 = Version::all(['status'=>0]);
		$list1 = Version::all(['status'=>1]);
		$count0 = count($list0);
		$count1 = count($list1);
		$arr = [$count0,$count1];
		return $arr;
	}
	//产品类型
	public function product_type()
	{
		return Db::name('product_type')->select();
	}
	
	//添加商品类型
	public function addType($data)
	{
		$data = [
			'product_name' => $data['product_name'],
			'text' => $data['text']
		];
		$result = Db::name('product_type')->insert($data);
		if($result){
			return true;
		} else {
			return falsa;
		}
	}

	//编辑商品
	public function goods_select($data)
	{
		$result = Db::name('version')->where('version_id',$data)->find();
		return $result;
	}
	
	public function goodsup($data)
	{
		return Db('version')->where('version_id',$data['version_id'])->update([
			'introduction' => $data['introduction'],
			'original_price' => $data['original_price'],
			'version_price' => $data['version_price'],
			]);
		
	}
	public function goodsup1($pic,$data)
	{
		return Db('version')->where('version_id',$data['version_id'])->update([
			'introduction' => $data['introduction'],
			'original_price' => $data['original_price'],
			'version_price' => $data['version_price'],
			'version_pic' => $pic['version_pic']
			]);
		
	}
	//回收站 查找软删除商品
	public function goodsback()
	{
		$result = self::onlyTrashed()->select();
		return $result;
	} 

	//还原回收站 商品
	public function proback($data)
	{
		$result = Db::name('version')->where('version_id',$data['version_id'])->update([
				'delete_time' => null
			]);
		return $result;
	}
	//删除回收站 商品
	public function delprouct($data)
	{
		return Db('version')->delete($data['version_id']);
	}
}



<?php

namespace app\buy\controller;
use think\Controller;
use app\buy\model\Query;
use app\buy\model\Color;
use app\buy\model\Version;

class Buyphone extends Controller
{
	public function buyphone(Query $query)
	{
		$goodsId = input('param.goodsId');
		$version = $query->goodsVer($goodsId);
		$versionId = $version[0]['version_id'];
		$verId = [];
		foreach ($version as $id) {
			$verId[] = $id['version_id'];
		}

		$color = $query->goodsCol($versionId);
		$goods = $query->goodsDetail($goodsId)[0];
		$userId = session('user_id');
		// 判断用户是否有收货地址
		$address = $query->address($userId);
		
		$username = session('username');
		
		$this->assign([
				'goodsVer' => $version,
				'verId' => $verId,
				'goodsCol' => $color,
				'goods' => $goods,
				'userId' => $userId,
				'username' => $username,
				'picture' => $color[0]['goods_pic1'],
				'address' => $address
			]);
		return $this->fetch();
	}

	public function checkCol(Color $color, Query $query)
	{
		return $color->verCol($_REQUEST);
	}

	public function selCol(Color $color, Query $query)
	{
		return $color->col($_REQUEST);
	}

	public function checkVer(Version $version, Query $query)
	{
		return $version->ver($_REQUEST);
	}
}
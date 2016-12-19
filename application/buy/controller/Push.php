<?php

namespace app\buy\controller;
use think\Controller;
use app\buy\model\Sub;
use app\buy\model\Quecar;

class Push extends Controller
{
	public function pushcar(Sub $sub)
	{
		return $sub->car($_REQUEST);

	}

	public function shopcar(Quecar $quecar)
	{
		$userId = session('user_id');
		$username = session('username');

		$usercar = $quecar->usercar($userId);
		$goodsNum = count($usercar);

		$this->assign([
				'userId' => $userId,
				'username' => $username,
				'car' => $usercar,
				'usercar' => $usercar,
				'goodsNum' => $goodsNum
			]);
		return $this->fetch();
	}

	// 处理购物车的价钱
	public function price(Quecar $quecar)
	{
		// dump($_REQUEST);
		return $quecar->pri($_REQUEST);
	}

	// 处理购物车商品数量增加
	public function plus(Quecar $quecar)
	{
		// dump($_REQUEST);
		return $quecar->pul($_REQUEST);
	}

	// 删除购物车商品
	public function dele(Quecar $quecar)
	{
		return $quecar->del($_REQUEST);
	}

	// 添加订单
	public function order(Quecar $quecar)
	{
		// dump($_REQUEST);
		return $quecar->add($_REQUEST);
	}
}
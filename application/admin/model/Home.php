<?php

namespace app\admin\model;
use think\Model;
use think\Db;

class Home extends Model
{
	public function info()
	{
		$userInfo = Db::name('user')->select();
		return count($userInfo);
	}
}



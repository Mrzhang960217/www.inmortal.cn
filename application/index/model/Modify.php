<?php

namespace app\index\model;
use think\Model;
use think\Db;

class Modify extends Model
{
	public function mod($args)
	{
		$password = md5($args['password']);
		$user_phone = $args['user_phone'];
		$db = Db::name('user')->where('user_phone', $user_phone)->update(['password' => $password]);

		if ($db) {
			echo json_encode(['status' => 1, 'msg' => '修改成功']);
		} else {
			echo json_encode(['status' => 0, 'msg' => '修改失败']);
		}
	}
}
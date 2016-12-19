<?php

namespace app\register\model;
use think\Model;
use think\Db;

class Checkuser extends Model
{
	public function check($args)
	{
		$username = $args['username'];
		
		$sql = Db::name('user')->field('user_phone')->select();

		$data = [];
		foreach ($sql as $user) {
			array_push($data, $user['user_phone']);
		}
		
		if (in_array($username, $data)) {
			echo json_encode(['status' => 1, 'msg' => '该小米账号已存在']);
			die();
		}
	}
}
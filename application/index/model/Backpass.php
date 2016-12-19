<?php

namespace app\index\model;
use think\Model;
use think\Db;

class Backpass extends Model
{
	public function checkusername($args)
	{
		$username = $args['username'];
		
		$sql = Db::name('user')->field('user_phone')->select();

		$data = [];
		foreach ($sql as $user) {
			array_push($data, $user['user_phone']);
		}
		
		if (in_array($username, $data)) {
			echo json_encode(['status' => 1, 'msg' => '存在']);
			die();
		} else {
			echo json_encode(['status' => 0, 'msg' => '该小米账号不存在', 'classname' => '.username']);
			die();
		}
	}

	public function back($args)
	{
		$user_phone = $args['user_phone'];
		$email = $args['email'];

		$db = Db::name('user')->where(['user_phone' => $user_phone, 'user_email' => $email])->select()[0];
		
		if ($db) {
			echo json_encode(['status' => 1, 'msg' => '验证成功', 'user_phone' => $db['user_phone']]);
		} else {
			echo json_encode(['status' => 0, 'msg' => '验证失败，邮箱不正确']);
		}
	}
}
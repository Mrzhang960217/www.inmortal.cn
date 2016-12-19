<?php

namespace app\register\model;
use think\Model;
use think\Db;

class Register extends Model
{
	public function add($args)
	{
		$data = [];
		$data['user_phone'] = $args['user_phone'];
		$data['user_email'] = $args['email'];
		$data['password'] = md5($args['password']);
		$data['create_time'] = time();
		$data['username'] = mt_rand();

		$result = Db::name('user')->field('user_phone')->select();

		foreach ($result as  $value) {

			if ($data['user_phone'] == $value['user_phone']) {
				echo json_encode(['status' => 0, 'msg' => '该小米账户已存在', 'classname' => '.user_phone']);
				die();
			}
		}


		$db = Db::name('user')->insert($data);

		if ($db) {
    		echo json_encode(['status' => 1, 'msg' => '注册成功', 'classname' => '']);
    		die();
    	}
	}
}
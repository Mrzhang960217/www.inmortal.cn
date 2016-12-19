<?php

namespace app\index\model;
use think\Model;
use think\DB;

class checkInfo extends Model
{
	public function editInfo($args)
	{
		$data['username'] = $args['username'];
		$data['birthday'] = $args['userbirthday'];
		$data['sex'] = $args['usersex'];
		// $data['user_phone'] = $args['userphone'];
		$data['user_email'] = $args['useremail'];
		$data['address'] = $args['address'];
		
		$db = Db::name('user')->where('user_id', session('user_id'))->update($data);

		if ($db) {
			echo json_encode(['status' => 1, 'msg' => '个人信息修改成功']);
		} else {
			echo json_encode(['status' => 0, 'msg' => '个人信息修改失败']);
		}

		$db = Db::name('user')->where('user_id', session('user_id'))->select();

		// 更新数据
		// session('user_phone', $db[0]['user_phone']);
		session('username', $db[0]['username']);
		session('user_id', $db[0]['user_id']);
		session('sex', $db[0]['sex']);
		session('head_portrait', $db[0]['head_portrait']);
		session('user_email', $db[0]['user_email']);
		session('birthday', $db[0]['birthday']);
		session('address', $db[0]['address']);
	}
}
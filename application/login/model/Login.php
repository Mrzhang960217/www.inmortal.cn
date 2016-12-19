<?php

namespace app\login\model;
use think\Model;
use think\Db;
// use think\Session;

class Login extends Model
{
	public function checkUsername($args)
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

	public function checkLogin($args)
	{
		$data['username'] = $args['username'];
		$data['password'] = md5($args['password']);
		
		// $sql = Db::name('user')->select();
		$db = Db::name('user')->where(['user_phone' => $data['username'], 'password' => $data['password']])->select();

		// $arr = [];
		// $arrs = [];
		// foreach ($sql as $user) {
		// 	$arr['username'] = $user['username'];
		// 	$arr['password'] = $user['password'];
		// 	array_push($arrs, $arr);
		// }

		if ($db) {

			// 保存数据到session中
			// session_start();
			// $_SESSION['user_id'] = $db[0]['user_id'];
			// $_SESSION['username'] = $db[0]['username'];
			// Session::set('username', $db[0]['username']);

			session('user_phone', $db[0]['user_phone']);
			session('username', $db[0]['username']);
			session('user_id', $db[0]['user_id']);
			session('sex', $db[0]['sex']);
			session('head_portrait', $db[0]['head_portrait']);
			session('user_email', $db[0]['user_email']);
			session('birthday', $db[0]['birthday']);
			session('address', $db[0]['address']);

			// 更新登录时间
			$time = time();
			$id = $db[0]['user_id'];

			Db::name('user')->where('user_id', $id)->update(['last_time' => $time]);

			echo json_encode(['status' => 1, 'msg' => '登录成功']);
			die();
		} else {
			echo json_encode(['status' => 0, 'msg' => '密码错误、请重新输入', 'classname' => '.password']);
			die();
		}
		
		// if (in_array($data, $arrs)) {
		// 	dump($arrs);
		// 	$_SESSION['username'] = $data['username'];
		// 	$_SESSION['password'] = $data['password'];
		// 	dump($_SESSION);
		// 	exit();
		// 	echo json_encode(['status' => 1, 'msg' => '登录成功']);
		// 	die();
		// } else {
		// 	echo json_encode(['status' => 0, 'msg' => '密码错误、请重新输入', 'classname' => '.password']);
		// 	die();
		// }	
	}
}
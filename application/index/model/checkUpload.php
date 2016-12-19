<?php

namespace app\index\model;
use think\Model;
use think\Db;

class checkUpload extends Model
{
	public function checkUp($args)
	{
		$data['head_portrait'] = $args;
		$data['user_id'] = session('user_id');
		Db::name('user')->update($data);
		$db = Db::name('user')->field('head_portrait')->where('user_id', $data['user_id'])->select();
		// dump($db['head_portrait']);
		session('head_portrait', $db[0]['head_portrait']);
	}
}
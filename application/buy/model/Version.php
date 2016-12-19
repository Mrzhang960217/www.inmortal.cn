<?php

namespace app\buy\model;
use think\Model;
use think\Db;

class Version extends Model
{
	public function ver($data)
	{
		$db = Db::name('Version')->where('Version_id', $data['verId'])->select();
		echo json_encode(['version' => $db]);
	}
}
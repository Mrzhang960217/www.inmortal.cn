<?php

namespace app\buy\model;
use think\Model;
use think\Db;

class Color extends Model
{
	public function verCol($data)
	{
		$db = Db::name('Color')->where('vid', $data['verId'])->select();
		echo json_encode(['color' => $db]);
	}

	public function col($data)
	{
		$db = Db::name('Color')->where('color_id', $data['verId'])->select();
		echo json_encode(['color' => $db]);
	}
}
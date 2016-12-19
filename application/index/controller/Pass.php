<?php

namespace app\index\controller;
use think\Controller;
use app\index\model\Modify;

class Pass extends Controller
{
	// 修改密码
	public function doModify(Modify $modify)
	{
		return $modify->mod($_REQUEST);
	}
}
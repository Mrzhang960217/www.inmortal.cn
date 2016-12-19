<?php

namespace app\register\controller;
use think\Controller;
use app\register\model\Register;
use app\register\model\Checkuser;

class Index extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    public function doRegister(Register $register)
    {
    	if(!captcha_check($_REQUEST['verify'])){
			echo json_encode(['status' => 0, 'msg' => '验证码输入错误', 'classname' => '.verify']);
			die();
		};
    	return $register->add($_REQUEST);
    }

    public function doCheck(Checkuser $checkuser)
    {
        return $checkuser->check($_REQUEST);
    }

}
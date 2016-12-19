<?php

namespace app\login\controller;
use think\Controller;
use think\view;
use app\login\model\Login;

class Index extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    public function doLogin(Login $login)
    {
    	return $login->checkLogin($_REQUEST);
    }

    public function checkUser(Login $login)
    {
    	return $login->checkUsername($_REQUEST);
    }
}
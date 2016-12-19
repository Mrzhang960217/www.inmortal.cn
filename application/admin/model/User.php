<?php

namespace app\admin\model;
use think\Model;
use think\Db;

class User extends Model
{
	public function userInfo()
	{
		return Db::name('user')->select();
		
	}
	public function user_info($data)
	{
		return Db::name('user')->where('username',$data['username'])->find();
	}

	//删除用户
	public function userDel($data)
	{
		return Db('user')->where('user_id',$data['user_id'])->delete();
	}

	//设置管理员
	public function setuser($data)
	{
		if(!empty($data['title'])){
			return Db('user')->where('user_id',$data['user_id'])->update(['permissions' => 0]);
		} else {
			return Db('user')->where('user_id',$data['user_id'])->update(['permissions' => 1]);
		}
		
	}
}



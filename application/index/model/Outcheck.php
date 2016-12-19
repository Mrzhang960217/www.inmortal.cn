<?php

namespace app\index\model;
use think\Model;

class Outcheck extends Model
{
	public function checkout()
	{
		return session(null);
	}
}
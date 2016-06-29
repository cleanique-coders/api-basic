<?php 

class Token extends ActiveRecord\Model
{
	public static function generate($value)
	{
		return [
			'token' => md5($value) . '.' . time(),
			'expired_at' => date("Y-m-d", strtotime("+1 week"))
		];
	}
}
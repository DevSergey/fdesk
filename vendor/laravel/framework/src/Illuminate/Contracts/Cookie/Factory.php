<?php namespace Illuminate\Contracts\Cookie;
interface Factory {
	public function make($name, $value, $minutes = 0, $path = null, $domain = null, $secure = false, $httpOnly = true);
	public function forever($name, $value, $path = null, $domain = null, $secure = false, $httpOnly = true);
	public function forget($name, $path = null, $domain = null);
}
<?php
class Zend_View_Helper_BaseUrl extends Zend_View_Helper_Abstract
{
	public function baseUrl()
	{
		$protocol = isset( $_SERVER['HTTPS'] ) ? 'https' : 'http';
		$server = $_SERVER['HTTP_HOST'];
		//$port = $_SERVER['SERVER_PORT'] != 80 ? ":{$_SERVER['SERVER_PORT']}" : '';
		$path = rtrim( dirname( $_SERVER['SCRIPT_NAME'] ), '/' );
		
		return "$protocol://$server$path";
	}
}
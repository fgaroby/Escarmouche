<?php
abstract class Application_Model_AbstractTest extends PHPUnit_Framework_TestCase
{
	protected $_options;
	
	
	public function tearDown()
	{
		$this->_options = null;
	}
}
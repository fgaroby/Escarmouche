<?php
require_once( 'Zend/Db/Table/Abstract.php' );

class Application_Model_Db_Table_Task extends Zend_Db_Table_Abstract
{
	/**
	 *
	 * Table name
	 * @var string
	 */
	protected $_name = 'task';
	
	
	protected $_primary = 'id';
}
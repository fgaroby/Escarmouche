<?php
require_once( 'Zend/Db/Table/Abstract.php' );

class Application_Model_Db_Table_User extends Zend_Db_Table_Abstract
{
	/**
	 *
	 * Table name
	 * @var string
	 */
	protected $_name = 'user';
	
	
	protected $_primary = 'id';
}
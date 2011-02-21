<?php
require_once( 'Zend/Db/Table/Abstract.php' );

class Application_Model_Db_Table_Status extends Zend_Db_Table_Abstract
{
	/**
	 *
	 * Table name
	 * @var string
	 */
	protected $_name = 'status';
	
	
	protected $_primary = 'id';
	
	
	protected $_referenceMap = array(	'Story'		=> array(	'columns'		=>'id',
																'refTableClass'	=> 'Application_Model_Db_Table_Story',
																'refColumns'	=> array( 'status_id' ) ),
										'Feature'	=> array(	'columns'		=>'id',
																'refTableClass'	=> 'Application_Model_Db_Table_Feature',
																'refColumns'	=> array( 'status_id' ) ) );
}
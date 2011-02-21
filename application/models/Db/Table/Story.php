<?php
class Application_Model_Db_Table_Story extends Zend_Db_Table_Abstract
{
	/**
	 *
	 * Table name
	 * @var string
	 */
	protected $_name = 'story';
	
	
	protected $_primary = 'id';
	
	
	protected $_referenceMap = array(	'Status'	=> array(	'columns'		=>'status_id',
																'refTableClass'	=> 'Application_Model_Db_Table_Status',
																'refColumns'	=> array( 'id' ) ),
										'Sprint'	=> array(	'columns'		=>'sprint_id',
																'refTableClass'	=> 'Application_Model_Db_Table_Sprint',
																'refColumns'	=> array( 'id' ) ),
										'Feature'	=> array(	'columns'		=>'feature_id',
																'refTableClass'	=> 'Application_Model_Db_Table_Feature',
																'refColumns'	=> array( 'id' ) ) );
}
<?php
require_once( 'Zend/Db/Table/Abstract.php' );

class Application_Model_Db_Table_Feature extends Zend_Db_Table_Abstract
{
	/**
	 *
	 * Table name
	 * @var string
	 */
	protected $_name = 'feature';
	
	
	protected $_primary = 'id';
	
	
	protected $_referenceMap = array(	'Sprint'	=> array(	'columns'		=>'sprint_id',
																'refTableClass'	=> 'Application_Model_Db_Table_Sprint',
																'refColumns'	=> array( 'id' ) ),
										'Story'		=> array(	'columns'		=>'story_id',
																'refTableClass'	=> 'Application_Model_Db_Table_Story',
																'refColumns'	=> array( 'id' ) ),
										'Release'	=> array(	'columns'		=>'release_id',
																'refTableClass'	=> 'Application_Model_Db_Table_Release',
																'refColumns'	=> array( 'id' ) ) );
}
<?php
class Application_Model_Db_Table_Story extends Escarmouche_Db_Table_Abstract
{
	/**
	 *
	 * Table name
	 * @var string
	 */
	protected $_name = 'story';
	
	
	protected $_referenceMap = array(	'Status'	=> array(	'columns'		=>'status',
																'refTableClass'	=> 'Application_Model_Db_Table_Status',
																'refColumns'	=> array( 'id' ) ),
										'Sprint'	=> array(	'columns'		=>'sprint',
																'refTableClass'	=> 'Application_Model_Db_Table_Sprint',
																'refColumns'	=> array( 'id' ) ),
										'Feature'	=> array(	'columns'		=>'feature',
																'refTableClass'	=> 'Application_Model_Db_Table_Feature',
																'refColumns'	=> array( 'id' ) ) );
}
<?php
class Application_Model_Db_Table_Feature extends Escarmouche_Db_Table_Abstract
{
	/**
	 *
	 * Table name
	 * @var string
	 */
	protected $_name = 'feature';
	
	
	protected $_referenceMap = array(	'Sprint'	=> array(	'columns'		=>'sprint',
																'refTableClass'	=> 'Application_Model_Db_Table_Sprint',
																'refColumns'	=> array( 'id' ) ),
										'Story'		=> array(	'columns'		=>'story',
																'refTableClass'	=> 'Application_Model_Db_Table_Story',
																'refColumns'	=> array( 'id' ) ),
										'Release'	=> array(	'columns'		=>'release',
																'refTableClass'	=> 'Application_Model_Db_Table_Release',
																'refColumns'	=> array( 'id' ) ) );
}
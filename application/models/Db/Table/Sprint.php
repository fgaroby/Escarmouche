<?php
class Application_Model_Db_Table_Sprint extends Escarmouche_Db_Table_Abstract
{
	/**
	 *
	 * Table name
	 * @var string
	 */
	protected $_name = 'sprint';
	
	
	protected $_referenceMap = array(	'Release'	=> array(	'columns'		=> 'release',
																'refTableClass'	=> 'Application_Model_Db_Table_Release',
																'refColumns'	=> array( 'id' ) ),
	 									'Story'		=> array(	'columns'		=> 'story',
																'refTableClass'	=> 'Application_Model_Db_Table_Story',
																'refColumns'	=> array( 'id' ) ) );
}
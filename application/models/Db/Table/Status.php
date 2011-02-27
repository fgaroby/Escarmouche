<?php
class Application_Model_Db_Table_Status extends Escarmouche_Db_Table_Abstract
{
	/**
	 *
	 * Table name
	 * @var string
	 */
	protected $_name = 'status';
	
	
	protected $_referenceMap = array(	'Story'		=> array(	'columns'		=>'id',
																'refTableClass'	=> 'Application_Model_Db_Table_Story',
																'refColumns'	=> array( 'status' ) ),
										'Feature'	=> array(	'columns'		=>'id',
																'refTableClass'	=> 'Application_Model_Db_Table_Feature',
																'refColumns'	=> array( 'status' ) ) );
}
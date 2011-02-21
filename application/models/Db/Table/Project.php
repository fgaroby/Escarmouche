<?php
class Application_Model_Db_Table_Project extends Zend_Db_Table_Abstract
{
	/**
	 *
	 * Table name
	 * @var string
	 */
	protected $_name = 'project';
	
	
	protected $_referenceMap = array(	'Creator'		=> array(	'columns'		=> 'creator_id',
																	'refTableClass'	=> 'User',
																	'refColumns'	=> array( 'id' ) ),
										'ProductOwner'	=> array(	'columns'		=> 'productOwner_id',
																	'refTableClass'	=> 'User',
																	'refColumns'	=> array( 'id' ) ),
										'ScrumMaster'	=> array(	'columns'		=> 'scrumMaster_id',
																	'refTableClass'	=> 'User',
																	'refColumns'	=> array( 'id' ) ) );
}
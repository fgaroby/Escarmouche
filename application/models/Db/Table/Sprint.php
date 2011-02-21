<?php
class Application_Model_Db_Table_Sprint extends Zend_Db_Table_Abstract
{
	/**
	 *
	 * Table name
	 * @var string
	 */
	protected $_name = 'sprint';
	
	
	protected $_primary = 'id';
	
	
	protected $_referenceMap = array(	'Release'	=> array(	'columns'		=> 'release_id',
																'refTableClass'	=> 'Release',
																'refColumns'	=> array( 'id' ) ),
	 									'Story'		=> array(	'columns'		=> 'story_id',
																'refTableClass'	=> 'Story',
																'refColumns'	=> array( 'id' ) ) );
}
<?php
class Application_Model_Db_Table_SprintRelease extends Escarmouche_Db_Table_Abstract
{
	/**
	 *
	 * Table name
	 * @var string
	 */
	protected $_name = 'sprint_release';
	
	protected $_primary = array( 'sprint', 'task' );
	
	
	protected $_referenceMap = array(	'Task'	=> array(	'columns'		=> array( 'task' ),
															'refTableClass'	=> 'Application_Model_Db_Table_Task',
															'refColumns'	=> array( 'id' ) ),
										'Story'	=> array(	'columns'		=> array( 'story' ),
															'refTableClass'	=> 'Application_Model_Db_Table_Story',
															'refColumns'	=> array( 'id' ) ) );
}
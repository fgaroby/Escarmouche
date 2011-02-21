<?php
class Application_Model_Db_Table_StoryTask extends Zend_Db_Table_Abstract
{
	/**
	 *
	 * Table name
	 * @var string
	 */
	protected $_name = 'story_task';
	
	protected $_primary = array( 'story_id', 'task_id' );
	
	
	protected $_referenceMap = array(	'Task'	=> array(	'columns'		=> array( 'task_id' ),
															'refTableClass'	=> 'Application_Model_Db_Table_Task',
															'refColumns'	=> array( 'id' ) ),
										'Story'	=> array(	'columns'		=> array( 'story_id' ),
															'refTableClass'	=> 'Application_Model_Db_Table_Story',
															'refColumns'	=> array( 'id' ) ) );
}
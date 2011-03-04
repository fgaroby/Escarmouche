<?php
/**
 *
 *
 * @author Francescu Garoby
 *
 */
class Application_Model_Story extends Application_Model_AbstractModel
{
	/**
	 *
	 * The status of the story.
	 * By default, equals <code>Application_Model_Status::SUGGESTED</code>
	 * @var int
	 */
	protected $_status;
	

	protected $_sprint = null;

	
	protected $_priority = 0;
	
	
	protected $_points = 0;
	
	
	protected $_feature = null;
	
	
	protected $_type = null;
	
	
	/**
	 *
	 * The list of <code>Task</code> that the story has.
	 * Cannot be <code>null</code>. Can be empty.
	 * @var array[Application_Model_Task]
	 */
	protected $_tasks = array();


	public function __construct( $options = array() )
	{
		$this->setStatus( Application_Model_Status::SUGGESTED );
		parent::__construct( $options );
	}


	/**
	 * 
	 * @param int $status the status of the story
	 * @throws InvalidArgumentException if $status is NaN or not a valid status
	 * TODO définir les status autorisés
	 */
	public function setStatus( $status )
	{
		if( !$status instanceof Application_Model_Status && !intval( $status, 10 ) )
			throw new InvalidArgumentException( "\$status' is 'NaN' !" );
		if( !Application_Model_Status::isValid( $status ) )
			throw new InvalidArgumentException( "'\$status' is not a valid status !" );
	
		$this->_status = $status;

		return $this;
	}


	/**
	 * @todo renvoyer un statut dépendant du statut des tasks, si le statut de la story est >= Application_Model_Status::WIP
	 * Enter description here ...
	 */
	public function getStatus()
	{
		if( $this->_status instanceof Application_Model_Status )
			$this->_status = Application_Model_StatusMapper::getInstance()->find( $this->_status );
		
		return $this->_status;
	}


	public function setSprint( $sprint = null )
	{
		$this->_sprint = $sprint;

		return $this;
	}


	public function getSprint()
	{
		if( is_int( $this->_sprint ) )
		{
			$sm = new Application_Model_SprintMapper();
			$this->_sprint = $sm->find( $this->_sprint );
		}
		
		return $this->_sprint;
	}
	
	
	/**
	 * Return the id of the sprint, or <code>null</code> if there's no sprint.
	 * @return the id of the sprint, or <code>null</code>.
	 */
	public function getSprintId()
	{
		if( $this->_sprint instanceof Application_Model_Sprint )
			return $this->_sprint->getId();
		else if( is_int( $this->_sprint ) )
			return $this->_sprint;
		else
			return null;
	}


	public function setPriority( $priority )
	{
		$this->_priority = $priority;
		
		return $this;
	}


	public function getPriority()
	{
		return $this->_priority;
	}
	
	
	/**
	 *
	 * @param array[Application_Model_Task] | Application_Model_Task $task the task (or array of tasks) we want to add to this story
	 * @throws InvalidArgumentException if $task is 'null' or empty
	 */
	public function addTask( $task )
	{
		if( $task === null || empty( $task ) )
			throw new InvalidArgumentException( "'\$task' cannot be 'null' or empty !" );

		if( is_array( $task ) )
			foreach( $task as $t )
				$this->_addTask( $t );
		else
			$this->_addTask( $task );

		return $this;
	}

	
	protected function _addTask( Application_Model_Task $task )
	{
		$index = array_search( $task, $this->_tasks );
		if( $index === false )
		{
			$this->_tasks[] = $task;
			$task->addStory( $this );
		}
		
		return $this;
	}
	

	/**
	 *
	 * @param array[Application_Model_Task] | Application_Model_Task $task the task (or array of tasks ) we want to remove to this story
	 * @throws InvalidArgumentException if $task is 'null' or empty
	 */
	public function removeTask( $task )
	{
		if( $task === null || empty( $task ) )
			throw new InvalidArgumentException( "'\$task' cannot be 'null' or empty !" );

		if( !is_array( $task ) )
			foreach( $task as $t )
				$this->_removeTask( $t );
		else
			$this->_removeTask( $task );

		return $this;
	}
	
	
	protected function _removeTask( Application_Model_Task $task )
	{
		$index = array_search( $task, $this->_tasks );
		if( $index !== false )
		{
			unset( $this->_tasks[$index] );
			$task->removeStory( $this );
		}
		
		return $this;
	}


	/**
	 *
	 * Enter description here ...
	 * @param int $index
	 * @return Application_Model_Task
	 * @throws InvalidArgumentException if $index is 'NaN'
	 * @throws OutOfRangeException if $index is negative or higher  than or equal to the size of the array
	 */
	public function getTask( $index )
	{
		if( !is_numeric( $index ) )
			throw new InvalidArgumentException( "'\$index' is NaN !" );
		if( $index < 0 || $index >= sizeof( $this->_tasks ) )
			throw new OutOfRangeException( "'\$index' cannot be negative, greater than or equal to the size of the array !" );

		return $this->_tasks[$index];
	}


	/**
	 *
	 * @return array[Application_Model_Task]
	 */
	public function getTasks()
	{
		return $this->_tasks;
	}
	
	
	public function setFeature( $feature = null )
	{
		if( $feature === 0 )
			$this->_feature = null;
		else
		{
			$this->_feature = $feature;
		}
		
		return $this;
	}
	
	
	public function getFeature()
	{
		if( is_int( $this->_feature ) )
		{
			$fm = new Application_Model_FeatureMapper();
			$this->_feature = $fm->find( $this->_feature );
		}
		
		return $this->_feature;
	}
	
	
	public function getFeatureId()
	{
		if( $this->_feature instanceof Application_Model_Feature )
			return $this->_feature->getId();
		else if( is_int( $this->_feature ) )
			return $this->_feature;
		else
			return null;
	}
	
	
	/**
	 * TODO proposer une couleur par défaut, si pas associée à une feature
	 * Returns the color associated
	 */
	public function getColor()
	{
		if( isset( $this->_feature ) )
		{
			if( is_int( $this->_feature ) )
			{
				$f = new Application_Model_FeatureMapper();
				$this->_feature = $f->find( $this->_feature );
			}
			
			if( $this->_feature instanceof Application_Model_Feature )
				return $this->_feature->getColor();
			else
				return Zend_Registry::get( 'config' )->color->border->default;
		}
		
		return null;
	}
	
	
	/**
	 * 
	 * Enter description here ...
	 * @param int $points
	 */
	public function setPoints( $points )
	{
		$this->_points = ( int ) $points;
			
		return $this;
	}
	
	
	public function getPoints()
	{
		return $this->_points;
	}
	
	
	public function setType( $type )
	{
		$this->_type = ( int ) $type;
	}
	
	
	protected function _getType()
	{
		$tm = new Application_Model_TypeMapper();
		$this->_type = $tm->find( $this->_type );
	}
	
	public function getType()
	{
		if( is_int( $this->_type ) )
			$this->_getType();
		
		return $this->_type;
	}
	
	
	public function getTypeId()
	{
		if( !isset( $this->_type ) )
			return null;

		if( is_int( $this->_type ) )
			$this->_getType();
			
		return $this->_type->getId();
	}
	
	
	public function toArray()
	{
		$data = parent::toArray();
		
		return array_merge( $data, array(	'status'	=> $this->getStatus(),
											'sprint'	=> $this->getSprintId(),
											'feature'	=> $this->getFeatureId(),
											'priority'	=> $this->getPriority(),
											'type'		=> $this->getTypeId() ) );
	}
}
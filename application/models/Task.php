<?php
require_once( dirname( __FILE__ ) . '/TaskMapper.php' );
require_once( dirname( __FILE__ ) . '/AbstractModel.php' );

class Application_Model_Task extends Application_Model_AbstractModel
{
	/**
	 * 
	 * The list of <code>Story</code> to which the task is attached.
	 * Cannot be <code>null</code>. Can be empty.
	 * @var array[Application_Model_Story]
	 */
	protected $_stories = array();
	
	
	/**
	 * 
	 * The status of the task.
	 * By default, equals <code>Application_Model_Status::SUGGESTED</code>
	 * @var int
	 */
	protected $_status;
	
	
	public function __construct( $options = array() )
	{
		$this->_status = Application_Model_Status::SUGGESTED;
		parent::__construct( $options );
	}
	
	
	/**
	 * 
	 * @param int $status the status of the task
	 * @throws InvalidArgumentException if $status is NaN or not a valid status
	 */
	public function setStatus( $status )
	{
		if( !is_int( $status ) )
			throw new InvalidArgumentException( "'\$status' is 'NaN' !" );
			
		// Status is an integer
		if( !Application_Model_Status::isValid( $status ) )
			throw new InvalidArgumentException( "'\$status' is not a valid status !" );
		
		if( $status !== Application_Model_Status::TODO
			&& $status !== Application_Model_Status::WIP
			&& $status !== Application_Model_Status::FINISHED 
			&& $status !== Application_Model_Status::PASSED
			&& $status !== Application_Model_Status::FAILED )
			throw new InvalidArgumentException( "The status '" . $status . "' is not allowed !" );
			
		$this->_status = $status;
		
		return $this;
	}
	

	/**
	 * @return Application_Model_Status
	 * The current status of the task
	 */
	public function getStatus()
	{
		return $this->_status;
	}
	
	
	/**
	 * 
	 * @param array[Application_Model_Story] | Application_Model_Story $story the story (or array of story) to which add this task
	 * @throws InvalidArgumentException if $story is 'null'
	 */
	public function addStory( $story )
	{
		if( is_array( $story ) )
			foreach( $story as $s )
				$this->_addStory( $s );
		else
			$this->_addStory( $story );
		
		return $this;
	}
	
	
	protected function _addStory( Application_Model_Story $story )
	{
		$index = array_search( $story, $this->_stories );
		if( $index === false )
		{
			$this->_stories[] = $story;
			$story->addTask( $this );
		}
		
		return $this;
	}
	
	
	/**
	 * 
	 * @param array[Application_Model_Story] | Application_Model_Story $story the story (or array of story) to which remove this task
	 * @throws InvalidArgumentException if $story is 'null'
	 */
	public function removeStory( $story )
	{
		if( is_array( $story ) )
			foreach( $story as $s )
				$this->_removeStory( $s );
		else
			$this->_removeStory( $story );
		
		return $this;
	}
	
	
	protected function _removeStory( Application_Model_Story $story )
	{
		$index = array_search( $story, $this->_stories );
		if( $index !== false )
		{
			unset( $this->_stories[$index] );
			$story->removeTask( $this );
		}
		
		return $this;
	}
	
	
	/**
	 * 
	 * Enter description here ...
	 * @param int $index
	 * @throws InvalidArgumentException
	 */
	public function getStory( $index )
	{
		if( !is_numeric( $index ) )
			throw new InvalidArgumentException( "'\$index' is NaN !" );
		if( $index < 0 || $index >= sizeof( $this->_stories ) )
			throw new OutOfRangeException("'\$id' cannot be negative, greater than or equal to the array size !" );
		
		return $this->_stories[$index];
	}
	
	
	/**
	 * 
	 * Return the list of 'stories' to which this task is attached
	 * @return array[Application_Model_Story]
	 */
	public function getStories()
	{
		return $this->_stories;
	}
	
	
	public function toArray()
	{
		return array_merge( parent::toArray(), array( 'status' => $this->getStatus() ) );
	}
}
?>
<?php
class Application_Model_Sprint extends Application_Model_AbstractModel
{
	protected	$_release = null;
	
	
	protected	$_status;
	
	
	protected	$_stories = array();
	
	
	protected	$_startDate = null;
	
	
	protected	$_endDate = null;
	
	
	
	public function __construct( $options = array() )
	{
		$this->_status = Application_Model_Status::SUGGESTED;
		parent::__construct( $options );
	}
	
	
	/**
	 * 
	 * Enter description here ...
	 * @param array[Application_Model_Story] | Application_Model_Story $story the story (or array of stories) we want to add 
	 * @throws InvalidArgumentException
	 */
	public function addStory( $story )
	{
		if( $story === null || empty( $story ) )
			throw new InvalidArgumentException( "'\$story' cannot be 'null' or empty !" );
			
		if( is_array( $story ) )
			foreach( $story as $s )
				$this->_addStory( $s );
		else if( $story instanceof Application_Model_Story )
			$this->_addStory( $story );
		else
			throw new UnexpectedValueException( "'\$story' is not a Application_Model_Story or an array of Application_Model_Story !" );
			
		return $this;
	}
	
	
	protected function _addStory( Application_Model_Story $story )
	{
		$index = array_search( $story, $this->_stories );
		if( $index === false )
		{
			$this->_stories[] = $story;
			$story->setSprint( $this );
		}
		
		return $this;
	}
	
	
	public function removeStory( $story )
	{
		if( $story === null || empty( $story ) )
			throw new InvalidArgumentException( "'\$story' cannot be 'null' or empty !" );
		
		if( is_array( $story ) )
			foreach( $story as $s )
				$this->_removeStory( $s );
		else if( $story instanceof Application_Model_Story )
			$this->_removeStory( $story );
		else
			throw new UnexpectedValueException( "'\$story' is not a Application_Model_Story or an array of Application_Model_Story !" );
			
		return $this;
	}
	
	
	protected function _removeStory( Application_Model_Story $story )
	{
		$index = array_search( $story, $this->_stories );
		if( $index !== false )
		{
			unset( $this->_stories[$index] );
			$story->setSprint( null );
		}
		
		return $this;
	}
	
	
	/**
	 * 
	 * Enter description here ...
	 * @param int $index
	 * @throws InvalidArgumentException
	 * @throws OutOfRangeException
	 * @return Application_Model_Sprint
	 */
	public function getStory( $index )
	{
		if( !is_numeric( $index ) )
			throw new InvalidArgumentException( "'\$index' is Nan !" );
		if( $index < 0 || $index >= sizeof( $this->_stories ) )
			throw new OutOfRangeException( "'\$index' cannot be negative, greater than or equal to the array size !" );
			
		return $this->_stories[$index];
	}
	
	
	public function getStories()
	{
		return $this->_stories;
	}
	
	
	/**
	 * 
	 * @param int $status the status of the sprint
	 * @throws InvalidArgumentException if $status is NaN or not a valid status
	 */
	public function setStatus( $status )
	{
		if( !$status instanceof Application_Model_Status && !is_numeric( $status ) )
			throw new InvalidArgumentException( "'\$status' is NaN and not an instance of Application_Model_Status !" );
		if( !Application_Model_Status::isValid( $status ) )
			throw new InvalidArgumentException( "'\$status' is not a valid status !" );
		
		if( !Application_Model_Status::isValidSprintStatus( $status ) )
			throw new InvalidArgumentException( "The status '" . Application_Model_Status::getStatus( $status ) . "' is not allowed !" );
			
		$this->_status = $status;
		
		return $this;
	}
	
	
	public function getStatus()
	{
		if( !$this->_status instanceof Application_Model_Status )
		{
			$sm = new Application_Model_StatusMapper();
			$this->_status = $sm->find( $this->_status );
		}
		
		return $this->_status;
	}
	
	
	public function getStatusId()
	{
		if( $this->_status instanceof Application_Model_Status )
			return $this->_status->getId();
		
		return $this->_status;
	}
	
	
	public function getPoints()
	{
		$points = 0;
		foreach( $this->_stories as $story )
			$points += $story->getPoints();
		
		return $points;
	}
	
	
	public function setRelease( $release )
	{
		$this->_release = $release;

		return $this;
	}
	
	
	public function getRelease()
	{
		return $this->_release;
	}
	
	
	public function getReleaseId()
	{
		if( $this->_release instanceof Application_Model_Feature )
			return $this->_release->getId();
		else
			return $this->_release;
	}
	
	
	public function setStartDate( $startDate = null )
	{
		if( null === $startDate )
			$this->_startDate = null;
		else if( $startDate instanceof Zend_Date )
			$this->_startDate = $startDate;
		else
			$this->_startDate = new Zend_Date( $startDate );
		
		return $this;
	}
	
	
	/**
	 * 
	 * Enter description here ...
	 * @param string|Zend_Date $date
	 */
	public function start( $date = null )
	{
		/*debug_print_backtrace();die();
		if( $this->_startDate !== null && Application_Model_Status::isStarted( $this->getStatus() ) )
			throw new BadMethodCallException( "This sprint has already started !" );*/
			
		if( $date === null )
			$date = new Zend_Date( 'now' );
		else if( is_string( $date ) )
			$date = new Zend_Date( $date );
		
		$this->_startDate = $date;
		$this->_status = Application_Model_Status::WIP;
	}
	
	
	public function getStartDate( $format = null )
	{
		if( null === $this->_startDate )
			return null;
		
		if( null === $format )
			$format = Zend_Date::DATE_MEDIUM;
		 
		return $this->_startDate->get( $format );
	}
	
	
	public function setEndDate( $endDate = null )
	{
		if( null === $endDate )
			$this->_endDate = null;
		else if( $endDate instanceof Zend_Date )
			$this->_endDate = $endDate;
		else
			$this->_endDate = new Zend_Date( $endDate );
		
		return $this;
	}
	
	
	/**
	 * @TODO : tester le status pour vérifier qu'une story ne peut être PASSED si un de ses tests est FAILED.
	 * Defines the end date of the sprint, and its status
	 * @param string | Zend_Date $date the new status of the sprint. <code>Application_Model_Status::FINISHED</code> by default.
	 * @param int $status the new status of the sprint.
	 * @throws BadMethodCallException if the sprint is finished already
	 * @throws InvalidArgumentException if the end date is before the start date
	 */
	public function end( $date = null, $status = Application_Model_Status::FINISHED )
	{
		if( $this->_endDate !== null )
			throw new BadMethodCallException( "This sprint has already finished !" );
			
		if( $date === null )
			$date = new Zend_Date( 'now' );
		else if( is_string( $date ) )
			$date = new Zend_Date( $date );
		if( $date->isEarlier( $this->_startDate ) )
			throw new InvalidArgumentException( "this sprint cannot finish before it starts !" );
		
		$this->_endDate = $date;
		$this->setStatus( $status );
	}
	
	
	public function getEndDate( $format = null )
	{
		if( null === $this->_endDate )
			return null;
		
		if( null === $format )
			$format = Zend_Date::DATE_MEDIUM;
		
		return $this->_endDate->get( $format );
	}
	
	
	public function toArray()
	{
		return array_merge( parent::toArray(), array(	'startDate'	=> $this->getStartDate(),
														'endDate'	=> $this->getEndDate(),
														'release'	=> $this->getReleaseId(),
														'status'	=> $this->getStatus() ) );
	}
}
?>
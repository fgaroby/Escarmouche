<?php

/**
 *
 *
 * @author Francescu Garoby
 *
 */
class Application_Model_Release extends Application_Model_AbstractModel
{
	/**
	 * 
	 * @var array[Application_Model_Sprint]
	 */
	protected $_sprints = array();

	
	protected $_product = null;
	
	
	protected $_status;
	
	
	/**
	 * 
	 * @var Zend_Date
	 */
	protected $_startDate;
	
	
	/**
	 * 
	 * @var Zend_Date
	 */
	protected $_endDate;
	
	
	/**
	 *
	 * @var int
	 */
	protected $_duration;
	
	
	
	public function __construct( $options = array() )
	{
		$this->_status = Application_Model_Status::SUGGESTED;
		parent::__construct( $options );
	}


	/**
	 * 
	 * @param array[Application_Model_Sprint] | Application_Model_Sprint $sprint
	 */
	public function addSprint( $sprint )
	{
		if( is_array( $sprint ) )
			foreach( $sprint as $s )
				$this->_addSprint( $s );
		else
			$this->_addSprint( $sprint );
			
		return $this;
	}
	
	
	protected function _addSprint( Application_Model_Sprint $sprint )
	{
		$index = array_search( $sprint, $this->_sprints );
		if( $index === false )
		{
			$this->_sprints[] = $sprint;
			$sprint->setRelease( $this );
		}
		
		return $this;
	} 


	/**
	 * 
	 * @param array[Application_Model_Sprint] | Application_Model_Sprint $sprint
	 */
	public function removeSprint( $sprint )
	{
		if( is_array( $sprint ) )
			foreach( $sprint as $s )
				$this->_removeSprint( $s );
		else
			$this->_removeSprint( $sprint );

		return $this;
	}
	
	
	protected function _removeSprint( Application_Model_Sprint $sprint )
	{
		$index = array_search( $sprint, $this->_sprints );
		if( $index !== false )
		{
			unset( $this->_sprints[$index] );
			$sprint->setRelease( $this );
		}
		
		return $this;
	}
	
	
	public function getSprint( $index )
	{
		if( !is_int( $index ) )
			throw new InvalidArgumentException( "'\$index' cannot be 'NaN' !" );
		if( $index < 0 || $index >= sizeof( $this->_sprints ) )
			throw new OutOfRangeException( "'\$index' cannot be negative, greater than or equal to the size of the array !" );

		return $this->_sprints[$index];
	}


	public function getSprints()
	{
		return $this->_sprints;
	}
	
	
	/**
	 * @todo changer le calcul du "pourcentage" et résoudre le bug CSS qui empêche le positionnement côte à côte
	 * Enter description here ...
	 */
	public function getMinimized()
	{
		$minimized = array();
		foreach( $this->_sprints as $sprint )
		{
			$minimized[$sprint->getStatus()->getId()]['color'] = $sprint->getStatus()->getName();
			$minimized[$sprint->getStatus()->getId()]['value'] = isset( $minimized[$sprint->getStatus()->getId()]['value'] ) ? $minimized[$sprint->getStatus()->getId()]['value']++ : 1;
		}
		foreach( $this->_sprints as $sprint )
		{
			$minimized[$sprint->getStatus()->getId()]['percent'] = $minimized[$sprint->getStatus()->getId()]['value'] / sizeof( $this->_sprints ) * 99;
		}
		
		return $minimized;
	}
	
	
	public function setProduct( $product = null )
	{
		if( null !== $product
			&& !$product instanceof Application_Model_Product
			&& !intval( $product, 10 ) )
			throw new InvalidArgumentException( "'\$product is NaN or an instance of Application_Model_Product !" );

		$this->_product = $product;
		
		return $this;
	}
	
	
	public function getProduct()
	{
		return $this->_product;
	}
	
	
	/**
	 * 
	 * @param int | Application_Model_Status $status the status of the task
	 * @throws InvalidArgumentException if $status is NaN or not a valid status
	 */
	public function setStatus( $status )
	{
		if( !$status instanceof Application_Model_Status && !intval( $status, 10 ) )
			throw new InvalidArgumentException( "'\$status' is NaN and not an instance of Application_Model_Status !" );
		if( !Application_Model_Status::isValid( $status ) )
			throw new InvalidArgumentException( "'\$status' is not a valid status !" );
		
		if( !Application_Model_Status::isValidReleaseStatus( $status ) )
			throw new InvalidArgumentException( "This status '" . Application_Model_Status::getStatus( $status ) . "' is not allowed !" );
			
		$this->_status = $status;
		
		return $this;
	}
	

	/**
	 * @return Application_Model_Status
	 * The current status of the task
	 */
	public function getStatus()
	{
		if( !$this->_status instanceof Application_Model_Status )
		{
			$sm = new Application_Model_StatusMapper();
			$this->_status = $sm->find( ( int ) $this->_status );
		}
		
		return $this->_status;
	}
	
	
	/**
	 * 
	 * Enter description here ...
	 * @param string | Zend_Date | null $startDate
	 */
	public function setStartDate( $startDate = null )
	{
		if( is_string( $startDate ) ) 
			$startDate = new Zend_Date( $startDate );
		$this->_startDate = $startDate;
	}
	
	
	public function getStartDate( $format = null )
	{
		if( null === $this->_startDate )
			return null;
		
		if( null === $format )
			$format = Zend_Date::DATE_SHORT;
		 
		return $this->_startDate->get( $format );
	}
	
	
	/**
	 * 
	 * Enter description here ...
	 * @param string | Zend_Date | null $endDate
	 */
	public function setEndDate( $endDate = null )
	{
		if( is_string( $endDate ) ) 
			$endDate = new Zend_Date( $endDate );
		$this->_endDate = $endDate;
	}
	
	
	public function getEndDate( $format = null )
	{
		if( null === $this->_endDate )
			return null;
		
		if( null === $format )
			$format = Zend_Date::DATE_SHORT;
		 
		return $this->_endDate->get( $format );
	}
	
	
	public function setDuration( $duration = 0 )
	{
		$this->_duration = $duration;
	}
	
	
	public function getDuration()
	{
		return $this->_duration;
	}
	
	
	public function toArray()
	{
		return array_merge( parent::toArray(), array(	'startDate'	=> $this->getStartDate(),
														'endDate'	=> $this->getEndDate(),
														'duration'	=> $this->getDuration() ) );
	}
}
?>
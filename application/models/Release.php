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
	 * Enter description here ...
	 * @var array[Application_Model_Sprint]
	 */
	protected $_sprints = array();

	
	protected $_product;
	
	
	protected $_status;
	
	
	
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
		if( !is_numeric( $index ) )
			throw new InvalidArgumentException( "'index' cannot be 'NaN' !" );
		if( $index < 0 || $index >= sizeof( $this->_sprints ) )
			throw new OutOfRangeException( "'index' cannot be negative, greater than or equal to the size of the array !" );

		return $this->_sprints[$index];
	}


	public function getSprints()
	{
		return $this->_sprints;
	}
	
	
	public function setProduct( Application_Model_Product $product = null )
	{
		$this->_product = $product;
		
		return $this;
	}
	
	
	public function getProduct()
	{
		return $this->_product;
	}
	
	
	/**
	 * 
	 * @param int $status the status of the task
	 * @throws InvalidArgumentException if $status is NaN or not a valid status
	 */
	public function setStatus( $status )
	{
		if( !is_integer( $status ) )
			throw new InvalidArgumentException( "'\$status' is 'NaN' !" );
		if( !Application_Model_Status::isValid( $status ) )
			throw new InvalidArgumentException( "'\$status' is not a valid status !" );
		
		if( $status !== Application_Model_Status::SUGGESTED
			&& $status !== Application_Model_Status::PLANIFIED
			&& $status !== Application_Model_Status::WIP
			&& $status !== Application_Model_Status::FINISHED )
			throw new InvalidArgumentException( "This status is not allowed !" );
			
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
}
?>
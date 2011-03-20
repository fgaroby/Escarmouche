<?php
/**
 *  Application_Model_Task
 *  
 *  LICENSE
 *  
 *  Copyright (C) 2011  windu.2b
 *  
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as
 *  published by the Free Software Foundation, either version 3 of the
 *  License, or (at your option) any later version.
 *  
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *  
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *  
 *  @author windu.2b
 *  @license AGPL v3
 *  @since 0.1
 */

class Application_Model_Task extends Application_Model_AbstractModel
{
	/**
	 * 
	 * The status of the task.
	 * By default, equals <code>Application_Model_Status::SUGGESTED</code>
	 * @var int
	 */
	protected $_status;
	
	
	protected $_type		= null;
	
	
	protected $_estimation	= 0;
	
	
	protected $_createdBy	= null;
	
	
	protected $_assignedTo	= null;
	
	
	protected $_closedBy	= null;
	
	
	/**
	 * 
	 * The list of <code>Story</code> to which the task is attached.
	 * Cannot be <code>null</code>. Can be empty.
	 * @var array[Application_Model_Story]
	 */
	protected $_stories		= array();
	
	
	protected $_comments	= array();

	
	
	public function __construct( $options = array() )
	{
		$this->_status = Application_Model_Status::SUGGESTED;
		$this->_type = Application_Model_Type::STORY;
		parent::__construct( $options );
	}
	
	
	/**
	 * 
	 * @param int $status the status of the task
	 * @throws InvalidArgumentException if $status is NaN or not a valid status
	 */
	public function setStatus( $status )
	{
		if( !Application_Model_Status::isValid( $status ) )
			throw new InvalidArgumentException( "'\$status' " . $status . " is not a valid status !" );
		
		if( !Application_Model_Status::isValidTaskStatus( $status ) )
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
		if( !$this->_status instanceof Application_Model_Status )
			$this->_status = Application_Model_StatusMapper::getInstance()->find( $this->_status );
		
		return $this->_status;
	}
	
	
	public function getStatusId()
	{
		if( $this->_status instanceof Application_Model_Status )
			return $this->_status->getId();
		else
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
	
	
	public function setEstimation( $estimation )
	{
		$this->_estimation = $estimation;
		
		return $this;
	}
	
	
	/**
	 * 
	 * @return int
	 */
	public function getEstimation()
	{
		return $this->_estimation;
	}
	
	
	protected function _loadType()
	{
		if( null === $this->_type )
			$this->_type = Application_Model_TypeMapper::getInstance()->find( $this->_type );		
	}
	
	
	public function setType( $type )
	{
		$this->_type = $type;
		
		return $this;
	}
	
	
	/**
	 * 
	 * @return Application_Model_Type
	 */
	public function getType()
	{
		$this->_loadType();
		
		return $this->_type;
	}
	
	
	/**
	 * 
	 * @return int | null
	 */
	public function getTypeId()
	{
		$this->_loadType();
		
		if( $this->_type instanceof Application_Model_Type )
			return $this->_type->getId();
		else
			return $this->_type;
	}
	
	
	public function setCreatedBy( $createdBy = null )
	{
		$this->_createdBy = $createdBy;
		
		return $this;
	}
	
	
	/**
	 * 
	 * @return Application_Model_User
	 */
	public function getCreatedBy()
	{
		if ( !$this->_createdBy instanceof Application_Model_User )
			$this->_createdBy = Application_Model_UserMapper::getInstance()->find( $this->_createdBy );
		
		return $this->_createdBy;
	}
	
	
	public function getCreatedById()
	{
		if ( $this->_createdBy instanceof Application_Model_User )
			return $this->_createdBy->getId();
		else
			return $this->_createdBy;
	}
	
	
	public function setAssignedTo( $assignedTo = null )
	{
		$this->_assignedTo = $assignedTo;
		
		return $this;
	}
	
	
	public function getAssignedTo()
	{
		if ( !$this->_assignedTo instanceof Application_Model_User )
			$this->_assignedTo = Application_Model_UserMapper::getInstance()->find( $this->_assignedTo );
		
		return $this->_assignedTo;
	}
	
	
	public function getAssignedToId()
	{
		if ( $this->_assignedTo instanceof Application_Model_User )
			return $this->_assignedTo->getId();
		else
			return $this->_assignedTo;
	}
	
	
	public function setClosedBy( $closedBy = null )
	{
		$this->_closedBy = $closedBy;
		
		return $this;
	}
	
	
	public function getClosedBy()
	{
		if ( !$this->_closedBy instanceof Application_Model_User )
			$this->_closedBy = Application_Model_UserMapper::getInstance()->find( $this->_closedBy );
		
		return $this->_closedBy;
	}
	
	
	public function getClosedById()
	{
		if ( $this->_closedBy instanceof Application_Model_User )
			return $this->_closedBy->getId();
		else
			return $this->_closedBy;
	}
	
	
	public function getColor()
	{
		$this->_loadType();
		
		if( $this->_type instanceof Application_Model_Type )
			return $this->_type->getColor();
		else
			return Zend_Registry::get( 'config' )->color->border->default;
	}
	
	
	public function getComments()
	{
		return $this->_comments;
	}
	
	
	public function toArray()
	{
		return array_merge( parent::toArray(), array(	'status'		=> $this->getStatusId(),
														'type'			=> $this->getTypeId(),
														'estimation'	=> $this->getEstimation(),
														'created_by'	=> $this->getCreatedById(),
														'assigned_to'	=> $this->getAssignedToId(),
														'closed_by'		=> $this->getClosedById() ) );
														
	}
}
?>
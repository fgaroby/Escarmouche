<?php
/**
 *  Application_Model_Feature
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

class Application_Model_Feature extends Application_Model_AbstractModel
{
	protected $_color;
	
	
	protected $_stories = array();
	
	
	protected $_status;
	
	
	protected $_release = null;
	
	
	public function __construct( $options  = array() )
	{
		$this->_status = Application_Model_Status::SUGGESTED;
		parent::__construct( $options );
	}
	
	
	/**
	 * @todo filtrer pour ne garder que des valeurs correspondant à des code-couleurs (RVB ou nom de couleur).
	 * @param string $color. The color to apply to this feature. Can be an hex value or a color name.
	 */
	public function setColor( $color )
	{
			$this->_color = $color;
	}
	
	
	public function getColor()
	{
		return $this->_color;
	}
	
	
	/**
	 * 
	 * @param int $status the status of the task
	 * @throws InvalidArgumentException if $status is NaN or not a valid status
	 */
	public function setStatus( $status )
	{
		if( !$status instanceof Application_Model_Status )
		{
			if( !Application_Model_Status::isValid( $status ) )
				throw new InvalidArgumentException( "'\$status' is not a valid status !" );
			
			if( !Application_Model_Status::isValidFeatureStatus( $status ) )
				throw new InvalidArgumentException( "The status '" . $status . "' is not allowed !" );
		}
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
		
		return $this->_status;
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
		if( $this->_release instanceof Application_Model_Release )
			return $this->_release->getId();
			
		return $this->_release;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param array[Application_Model_Story] | Application_Model_Story $story the story (or array of stories) we want to add 
	 * @throws InvalidArgumentException
	 */
	public function addStory( $story )
	{
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
			$story->setFeature( $this );
		}
		
		return $this;
	}
	
	
	public function removeStory( $story )
	{
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
			$story->setFeature( null );
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
	
	
	public function toArray()
	{
		$data = parent::toArray();
		
		return array_merge( $data, array(	'status'	=> $this->getStatus(),
											'release'	=> $this->getReleaseId(),
											'color'		=> $this->getColor() ) );
	}
}
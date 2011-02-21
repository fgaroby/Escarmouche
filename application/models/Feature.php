<?php
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
			if( !is_numeric( intval( $status ) ) )
				throw new InvalidArgumentException( "\$status' is 'NaN' !" );
				
			// $status is an integer
			$status = intval( $status, 10 );	
			if( !Application_Model_Status::isValid( $status ) )
				throw new InvalidArgumentException( "'\$status' is not a valid status !" );
			
			if( $status !== Application_Model_Status::TODO
				&& $status !== Application_Model_Status::WIP
				&& $status !== Application_Model_Status::FINISHED )
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
		
		return array_merge( $data, array(	'status_id'		=> $this->getStatus(),
											'release_id'	=> $this->getReleaseId(),
											'color'			=> $this->getColor() ) );
	}
}
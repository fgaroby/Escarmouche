<?php
class Application_Model_Product extends Application_Model_AbstractModel
{
	protected $_releases = array();
	
	
	protected $_scrumMaster = null;
	
	
	protected $_productOwner = null;
	
	
	
	public function __construct( array $options = array() )
	{
		parent::__construct( $options );
	}
	
	
	public function addRelease( $release )
	{
		if( is_array( $release ) )
			foreach( $release as $r )
				$this->_addRelease( $r );
		else
			$this->_addRelease( $release );
		
		return $this;
	}
	
	
	protected function _addRelease( Application_Model_Release $release )
	{
		if( !in_array( $release, $this->_releases ) )
		{
			$this->_releases[] = $release;
			$release->setProduct( $this );
		}
		
		return $this;
	}
	
	
	public function removeRelease( $release )
	{
		if( is_array( $release ) )
			foreach( $release as $r )
				$this->_removeRelease( $r );
		else
			$this->_removeRelease( $release );
		
		return $this;
	}
	
	
	protected function _removeRelease( Application_Model_Release $release )
	{
		$index = array_search( $release, $this->_releases );
		if( $index !== false )
		{
			unset( $this->_releases[$index] );
			$release->setProduct( null );
		}
		
		return $this;
	}
	
	
	public function setScrumMaster( $scrumMaster )
	{
		$this->_scrumMaster = $scrumMaster;
	}
	
	
	public function getScrumMaster()
	{
		if( is_int( $this->_scrumMaster ) )
		{
			$um = new Application_Model_UserMapper();
			$this->_scrumMaster = $um->find( $this->_scrumMaster );
		}
		
		return $this->_scrumMaster;
	}
	
	
	public function setProductOwner( $productOwner )
	{
		$this->_productOwner = $productOwner;
	}
	
	
	public function getProductOwner()
	{
		if( is_int( $this->_productOwner ) )
		{
			$po = new Application_Model_UserMapper();
			$this->_productOwner = $po->find( $this->_productOwner );
		}
		
		return $this->_productOwner;
	}
	
	
	public function getRelease( $index )
	{
		if( !is_numeric( $index ) )
			throw new InvalidArgumentException( "'\$index' is NaN !" );
		if( $index < 0 || $index >= sizeof( $this->_releases ) )
			throw new OutOfRangeException("'\$id' cannot be negative, greater than or equal to the array size !" );
		
		return $this->_releases[$index];
	}
	
	
	public function getReleases()
	{
		return $this->_releases;
	}
	
	
	public function toArray()
	{
		return array_merge( parent::toArray(), array(	'scrumMaster'	=> $this->_scrumMaster,
														'productOwner'	=> $this->_productOwner ) );
	}
}
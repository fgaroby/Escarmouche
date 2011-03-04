<?php
/**
 *  Application_Model_Product
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

class Application_Model_Product extends Application_Model_AbstractModel
{
	protected $_releases		= array();
	
	
	protected $_scrumMaster		= null;
	
	
	protected $_productOwner	= null;
	
	
	protected $_developpers		= array();
	
	
	
	public function __construct( $options = array() )
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
		if( $scrumMaster instanceof Application_Model_User )
			$scrumMaster->setRole( 'scrumMaster' );
		$this->_scrumMaster = $scrumMaster;
		
		return $this;
	}
	
	
	public function getScrumMaster()
	{
		if( !$this->_scrumMaster instanceof Application_Model_User )
		{
			$um = new Application_Model_UserMapper();
			$this->_scrumMaster = $um->find( $this->_scrumMaster );
			$this->_scrumMaster->setRole( 'scrumMaster' );
		}
		
		return $this->_scrumMaster;
	}
	
	
	public function getDeveloppers()
	{
		$um = null;
		foreach( $this->_developpers as $key => $developper )
		{
			if( !$developper instanceof Application_Model_User )
			{
				if( $um === null )
					$um = new Application_Model_UserMapper();
				$this->_developpers[$key] = $um->find( $developper );
			}
		}
		
		return $this->_developpers;
	}
	
	
	public function setProductOwner( $productOwner )
	{
		if( $productOwner instanceof Application_Model_User )
			$productOwner->setRole( 'productOwner' );
		$this->_productOwner = $productOwner;
		
		return $this;
	}
	
	
	public function getProductOwner()
	{
		if( !$this->_productOwner instanceof Application_Model_User )
		{
			$po = new Application_Model_UserMapper();
			$this->_productOwner = $po->find( $this->_productOwner );
			$this->_productOwner->setRole( 'productOwner' );
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
	
	
	public function getUsers()
	{
		return array(	'productOwner'	=> $this->getProductOwner(),
						'scrumMaster'	=> $this->getScrumMaster(),
						'developper'	=> $this->getDeveloppers() );
	}
	
	public function toArray()
	{
		return array_merge( parent::toArray(), array(	'scrumMaster'	=> $this->getScrumMaster()->getId(),
														'productOwner'	=> $this->getProductOwner()->getId() ) );
	}
}
<?php
/**
 *  Application_Model_AbstractModel
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

abstract class Application_Model_AbstractModel
{
	/**
	 * 
	 * Enter description here ...
	 * @var int
	 */
	protected $_id;
	
	
	/**
	 * 
	 * Enter description here ...
	 * @var string
	 */
	protected $_name = '';
	
	
	/**
	 * 
	 * Enter description here ...
	 * @var string
	 */
	protected $_description = '';


	public function __construct( $options = array() )
	{
		if( ( !isset( $options['id'] ) || empty( $options['id'] ) ) && ( !isset( $options['name'] ) || empty( $options['name'] ) ) )
		{
			throw new InvalidArgumentException( "'\$id' and '\$name' cannot be both 'null' or empty !" );
		}
		
		if( is_array( $options ) || $options instanceof ArrayAccess )
			$this->setOptions( $options );
	}


	public function setOptions( $options )
	{
		$methods = get_class_methods( $this );
		foreach( $options as $key => $value )
		{
			if( is_array( $value ) )
				$method = 'add' . ucfirst( $key );
			else
				$method = 'set' . ucfirst( $key );
			
			if( in_array( $method, $methods ) )
				$this->$method( $value );
		}

		return $this;
	}


	public function __set( $name, $value )
	{
		$method = 'set' . ucfirst( $name );
		if( ( 'mapper' === $name ) || !method_exists( $this, $method ) )
			throw new Exception( 'Invalid release property' );

		$this->$method( $value );
	}

	
	public function setFromArray( array $data )
	{
		foreach( $data as $key => $value )
			$this->__set( $key, $value );
	}

	public function __get( $name )
	{
		$method = 'get' . ucfirst( $name );
		if( ( 'mapper' === $name ) || !method_exists( $this, $method ) )
			throw new Exception( 'Invalid property : ' . $name );

		return $this->$method();
	}


	public function setId( $id )
	{
		if( empty( $id ) )
			$this->_id = null;
		else
			$this->_id = ( int ) $id;

		return $this;
	}


	public function getId()
	{
		return $this->_id;
	}


	public function setName( $name )
	{
		if( $name === null || empty( $name ) )
			throw new InvalidArgumentException( "'name' cannot be 'null' or empty !" );
		
		$this->_name = $name;

		return $this;
	}


	public function getName()
	{
		return $this->_name;
	}


	public function setDescription( $description )
	{
		$this->_description = $description;

		return $this;
	}


	public function getDescription()
	{
		return $this->_description;
	}
	
	
	public function toArray()
	{
		return array(	'id'			=> $this->getId(),
						'name'			=> $this->getName(),
						'description'	=> $this->getDescription() );
	}
}
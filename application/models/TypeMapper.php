<?php
/**
 *  Application_Model_TypeMapper
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

class Application_Model_TypeMapper extends Application_Model_AbstractMapper
{
	protected static $_instance;



	protected function __construct()
	{
		
	}
	
	
	public function getDbTable()
	{
		if( null === $this->_dbTable )
			$this->setDbTable('Application_Model_Db_Table_Type' );

		return $this->_dbTable;
	}


	/**
	* 
	* @see application/models/Application_Model_AbstractMapper::save()
	* @param Application_Model_Type $type
	* @return void
	*/
	public function save( Application_Model_AbstractModel $type )
	{
		if( !$type instanceof Application_Model_Type )
			throw new InvalidArgumentException( "'\$type' must be instance of 'Application_Model_Type' !" );

		$data = array(	'name'			=> $type->getName(),
		'description'	=> $type->getDescription(),
		'status'		=> $type->getStatus() );

		if( null === ( $id = $type->getId() ) )
		{
			unset( $data['id'] );
			$this->getDbTable()->insert( $data );
		}
		else
			$this->getDbTable()->update( $data, array( 'id = ?' => $id ) );
	}


	/**
	* 
	* @see Application_Model_AbstractMapper::find()
	* @param int $id
	*/
	public function find( $id )
	{
		if( !$id )
			return new Zend_Db_Table_Row();

		if( isset( $this->_loadedMap[$id] ) )
			return $this->_loadedMap[$id];

		$rowset = $this->getDbTable()->find( array( 'id = ?' => $id ) );
		if( 0 === $rowset->count() )
			return new Zend_Db_Table_Row();

		$row = $rowset->current();
		$data = array(	'id'			=> $row->id,
		'name'			=> $row->name,
		'description'	=> $row->description );

		$this->_loadedMap[$id] = new Application_Model_Type( $data );

		return $this->_loadedMap[$id];
	}


	/**
	* 
	* @see Application_Model_AbstractMapper::fetchAll()
	*/
	public function fetchAll( $where = null, $order = null, $count = null, $offset = null )
	{
		$resultSet = $this->getDbTable()->fetchAll( $where, $order, $count, $offset );
		$entries = array();
		foreach( $resultSet as $row )
		{
			$entry = new Application_Model_Type( array(	'id'			=> $row->id,
			'name'			=> $row->name,
			'description'	=> $row->description ) );
			$entries[] = $entry;
		}

		return $entries;
	}


	/**
	* 
	* @see Application_Model_AbstractMapper::delete()
	* @param int | Application_Model_Type $type
	*/
	public function delete( Application_Model_AbstractModel $type )
	{
		if( null === ( $id = $type->getId() ) )
		throw new Exception( 'Object ID not set !' );

		unset( $this->_loadedMap[$id] );
		$this->getDbTable()->delete( array( 'id = ?' => $id ) );
	}


	public static function getInstance()
	{
		if( null === self::$_instance )
		self::$_instance = new Application_Model_TypeMapper();

		return self::$_instance;
	}
}
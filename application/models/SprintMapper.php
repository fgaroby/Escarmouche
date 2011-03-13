<?php
/**
 *  Application_Model_SprintMapper
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

class Application_Model_SprintMapper extends Application_Model_AbstractMapper
{
	protected static $_instance;



	protected function __construct()
	{
		
	}
	
	
	public function getDbTable()
	{
		if( null === $this->_dbTable )
		$this->setDbTable('Application_Model_Db_Table_Sprint');

		return $this->_dbTable;
	}


	/**
	 * 
	 * @see Application_Model_AbstractMapper::save()
	 * @param Application_Model_Sprint $sprint
	 */
	public function save( Application_Model_AbstractModel $sprint )
	{
		if( !$sprint instanceof Application_Model_Sprint )
			throw new InvalidArgumentException( "'\$sprint' is not an instance of Application_Model_Sprint !" );

		$data = array(	'name'			=> $sprint->getName(),
						'description'	=> $sprint->getDescription(),
						'status'		=> $sprint->getStatusId(),
						'startDate'		=> $sprint->getStartDate( Zend_Date::ISO_8601),
						'endDate'		=> $sprint->getEndDate( Zend_Date::ISO_8601 ),
						'release'		=> $sprint->getReleaseId() );

		if( null === ( $id = $sprint->getId() ) )
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
			return null;

		if( isset( $this->_loadedMap[$id] ) )
			return $this->_loadedMap[$id];
		
		$rowset = $this->getDbTable()->find( array( 'id = ?' => $id ) );
		if( 0 === $rowset->count() )
			return null;
			
		$row = $rowset->current();
		$sprint = new Application_Model_Sprint( $row );
		
		$rsStories = $row->findDependentRowset( 'Application_Model_Db_Table_Story' );
		while( $rsStories->next() )
			$sprint->addStory( new Application_Model_Story( $rsStories->current() ) );
		
		$this->_loadedMap[$id] = $sprint;
		
		return $this->_loadedMap[$id];
	}


	public function fetchAll( $where = null, $order = null, $count = null, $offset = null )
	{
		$resultSet = $this->getDbTable()->fetchAll( $where, $order, $count, $offset );
		$entries = array();
		foreach( $resultSet as $row )
		{
			$entry = new Application_Model_Sprint( $row );
			
			$rsStories = $row->findDependentRowset( 'Application_Model_Db_Table_Story' );
			foreach( $rsStories as $rwStory )
				$entry->addStory( new Application_Model_Story( $rwStory ) );
			
			$entries[] = $entry;
		}

		return $entries;
	}
	
	
	/**
	 * 
	 * @see Application_Model_AbstractMapper::delete()
	 * @param int | Application_Model_Sprint $sprint
	 * @return void
	 */
	public function delete( Application_Model_AbstractModel $sprint )
	{
		if( $sprint instanceof Application_Model_Sprint )	
			if( null === ( $id = $sprint->getId( ) ) )
				throw new Exception( 'Object ID not set' );
		else
			$id = $sprint;
		
		unset( $this->_loadedMap[$id] );
		$this->getDbTable()->delete( array( 'id = ?' => $id ) );
	}
	
	
	public static function getInstance()
	{
		if( null === self::$_instance )
			self::$_instance = new Application_Model_SprintMapper();
		
		return self::$_instance;
	}
}
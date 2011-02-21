<?php
require_once( dirname( __FILE__ ) . '/AbstractMapper.php' );
require_once( dirname( __FILE__ ) . '/Db/Table/Task.php' );;


class Application_Model_TaskMapper extends Application_Model_AbstractMapper
{
	/**
	 * Get storage
	 * @return Zend_Db_Table_Abstract
	 */
	public function getDbTable()
	{
		if( null === $this->_dbTable )
			$this->setDbTable( 'Application_Model_Db_Table_Task' );

		return $this->_dbTable;
	}
    
    
    
    /**
     * 
     * @see Application_Model_AbstractMapper::save()
     */
	public function save( $task )
	{
		if( !$task instanceof Application_Model_Task )
			throw new InvalidArgumentException( "'\$task' must be instance of 'Application_Model_Task' !" );
			
		$data = array(	'name'			=> $task->getName(),
						'description'	=> $task->getDescription(),
						'status_id'		=> $task->getStatus() );
		
		if( null === ( $id = $task->getId() ) )
		{
			$table = $this->getDbTable();
			unset( $data['id'] );
			$table->insert( $data );
			$id = $table->getAdapter()->lastInsertId();
			$task->setId( $id );
			
			$this->_loadedMap[$id] = $task;
		}
		else
			$this->getDbTable()->update( $data, array( 'id = ?' => $id ) );
			
		return $this;
	}
	
	
	/**
	 * Find one task by ID
	 * @see application/models/Application_Model_AbstractMapper::fetchAll()
	 * @return Application_Model_Task
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
		$data = array( 	'id'			=> $row->id,
			 			'name'			=> $row->name,
			 			'description'	=> $row->description,
			 			'status'		=> $row->status_id );
		$this->_loadedMap[$id] = new Application_Model_Task( $data );
		
		return $this->_loadedMap[$id];
	}
	
	
	/**
	 * 
	 * @see Application_Model_AbstractMapper::fetchAll()
	 */
	public function fetchAll()
	{
		$resultSet = $this->getDbTable()->fetchAll();
		$entries = array();
		foreach( $resultSet as $row )
		{
			$entry = new Application_Model_Task( array(	'id'			=> $row->id,
														'name'			=> $row->name,
														'description'	=> $row->description,
														'status'		=> $row->status_id ) );
			$entries[] = $entry;
		}
		
		return $entries;
	}
	
	
	/**
	 * Delete a task
	 * @see application/models/Application_Model_AbstractMapper::delete()
	 * @param int | Application_Model_Task $task
	 * @return void
	 */
	public function delete( $task )
	{
		if( null === ( $id = $task->getId() ) )
			throw new Exception( 'Object ID not set !' );
		
		unset( $this->_loadedMap[$id] );
		$this->getDbTable()->delete( array( 'id = ?' => $id ) );
	}
} 
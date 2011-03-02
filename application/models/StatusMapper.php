<?php
class Application_Model_StatusMapper extends Application_Model_AbstractMapper
{
	public function getDbTable()
	{
		if( null === $this->_dbTable )
		$this->setDbTable('Application_Model_Db_Table_Status');

		return $this->_dbTable;
	}


	/**
	 * 
	 * @see Application_Model_AbstractMapper::save()
	 * @param Application_Model_Status $status
	 */
	public function save( Application_Model_AbstractModel $status )
	{
		$data = array(	'name'			=> $status->getName(),
						'description'	=> $status->getDescription() );

		if( null === ( $id = $status->getId() ) )
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
		$data = array( 	'id'			=> $row->id,
			 			'name'			=> $row->name,
			 			'description'	=> $row->description );
			
		$this->_loadedMap[$id] = new Application_Model_Status( $data );
		
		return $this->_loadedMap[$id];
	}


	public function fetchAll( $where = null, $order = null, $count = null, $offset = null )
	{
		$resultSet = $this->getDbTable()->fetchAll( $where, $order, $count, $offset );
		$entries = array();
		foreach( $resultSet as $row )
		{
			$entry = new Application_Model_Status( array(	'id'			=> $row->id,
															'name'			=> $row->name,
															'description'	=> $row->description ) );
			$entries[] = $entry;
		}

		return $entries;
	}
	
	
	/**
	 * 
	 * @see Application_Model_AbstractMapper::delete()
	 * @param int | Application_Model_Status $status
	 * @return void
	 */
	public function delete( $status )
	{
		if( $status instanceof Application_Model_Status )	
			if( null === ( $id = $status->getId( ) ) )
				throw new Exception( 'Object ID not set' );
		else
			$id = $status;
		
		unset( $this->_loadedMap[$id] );
		$this->getDbTable()->delete( array( 'id = ?' => $id ) );
	}
}
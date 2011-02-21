<?php
class Application_Model_SprintMapper extends Application_Model_AbstractMapper
{
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
	public function save( $sprint )
	{
		$data = array(	'name'			=> $sprint->getName(),
						'description'	=> $sprint->getDescription(),
						'release'		=> $sprint->getRelease(),
						'status'		=> $sprint->getStatus(),
						'stories'		=> $sprint->getStories() );

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
		if( 0 === $result->count() )
			return null;
			
		$row = $rowset->current();
		$stories = $row->findStoryViaStoryTask();
		$data = array( 	'id'			=> $row->id,
			 			'name'			=> $row->name,
			 			'description'	=> $row->description,
			 			'release'		=> $row->release,
			 			'status'		=>	$row->status );
			
		$this->_loadedMap[$id] = new Application_Model_Sprint( $data );
		
		return $this->_loadedMap[$id];
	}


	public function fetchAll()
	{
		$resultSet = $this->getDbTable()->fetchAll();
		$entries = array();
		foreach( $resultSet as $row )
		{
			$entry = new Application_Model_Sprint( array(	'id'			=> $row->id,
															'name'			=> $row->name,
															'description'	=> $row->description,
															'status'		=> $row->status ) );
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
	public function delete( $sprint )
	{
		if( $sprint instanceof Application_Model_Sprint )	
			if( null === ( $id = $sprint->getId( ) ) )
				throw new Exception( 'Object ID not set' );
		else
			$id = $sprint;
		
		unset( $this->_loadedMap[$id] );
		$this->getDbTable()->delete( array( 'id = ?' => $id ) );
	}
}
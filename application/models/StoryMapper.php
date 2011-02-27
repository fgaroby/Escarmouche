<?php
require_once( dirname( __FILE__ ) . '/Db/Table/Status.php' );

class Application_Model_StoryMapper extends Application_Model_AbstractMapper
{	
    public function getDbTable()
    {
    	if( null === $this->_dbTable )
    		$this->setDbTable('Application_Model_Db_Table_Story');

    	return $this->_dbTable;
    }
    
    
    /**
     * 
     * @see Application_Model_AbstractMapper::save()
     */
	public function save( $story )
	{
		$data = array(	'name'			=> $story->getName(),
						'description'	=> $story->getDescription(),
						'status'		=> $story->getStatus(),
						'sprint'		=> $story->getSprintId(),
						'feature'		=> $story->getFeatureId(),
						'priority'		=> $story->getPriority(),
						'points'		=> $story->getPoints() );
		
		if( null === ( $id = $story->getId() ) )
		{
			unset( $data['id'] );
			$this->getDbTable()->insert( $data );
		}
		else
			$this->getDbTable()->update( $data, array( 'id = ?' => $id ) );
	}
	
	
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
			 			'status'		=> $row->status,
			 			'sprint'		=> $row->sprint,
						'feature'		=> $row->feature,
						'priority'		=> $row->priority,
						'points'		=> $row->points );
			
		$this->_loadedMap[$id] = new Application_Model_Story( $data );
		
		return $this->_loadedMap[$id];
	}
	
	
	public function fetchAll()
	{
		$resultSet = $this->getDbTable()->fetchAll();
		$entries = array();
		foreach( $resultSet as $row )
		{
			$entry = new Application_Model_Story( array(	'id'			=> $row->id,
															'name'			=> $row->name,
															'description'	=> $row->description,
															'status'		=> $row->status,
															'sprint'		=> $row->sprint,
															'feature'		=> $row->feature,
															'points'		=> $row->points ) );
			$entries[] = $entry;
		}
		
		return $entries;
	}
	
	
	/**
	 * 
	 * @see Application_Model_AbstractMapper::delete()
	 * @param int | Application_Model_Story $story
	 * @return void
	 */
	public function delete( $story )
	{
		if( !$story instanceof Application_Model_Story || null === ( $id = $story->getId( ) ) )
				throw new Exception( 'Object ID not set' );
		
		unset( $this->_loadedMap[$id] );
		$this->getDbTable()->delete( array( 'id = ?' => $id ) );
	}
} 
<?php
class Application_Model_ReleaseMapper extends Application_Model_AbstractMapper
{	
    public function getDbTable()
    {
    	if( null === $this->_dbTable )
    		$this->setDbTable('Application_Model_Db_Table_Release');

    	return $this->_dbTable;
    }
    
    
    /**
     * 
     * @see Application_Model_AbstractMapper::save()
     * @param Application_Model_Release $release
     * @return void
     */
	public function save( Application_Model_AbstractModel $release )
	{
		if( !$release instanceof Application_Model_Release )
			throw new InvalidArgumentException( "'\$release' must be instance of 'Application_Model_Release' !" );
			
		$data = array(	'name'			=> $release->getName(),
						'description'	=> $release->getDescription(),
						'status'		=> $release->getStatus(),
						'product'		=> $release->getProduct(),
						'startDate'		=> $release->getStartDate(),
						'endDate'		=> $release->getEndDate(),
						'duration'		=> $release->getDuration() );
		
		if( null === ( $id = $release->getId() ) )
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
		$release = new Application_Model_Release( $row );
		
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
			$entry = new Application_Model_Release( $row );
			$entries[] = $entry;
		}
		
		return $entries;
	}
	
	
	/**
	 * 
	 * @see Application_Model_AbstractMapper::delete()
	 * @param int | Application_Model_Release $release
	 */
	public function delete( $release )
	{
		if( $release instanceof Application_Model_Story )	
			if( null === ( $id = $release->getId( ) ) )
				throw new Exception( 'Object ID not set' );
		else
			$id = $release;
		
		unset( $this->_loadedMap[$id] );
		$this->getDbTable()->delete( array( 'id = ?' => $id ) );
	}
} 
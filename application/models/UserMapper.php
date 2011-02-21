<?php
require_once( dirname( __FILE__ ) . '/AbstractMapper.php' );

class Application_Model_UserMapper extends Application_Model_AbstractMapper
{
	public function getDbTable()
	{
    	if( null === $this->_dbTable )
    		$this->setDbTable('Application_Model_Db_Table_User' );

    	return $this->_dbTable;
	}
	

	public function save( $model )
	{
		
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
			 			'name'			=> $row->name );
		$this->_loadedMap[$id] = new Application_Model_User( $data );
		
		return $this->_loadedMap[$id];
	}

	
	public function fetchAll()
	{
		
	}

	
	public function delete( $model )
	{
		
	}
}
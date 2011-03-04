<?php
class Application_Model_UserMapper extends Application_Model_AbstractMapper
{
	protected static $_instance = null;
	
	
	
	public function getDbTable()
	{
    	if( null === $this->_dbTable )
    		$this->setDbTable('Application_Model_Db_Table_User' );

    	return $this->_dbTable;
	}
	

	public function save( Application_Model_AbstractModel $model )
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

	
	public function fetchAll( $where = null, $order = null, $count = null, $offset = null )
	{
		$resultSet = $this->getDbTable()->fetchAll( $where, $order, $count, $offset );
		$entries = array();
		foreach( $resultSet as $row )
		{
			$entry = new Application_Model_User( $row );
			$entries[] = $entry;
		}
		
		return $entries;
	}

	
	public function delete( Application_Model_AbstractModel $model )
	{
		if( !$model instanceof Application_Model_User )
			return false;

		return $this->getDbTable()->delete( 'id = ? ' . $model->getId() );
	}
	
	
	public static function getInstance()
	{
		if( null === self::$_instance )
			self::$_instance = new Application_Model_UserMapper();
			
		return self::$_instance;
	}
}
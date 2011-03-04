<?php
class Application_Model_FeatureMapper extends Application_Model_AbstractMapper
{
	protected static $_instance;



	public function getDbTable()
	{
		if( null === $this->_dbTable )
		$this->setDbTable('Application_Model_Db_Table_Feature' );

		return $this->_dbTable;
	}


	/**
	* 
	* @see application/models/Application_Model_AbstractMapper::save()
	* @param Application_Model_Feature $feature
	* @return void
	*/
	public function save( Application_Model_AbstractModel $feature )
	{
		if( !$feature instanceof Application_Model_Feature )
			throw new InvalidArgumentException( "'\$feature' must be instance of 'Application_Model_Feature' !" );

		$data = array(	'name'			=> $feature->getName(),
						'description'	=> $feature->getDescription(),
						'status'		=> $feature->getStatus(),
						'color'			=> $feature->getColor(),
						'release'		=> $feature->getReleaseId() );

		if( null === ( $id = $feature->getId() ) )
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

		$this->_loadedMap[$id] = new Application_Model_Feature( $rowset->current() );

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
			$entry = new Application_Model_Feature( $row );
			$entries[] = $entry;
		}

		return $entries;
	}


	/**
	* 
	* @see Application_Model_AbstractMapper::delete()
	* @param int | Application_Model_Feature $feature
	*/
	public function delete( $feature )
	{
		if( null === ( $id = $feature->getId() ) )
			throw new Exception( 'Object ID not set !' );

		unset( $this->_loadedMap[$id] );
		$this->getDbTable()->delete( array( 'id = ?' => $id ) );
	}


	public static function getInstance()
	{
		if( null === self::$_instance )
			self::$_instance = new Application_Model_FeatureMapper();

		return self::$_instance;
	}
}
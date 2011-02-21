<?php
abstract class Application_Model_AbstractMapper
{
	/**
	 * @var Zend_Db_Table_Abstract Storage
	 */
	protected $_dbTable = null;
	
	
	/**
	 * Caches the ever loaded data.
	 * @var array Identity map
	 */
	protected $_loadedMap;

	
	public abstract function getDbTable();
	

	public abstract function save( $model );

	
	public abstract function find( $id );

	
	public abstract function fetchAll();

	
	public abstract function delete( $model );

	
	public function setDbTable( $dbTable )
	{
		if( is_string( $dbTable ) )
		$dbTable = new $dbTable();
		 
		if( !$dbTable instanceof Zend_Db_Table_Abstract )
		throw new Exception('Invalid table data gateway provided');

		$this->_dbTable = $dbTable;

		return $this;
	}
}
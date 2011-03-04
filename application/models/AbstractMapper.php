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
	

	public abstract function save( Application_Model_AbstractModel $model );

	
	public abstract function find( $id );

	
	public abstract function fetchAll( $where = null, $order = null, $count = null, $offset = null );

	
	public abstract function delete( Application_Model_AbstractModel $model );

	
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
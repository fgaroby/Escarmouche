<?php
/**
 *  Application_Model_AbstractMapper
 *  
 *  LICENSE
 *  
 *  Copyright (C) 2011  windu.2b
 *  
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as
 *  published by the Free Software Foundation, either version 3 of the
 *  License, or (at your option) any later version.
 *  
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *  
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *  
 *  @author windu.2b
 *  @license AGPL v3
 *  @since 0.1
 */

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
<?php
class Application_Model_ProductMapper extends Application_Model_AbstractMapper
{
    public function getDbTable()
    {
    	if( null === $this->_dbTable )
    		$this->setDbTable('Application_Model_Db_Table_Product' );

    	return $this->_dbTable;
    }
    
    
    /**
     * 
     * @see application/models/Application_Model_AbstractMapper::save()
     * @param Application_Model_Product $product
     * @return void
     */
    public function save( $product )
    {
    	if( !$product instanceof Application_Model_Product )
			throw new InvalidArgumentException( "'\$product' must be instance of 'Application_Model_Product' !" );
			
		$data = array(	'name'				=> $product->getName(),
						'description'		=> $product->getDescription(),
						'scrumMaster_id'	=> $product->getScrumMaster(),
						'productOwner_id'	=> $product->getProductOwner() );
		
		if( null === ( $id = $product->getId() ) )
		{
			unset( $data['id'] );
			$id = $this->getDbTable()->insert( $data );
			if( $id !== null )
				$product->setId( $id );
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
		$data = array(	'id'			=> $row->id,
			 			'name'			=> $row->name,
						'description'	=> $row->description,
						'scrumMaster'	=> $row->scrumMaster,
						'productOwner'	=> $row->productOwner );
			
		$this->_loadedMap[$id] = new Application_Model_Product( $data );
		
		return $this->_loadedMap[$id];
	}
	
	
	/**
	 * 
	 * @see Application_Model_AbstractMapper::fetchAll()
	 */
	public function fetchAll()
	{
		$resultSet = $this->getDbTable()->fetchAll();
		$entries = array();
		foreach( $resultSet as $row )
		{
			$entry = new Application_Model_Product( array(	'id'			=> $row->id,
															'name'			=> $row->name,
															'description'	=> $row->description,
															'scrumMaster'	=> $row->scrumMaster_id,
															'productOwner'	=> $row->productOwner_id ) );
			$entries[] = $entry;
		}
		
		return $entries;
	}
	
	
	/**
	 * 
	 * @see Application_Model_AbstractMapper::delete()
	 * @param int | Application_Model_Product $product
	 */
	public function delete( $product )
	{
		if( null === ( $id = $product->getId() ) )
			throw new Exception( 'Object ID not set !' );
		
		unset( $this->_loadedMap[$id] );
		$this->getDbTable()->delete( array( 'id = ?' => $id ) );
	}
}
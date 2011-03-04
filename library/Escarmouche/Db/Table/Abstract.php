<?php


abstract class Escarmouche_Db_Table_Abstract extends Zend_Db_Table_Abstract
{
	protected $_primary = 'id';
	
	
	protected $_rowClass = 'Escarmouche_Db_Table_Row_Abstract';
	
	
	public function update( array $data, $where )
	{
		if( is_array( $where )
			&& isset( $where['id = ?'] )
			&& $where['id = ?'] instanceof Application_Model_AbstractModel )
			$where['id = ?'] = $where['id = ?']->getId();
			
		return parent::update( $data, $where );
	}
}
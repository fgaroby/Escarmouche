<?php
class Application_Model_Db_Table_Release extends Zend_Db_Table_Abstract
{
	/**
	 *
	 * Table name
	 * @var string
	 */
	protected $_name = 'release';
	
	
	protected $_referenceMap = array(	'Project'	=> array(	'columns'		=> 'product_id',
																'refTableClass'	=> 'Product',
																'refColumns'	=> array( 'id' ) ),
										'Feature'	=> array(	'columns'		=>'id',
																'refTableClass'	=> 'Application_Model_Db_Table_Feature',
																'refColumns'	=> array( 'release_id' ) ) );
}
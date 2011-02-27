<?php
class Application_Model_Db_Table_Release extends Escarmouche_Db_Table_Abstract
{
	/**
	 *
	 * Table name
	 * @var string
	 */
	protected $_name = 'release';
	
	
	protected $_referenceMap = array(	'Product'	=> array(	'columns'		=> 'product',
																'refTableClass'	=> 'Application_Model_Db_Table_Product',
																'refColumns'	=> array( 'id' ) ),
										'Feature'	=> array(	'columns'		=> 'id',
																'refTableClass'	=> 'Application_Model_Db_Table_Feature',
																'refColumns'	=> array( 'feature' ) ),
										'Sprint'	=> array(	'columns'		=> 'sprint',
																'refTableClass'	=> 'Application_Model_Db_Table_Sprint',
																'refColumns'	=> array( 'id' ) ) );
}
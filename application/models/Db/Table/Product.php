<?php
class Application_Model_Db_Table_Product extends Escarmouche_Db_Table_Abstract
{
	/**
	 *
	 * Table name
	 * @var string
	 */
	protected $_name = 'product';
	
	
	protected $_referenceMap = array(	'Creator'		=> array(	'columns'		=> 'creator',
																	'refTableClass'	=> 'User',
																	'refColumns'	=> array( 'id' ) ),
										'ProductOwner'	=> array(	'columns'		=> 'productOwner',
																	'refTableClass'	=> 'User',
																	'refColumns'	=> array( 'id' ) ),
										'ScrumMaster'	=> array(	'columns'		=> 'scrumMaster',
																	'refTableClass'	=> 'User',
																	'refColumns'	=> array( 'id' ) ) );
}
<?php
abstract class Escarmouche_Db_Table_Abstract extends Zend_Db_Table_Abstract
{
	protected $_primary = 'id';
	
	
	protected $_rowClass = 'Escarmouche_Db_Table_Row_Abstract';
}
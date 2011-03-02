<?php
class Escarmouche_Db_Table_Row_Abstract extends Zend_Db_Table_Row
{
	public function __get( $columnName )
	{
		$m = $this->_table->info( 'metadata' );
		
		if( isset( $m[$columnName] ) )
		{
			$value = $this->_data[$columnName];
			switch( strtoupper( $m[$columnName]['DATA_TYPE'] ) )
			{
				case 'DATE' :
					if( $value && ( $value != '0000-00-00' ) )
					{
						$date = new Zend_Date( $value, Zend_Date::ISO_8601 );
						$date->set( '00:00:00', Zend_Date::TIMES );
						
						return $date;
					}
					else
						return null;
				break;

				case 'DATETIME' :
					if( $value && ( $value != '0000-00-00 00:00:00' ) )
						return new Zend_Date( $value );
					else
						return null;
				break;

				case 'INT' :
				case 'INTEGER' :
					return intval( $value, 10 );
				break;
				
				default :
					return parent::__get( $columnName );
			}
		}
		else
			return parent::__get( $columnName );
	}
}
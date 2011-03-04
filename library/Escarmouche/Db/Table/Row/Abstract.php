<?php
/**
 *  Escarmouche_Db_Table_Row_Abstract
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
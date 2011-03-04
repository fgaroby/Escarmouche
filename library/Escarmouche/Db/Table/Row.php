<?php
/**
 *  Escarmouche_Db_Table_Row
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

/**
 * Enregistrement (Row) de base de données
 */
class Escarmouche_Db_Table_Row extends Zend_Db_Table_Row_Abstract
{
	/**
	 * Activation / désactivation de l'autosauvegarde à la destruction
	 */
	private static $_autoSave = true;


	/*protected function _insert()
	{
		if( ! $this->_data['id'] )
			$this->_data['id'] = $this->_uuid();
		$log = Zend_Registry::get( 'log' );
		$log->info( Zend_Debug::dump( $this->_data, "INSERT: $this->_tableClass", false ) );
	}*/


	/**
	 * Manipulation de l'autosauvegarde
	 */
	public static function setAutoSave( $save )
	{
		self::$_autoSave = ( bool ) $save;
	}


	/**
	 * Appelé à la désérialisation de l'objet
	 * Reconnecte automatiquement l'objet à sa table
	 */
	public function __wakeup()
	{
		$this->setTable( new $this->_tableClass() );
	}
}
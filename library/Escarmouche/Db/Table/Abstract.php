<?php
/**
 *  Escarmouche_Db_Table_Abstract
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
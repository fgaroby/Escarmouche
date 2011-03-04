<?php
/**
 *  Application_Model_Db_Table_Release
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
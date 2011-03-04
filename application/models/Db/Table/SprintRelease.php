<?php
/**
 *  Application_Model_Db_Table_SprintRelease
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

class Application_Model_Db_Table_SprintRelease extends Escarmouche_Db_Table_Abstract
{
	/**
	 *
	 * Table name
	 * @var string
	 */
	protected $_name = 'sprint_release';
	
	protected $_primary = array( 'sprint', 'task' );
	
	
	protected $_referenceMap = array(	'Task'	=> array(	'columns'		=> array( 'task' ),
															'refTableClass'	=> 'Application_Model_Db_Table_Task',
															'refColumns'	=> array( 'id' ) ),
										'Story'	=> array(	'columns'		=> array( 'story' ),
															'refTableClass'	=> 'Application_Model_Db_Table_Story',
															'refColumns'	=> array( 'id' ) ) );
}
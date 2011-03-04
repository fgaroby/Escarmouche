<?php
/**
 *  Escarmouche_Controller
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
class Escarmouche_Controller extends Zend_Controller_Action
{


	public function __call( $method, $args )
	{
		if( 'Action' == substr( $method, - 6 ) )
		{
			// If the action method was not found, render the error
			// template
			return $this->render( 'error' );
		}
		// all other methods throw an exception
		


		throw new Exception( 'Invalid method "' . $method . '" called', 500 );
	}
}
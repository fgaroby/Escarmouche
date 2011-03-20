<?php
/**
 *  Escarmouche_Form_Auth
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
 * Form to authenticate
 * 
 * @package escarmouche
 * @package form
 */
class Escarmouche_Form_Auth extends Zend_Form
{
	public function init()
	{
		$username = new Zend_Form_Element_Text( 'username' );
		$username->setLabel( 'Username:' )
				 ->setRequired( true )
				 ->addFilter( new Escarmouche_Filter_StripSlashes() )
				 ->addValidator( new Zend_Validate_StringLength( 0, 75 ) )
				 ->setDecorators( array( 'ViewHelper', 'Errors', 'Label' ) )
				 ->setValue( 'Username' );
		$this->addElement( $username );
		
		
		$password = new Zend_Form_Element_Password( 'password' );
		$password->setLabel( 'Password:' )
				 ->setRequired( true )
			 	 ->setDecorators( array( 'ViewHelper', 'Errors', 'Label', array( 'HtmlTag', array( 'tag' => 'p') ) ) );
		$this->addElement( $password );
		
		
		$submitButton = new Zend_Form_Element_Submit( 'submit_auth', array( 'title' => 'submit_auth' ) );
		$submitButton->setLabel( "Valider" )
					 ->setValue( "Valider" )
					 ->setAttrib( 'style', 'margin-left: 80px' )
					 ->setDecorators( array( 'ViewHelper' ) );
		$this->addElement( $submitButton );
	}
}
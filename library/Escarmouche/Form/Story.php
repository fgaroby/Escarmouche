<?php
/**
 *  Escarmouche_Form_Story
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
 * Form to create/update a story
 * 
 * @package escarmouche
 * @package form
 */
class Escarmouche_Form_Story extends Zend_Form
{
	/**
	 * Initialisation du formulaire (méthode obligatoire)
	 *
	 * @return Zend_Form
	 */
	public function init()
	{
		// Champ hidden "id" (contenant l'identifiant du produit, dans le cas d'une modification)
		$id = new Zend_Form_Element_Hidden( 'id' );
		$id->addValidator( new Zend_Validate_Int() )
		   ->setDecorators( array( 'ViewHelper', 'Errors', 'Label' ) );
		$this->addElement( $id );
		
		$name = new Zend_Form_Element_Text( 'name' );
		$name->addFilter( new Escarmouche_Filter_StripSlashes() )
			 ->addValidator( new Zend_Validate_StringLength( 0, 75 ) )
			 ->setLabel( "Nom :" )
			 ->setRequired( true )
			 ->setDecorators( array( 'ViewHelper', 'Errors', 'Label', array( 'HtmlTag', array( 'tag' => 'p') ) ) );
		$this->addElement( $name );
		
		$desc = new Zend_Form_Element_Textarea( 'description' );
		$desc->addFilter( new Escarmouche_Filter_StripSlashes() )
			 ->setLabel( "Description :" )
			 ->setRequired( false )
			 ->setAttrib( 'rows', 5 )
			 ->setAttrib( 'cols', 40 )
			 ->setDecorators( array( 'ViewHelper', 'Errors', 'Label', array( 'HtmlTag', array( 'tag' => 'p') ) ) );
		$this->addElement( $desc );
		
		// The type of the task : Story, Technical, ...
		$type = new Zend_Form_Element_Select( 'type' );
		$type->setLabel( "Type :" )
			 ->setRequired( true )
			 ->addValidator( new Zend_Validate_Int() )
			 ->setDecorators( array( 'ViewHelper', 'Errors', 'Label', array( 'HtmlTag', array( 'tag' => 'p') ) ) );
		$types = Application_Model_TypeMapper::getInstance()->fetchAll();
		foreach( $types as $t )
			$type->addMultiOption( $t->getId(), $t->getName() );
		$this->addElement( $type );
		
		// The feature to which is assigned the story
		$features = Application_Model_FeatureMapper::getInstance()->fetchAll();
		$feature = new Zend_Form_Element_Select( 'feature' );
		$feature->setLabel ('Feature : ' )
				->setRequired( true )
				->addValidator( new Zend_Validate_Int() )
				->setDecorators( array( 'ViewHelper', 'Errors', 'Label', array( 'HtmlTag', array( 'tag' => 'p') ) ) );
		foreach( $features as $f )
			$feature->addMultiOption( $f->getId(), $f->getName() );
		$this->addElement( $feature );
		
		// The hidden referrer
		$referrer = new Zend_Form_Element_Hidden( 'referrer' );
		$referrer->setDecorators( array( 'ViewHelper' ) );
		$this->addElement( $referrer );
		
		
		$resetButton = new Zend_Form_Element_Reset( 'reset_story', array( 'name' => 'reset_story') );
		$resetButton->setLabel( "Annuler" )
					->setValue( "Annuler" )
					 ->setAttrib( 'style', 'margin-left: 80px' )
					->setDecorators( array( 'ViewHelper' ) );
		$this->addElement( $resetButton );
		
		$submitButton = new Zend_Form_Element_Submit( 'submit_story', array( 'name' => 'submit_story' ) );
		$submitButton->setLabel( "Valider" )
					 ->setValue( "Valider" )
					 ->setAttrib( 'style', 'margin-left: 80px' )
					 ->setDecorators( array( 'ViewHelper' ) );
		$this->addElement( $submitButton );
		
		// jeton
		$token = new Zend_Form_Element_Hash( 'token', array( 'salt' => 'unique' ) );
		
		// Fieldsets
		$this->addDisplayGroup( array( 'id', 'name', 'description', 'type' ), 'base', array( 'legend' => 'Données de base' ) )
			 ->addDisplayGroup( array( 'feature' ), 'attachment', array( 'legend' => 'Attachment' ) )
			 ->addDisplayGroup( array( 'referrer', 'reset_story', 'submit_story' ), 'validation', array( 'legend' => 'Validation' ) );
	}
	
	
	public function getValues( $suppressArrayNotation = false )
	{
		$values = parent::getValues( $suppressArrayNotation );
		foreach( $values as $key => $value )
		{
			if( in_array( 'Zend_Validate_Int', array_keys( $this->_elements[$key]->getValidators() ) ) )
				$values[$key] = ( int ) $value;
		}
		
		return $values;
	}
}
<?php
/**
 * Formulaire de création/modification d'un produit
 * 
 * @package escarmouche
 * @package form
 */
class Escarmouche_Form_Product extends Zend_Form
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
		$id->addValidator( new Zend_Validate_Uuid() );
		$id->setDecorators( array( 'ViewHelper', 'Label' ) );
		$this->addElement( $id );
		
		$name = new Zend_Form_Element_Text( 'name' );
		$name->addFilter( new Escarmouche_Filter_StripSlashes() );
		$name->addValidator( new Zend_Validate_StringLength( 0, 75 ) );
		$name->setLabel( "Nom :" );
		$name->setRequired( true );
		$name->setDecorators( array( 'ViewHelper', 'Label', array( 'HtmlTag', array( 'tag' => 'p') ) ) );
		$this->addElement( $name );
		
		$desc = new Zend_Form_Element_Textarea( 'description' );
		$desc->addFilter( new Escarmouche_Filter_StripSlashes() );
		$desc->setLabel( "Description :" );
		$desc->setRequired( false );
		$desc->setAttrib( 'rows', 5 );
		$desc->setAttrib( 'cols', 40 );
		$desc->setDecorators( array( 'ViewHelper', 'Label', array( 'HtmlTag', array( 'tag' => 'p') ) ) );
		$this->addElement( $desc );
		
		
		
		// Boutons d'annulation/validation
		$resetButton = new Zend_Form_Element_Reset( 'reset_product', array( 'name' => 'reset_product') );
		$resetButton->setLabel( "Annuler" );
		$resetButton->setValue( "Annuler" );
		$resetButton->style = 'margin-left: 80px';
		$resetButton->setDecorators( array( 'ViewHelper' ) );
		$this->addElement( $resetButton );
		
		$submitButton = new Zend_Form_Element_Submit( 'submit_product', array( 'name' => 'submit_product' ) );
		$submitButton->setLabel( "Valider" );
		$submitButton->setValue( "Valider" );
		$submitButton->style = 'margin-left: 80px';
		$submitButton->setDecorators( array( 'ViewHelper' ) );
		$this->addElement( $submitButton );
		
		// jeton
		$token = new Zend_Form_Element_Hash( 'token', array( 'salt' => 'unique' ) );
		
		// Fieldsets
		$this->addDisplayGroup( array( 'id', 'name', 'description' ), 'base', array( 'legend' => 'Données de base' ) );
		$this->addDisplayGroup( array( 'reset_product', 'submit_product' ), 'validation', array( 'legend' => 'Validation' ) );
	}
}
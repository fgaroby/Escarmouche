<?php
/**
 * Formulaire de création/modification d'une catégorie
 * 
 * @package overlord
 * @package form
 */
class Overlord_Form_Unit extends Zend_Form
{


	/**
	 * Initialisation du formulaire (méthode obligatoire)
	 *
	 * @return Zend_Form
	 */
	public function init()
	{
		//Champ hidden "id"
		$id = new Zend_Form_Element_Hidden( 'id' );
		$id->addValidator( new Zend_Validate_Uuid() );
		$this->addElement( $id );
		
		// Champ texte "nom" (méthode simple avec setters)
		$name = new Zend_Form_Element_Text( 'name' );
		$name->addFilter( new Overlord_Filter_StripSlashes() );
		$name->addValidator( new Zend_Validate_StringLength( 0, 75 ) );
		$name->setLabel( "Nom :" );
		$name->setRequired( true );
		$this->addElement( $name );
		
		//Champ texte "unit"
		$unit = new Zend_Form_Element_Text( 'unit' );
		$unit->addFilter( new Overlord_Filter_StripSlashes() );
		$unit->setLabel( "Unité : " );
		$unit->setRequired( false );
		$this->addElement( $unit );
		
		// Bouton de validation
		$submitButton = new Zend_Form_Element_Submit( 'submit_product' );
		$submitButton->setLabel( "Valider" );
		$submitButton->setValue( "Valider" );
		$submitButton->style = 'margin-left: 80px';
		$this->addElement( $submitButton );
		
		// jeton
		$token = new Zend_Form_Element_Hash( 'token', array( 'salt' => 'unique' ) );
		$this->addElement( $token );
	}
}
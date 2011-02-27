<?php
/**
 * Formulaire de création/modification d'une release
 * 
 * @package escarmouche
 * @package form
 */
class Escarmouche_Form_Release extends Zend_Form
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
		$id->addValidator( new Zend_Validate_Uuid() )
		   ->setDecorators( array( 'ViewHelper', 'Label' ) );
		$this->addElement( $id );
		
		// Champ texte "name"
		$name = new Zend_Form_Element_Text( 'name' );
		$name->addFilter( new Escarmouche_Filter_StripSlashes() )
			 ->addValidator( new Zend_Validate_StringLength( 0, 75 ) )
			 ->setLabel( "Nom :" )
			 ->setRequired( true )
			 ->setDecorators( array( 'ViewHelper', 'Label', array( 'HtmlTag', array( 'tag' => 'p') ) ) );
		$this->addElement( $name );
		
		// Champ texte "poids" (méthode simple avec setters)
		$desc = new Zend_Form_Element_Textarea( 'description' );
		$desc->addFilter( new Escarmouche_Filter_StripSlashes() )
			 ->setLabel( "Description :" )
			 ->setRequired( false )
			 ->setAttribs( array( 'rows' => 5, 'cols' => 40 ) )
			 ->setDecorators( array( 'ViewHelper', 'Label', array( 'HtmlTag', array( 'tag' => 'p') ) ) );
		$this->addElement( $desc );
		
		$startDate = new Zend_Form_Element_Text( 'startDate' );
		$startDate->addFilter( new Escarmouche_Filter_StripSlashes() )
				  ->setLabel( 'Date de début :' )
				  ->setRequired( true )
				  ->setAttribs( array( 'size' => 10, 'maxlength' => 10 ) )
				  ->setDecorators( array( 'ViewHelper', 'Label', array( 'HtmlTag', array( 'tag' => 'p') ) ) );
		$this->addElement( $startDate );
		
		$endDate = new Zend_Form_Element_Text( 'endDate' );
		$endDate->addFilter( new Escarmouche_Filter_StripSlashes() )
				  ->setLabel( 'Date de fin :' )
				  ->setRequired( true )
				  ->setAttribs( array( 'size' => 10, 'maxlength' => 10 ) )
				  ->setDecorators( array( 'ViewHelper', 'Label', array( 'HtmlTag', array( 'tag' => 'p') ) ) );
		$this->addElement( $endDate );
		
		$duration = new Zend_Form_Element_Text( 'duration' );
		$duration->addFilter( new Escarmouche_Filter_StripSlashes() )
				  ->setLabel( 'Durée :' )
				  ->setRequired( true )
				  ->setAttribs( array( 'size' => 10, 'maxlength' => 10 ) )
				  ->setDecorators( array( 'ViewHelper', 'Label', array( 'HtmlTag', array( 'tag' => 'p') ) ) );
		$this->addElement( $duration );
		
		$sprintBox = new Zend_Form_Element_Radio( 'sprintRadio' );
		$sprintBox->setLabel( 'Créer les sprints ?' )
				  ->setMultiOptions( array( '1' => 'Oui', '0' => 'Non' ) )
				  ->setRequired( true )
				  ->setDecorators( array( 'ViewHelper', 'Label', array( 'HtmlTag', array( 'tag' => 'p') ) ) );
		$this->addElement( $sprintBox );
		
		// Boutons d'annulation/validation
		$resetButton = new Zend_Form_Element_Reset( 'reset_product', array( 'name' => 'reset_product') );
		$resetButton->setLabel( "Annuler" )
					->setValue( "Annuler" )
					->setAttrib( 'style', 'margin-left: 80px' )
					->setDecorators( array( 'ViewHelper', 'Label', array( 'HtmlTag', array( 'tag' => 'p') ) ) );
		$this->addElement( $resetButton );
		
		$submitButton = new Zend_Form_Element_Submit( 'submit_product', array( 'name' => 'submit_product' ) );
		$submitButton->setLabel( "Valider" )
					 ->setValue( "Valider" )
					 ->setAttrib( 'style', 'margin-left: 80px' )
					 ->setDecorators( array( 'ViewHelper', 'Label', array( 'HtmlTag', array( 'tag' => 'p') ) ) );
		$this->addElement( $submitButton );
		
		// token
		$token = new Zend_Form_Element_Hash( 'token', array( 'salt' => 'unique' ) );
		
		// Fieldsets
		$this->addDisplayGroup( array( 'id', 'name', 'description' ), 'base', array( 'legend' => 'Données de base' ) )
			 ->addDisplayGroup( array( 'startDate', 'endDate', 'duration' ), 'date', array( 'legend' => 'Dates' ) )
			 ->addDisplayGroup( array( 'sprintRadio' ), 'sprints', array( 'legend' => 'Sprints' ) )
			 ->addDisplayGroup( array( 'reset_product', 'submit_product' ), 'validation', array( 'legend' => 'Validation' ) );
	}
}
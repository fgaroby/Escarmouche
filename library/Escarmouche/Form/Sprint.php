<?php
/**
 * Formulaire de création/modification d'un produit
 * 
 * @package escarmouche
 * @package form
 */
class Escarmouche_Form_Sprint extends Zend_Form
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
		
		
		$release = new Zend_Form_Element_Select( 'release' );
		$release->setLabel( "Release :" )
				->setRequired( true )
				->addValidator( new Zend_Validate_Int() )
				->setDecorators( array( 'ViewHelper', 'Errors', 'Label', array( 'HtmlTag', array( 'tag' => 'p') ) ) )
				->addMultiOption( '', '-- Releases --' );
		$rm = new Application_Model_ReleaseMapper();
		$releases = $rm->fetchAll();
		foreach( $releases as $r )
			$release->addMultiOption( $r->getId(), $r->getName() );
		$this->addElement( $release );
		
		$startDate = new Zend_Form_Element_Text( 'startDate' );
		$startDate->addValidator( new Zend_Validate_Date( array( 'format' => 'dd/MM/YYYY' ) ) )
				  ->setLabel( 'Date de début :' )
				  ->setRequired( true )
				  ->setAttribs( array( 'size' => 10, 'maxlength' => 10 ) )
				  ->setDecorators( array( 'ViewHelper', 'Errors', 'Label', array( 'HtmlTag', array( 'tag' => 'p') ) ) );
		$this->addElement( $startDate );
		
		$endDate = new Zend_Form_Element_Text( 'endDate' );
		$endDate->addValidator( new Zend_Validate_Date( array( 'format' => 'dd/MM/YYYY' ) ) )
				->setLabel( 'Date de fin :' )
				->setRequired( true )
				->setAttribs( array( 'size' => 10, 'maxlength' => 10 ) )
				->setDecorators( array( 'ViewHelper', 'Errors', 'Label', array( 'HtmlTag', array( 'tag' => 'p') ) ) );
		$this->addElement( $endDate );
		
		// The hidden referrer
		$referrer = new Zend_Form_Element_Hidden( 'referrer' );
		$referrer->setDecorators( array( 'ViewHelper' ) );
		$this->addElement( $referrer );
		
		
		$resetButton = new Zend_Form_Element_Reset( 'reset_sprint', array( 'name' => 'reset_sprint') );
		$resetButton->setLabel( "Annuler" )
					->setValue( "Annuler" )
					 ->setAttrib( 'style', 'margin-left: 80px' )
					->setDecorators( array( 'ViewHelper' ) );
		$this->addElement( $resetButton );
		
		$submitButton = new Zend_Form_Element_Submit( 'submit_sprint', array( 'name' => 'submit_sprint' ) );
		$submitButton->setLabel( "Valider" )
					 ->setValue( "Valider" )
					 ->setAttrib( 'style', 'margin-left: 80px' )
					 ->setDecorators( array( 'ViewHelper' ) );
		$this->addElement( $submitButton );
		
		// jeton
		$token = new Zend_Form_Element_Hash( 'token', array( 'salt' => 'unique' ) );
		
		// Fieldsets
		$this->addDisplayGroup( array( 'id', 'name', 'description', 'type' ), 'base', array( 'legend' => 'Données de base' ) )
			 ->addDisplayGroup( array( 'release', 'startDate', 'endDate', 'duration' ), 'date', array( 'legend' => 'Dates' ) )
			 ->addDisplayGroup( array( 'referrer', 'reset_sprint', 'submit_sprint' ), 'validation', array( 'legend' => 'Validation' ) );
	}
}
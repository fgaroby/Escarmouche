<?php
/**
 * Form which allows to create/update a comment
 * 
 * @package escarmouche
 * @package form
 */
class Escarmouche_Form_Comment extends Zend_Form
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
		
		$title = new Zend_Form_Element_Text( 'title' );
		$title->addFilter( new Escarmouche_Filter_StripSlashes() )
			 ->addValidator( new Zend_Validate_StringLength( 0, 75 ) )
			 ->setLabel( "Titre :" )
			 ->setRequired( true )
			 ->setDecorators( array( 'ViewHelper', 'Errors', 'Label', array( 'HtmlTag', array( 'tag' => 'p') ) ) );
		$this->addElement( $title );
		
		$desc = new Zend_Form_Element_Textarea( 'comment' );
		$desc->addFilter( new Escarmouche_Filter_StripSlashes() )
			 ->setLabel( "Commentaire :" )
			 ->setRequired( true )
			 ->setAttrib( 'rows', 5 )
			 ->setAttrib( 'cols', 40 )
			 ->setDecorators( array( 'ViewHelper', 'Errors', 'Label', array( 'HtmlTag', array( 'tag' => 'p') ) ) );
		$this->addElement( $desc );
		
		// The hidden referrer
		$referrer = new Zend_Form_Element_Hidden( 'referrer' );
		$referrer->setDecorators( array( 'ViewHelper' ) );
		$this->addElement( $referrer );
		
		
		$resetButton = new Zend_Form_Element_Reset( 'reset_comment', array( 'title' => 'reset_comment') );
		$resetButton->setLabel( "Annuler" )
					->setValue( "Annuler" )
					 ->setAttrib( 'style', 'margin-left: 80px' )
					->setDecorators( array( 'ViewHelper' ) );
		$this->addElement( $resetButton );
		
		$submitButton = new Zend_Form_Element_Submit( 'submit_comment', array( 'title' => 'submit_comment' ) );
		$submitButton->setLabel( "Valider" )
					 ->setValue( "Valider" )
					 ->setAttrib( 'style', 'margin-left: 80px' )
					 ->setDecorators( array( 'ViewHelper' ) );
		$this->addElement( $submitButton );
		
		// jeton
		$token = new Zend_Form_Element_Hash( 'token', array( 'salt' => 'unique' ) );
		
		// Fieldsets
		$this->addDisplayGroup( array( 'id', 'title', 'comment', 'type' ), 'base', array( 'legend' => 'Données de base' ) )
			 ->addDisplayGroup( array( 'referrer', 'reset_comment', 'submit_comment' ), 'validation', array( 'legend' => 'Validation' ) );
	}
}
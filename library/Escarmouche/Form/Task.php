<?php
/**
 * Formulaire de création/modification d'un produit
 * 
 * @package escarmouche
 * @package form
 */
class Escarmouche_Form_Task extends Zend_Form
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
		$tm = new Application_Model_TypeMapper();
		$types = $tm->fetchAll( null, 'ORDER BY name ASC' );
		foreach( $types as $t )
			$type->addMultiOption( $t->getId(), $t->getName() );
		$this->addElement( $type );
		
		
		
		
		$resetButton = new Zend_Form_Element_Reset( 'reset_task', array( 'name' => 'reset_task') );
		$resetButton->setLabel( "Annuler" )
					->setValue( "Annuler" )
					 ->setAttrib( 'style', 'margin-left: 80px' )
					->setDecorators( array( 'ViewHelper' ) );
		$this->addElement( $resetButton );
		
		$submitButton = new Zend_Form_Element_Submit( 'submit_task', array( 'name' => 'submit_task' ) );
		$submitButton->setLabel( "Valider" )
					 ->setValue( "Valider" )
					 ->setAttrib( 'style', 'margin-left: 80px' )
					 ->setDecorators( array( 'ViewHelper' ) );
		$this->addElement( $submitButton );
		
		// jeton
		$token = new Zend_Form_Element_Hash( 'token', array( 'salt' => 'unique' ) );
		
		// Fieldsets
		$this->addDisplayGroup( array( 'id', 'name', 'description', 'type' ), 'base', array( 'legend' => 'Données de base' ) )
			 ->addDisplayGroup( array( 'reset_task', 'submit_task' ), 'validation', array( 'legend' => 'Validation' ) );
	}
}
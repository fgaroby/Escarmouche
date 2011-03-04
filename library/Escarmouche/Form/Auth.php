<?php
class Escarmouche_Form_Auth extends Zend_Form
{
	public function init()
	{
		$username = new Zend_Form_Element_Text( 'username' );
		$username->setLabel( 'Username:' )
				 ->setRequired( true )
				 ->addFilter( new Escarmouche_Filter_StripSlashes() )
				 ->addValidator( new Zend_Validate_StringLength( 0, 75 ) )
				 ->setDecorators( array( 'ViewHelper', 'Errors', 'Label' ) );
		$this->addElement($username);
		
		
		$password = new Zend_Form_Element_Password( 'password' );
		$password->setLabel( 'Password:' )
				 ->setRequired( true )
			 	 ->setDecorators( array( 'ViewHelper', 'Errors', 'Label', array( 'HtmlTag', array( 'tag' => 'p') ) ) );
		$this->addElement($password);
		
		
		$submitButton = new Zend_Form_Element_Submit( 'submit_auth', array( 'title' => 'submit_auth' ) );
		$submitButton->setLabel( "Valider" )
					 ->setValue( "Valider" )
					 ->setAttrib( 'style', 'margin-left: 80px' )
					 ->setDecorators( array( 'ViewHelper' ) );
		$this->addElement( $submitButton );
	}
}
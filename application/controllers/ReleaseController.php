<?php

/**
 * ReleaseController
 *
 * @author Francescu Garoby
 * @version 0.1
 */


class ReleaseController extends Zend_Controller_Action
{
	protected $_releaseMapper;
	
	
	public function init()
	{
		$this->view->setTitle( 'Releases' );
		$this->_releaseMapper = new Application_Model_ReleaseMapper();
	}

	
	public function indexAction()
	{
		$this->view->setTitle( 'Liste des releases de votre produit' );

		$this->view->releases = $this->_releaseMapper->fetchAll();
	}
	
	
	public function displayAction()
	{
		$params = $this->getRequest()->getParams();
		if( isset( $params['id'] ) )
		{
			$release = $this->_releaseMapper->find( $params['id' ] );
			$this->view->release = $release;
			$this->view->setTitle( $release->getName() );	
		}
		else
			$this->_redirect( $this->view->url( array( 'controller' => 'release', 'action' => 'index' ) ), array( 'prependBase' => false ) );
	}
	
	
	public function editAction()
	{
		$params = $this->getRequest()->getParams();
		$isUpdate = isset( $params['id'] ) && !empty( $params['id'] );
		if( $isUpdate )
		{
			$params['id'] = (int ) $params['id'];
			
			$release = $this->_releaseMapper->find( $params['id'] );
			if( $release === null )
			{
				$this->getRequest()->clearParams();
				$this->_redirect( $this->view->url( array( 'controller' => 'release', 'action' => 'edit', 'id' => null ) ), array( 'prependBase' => false, 'code' => 303 ) );
			}
		}
		else
		{
			$release = new Application_Model_Release( array( 'name' => 'default release' ) );
		}
		//Zend_Debug::dump($release);
		$form = new Escarmouche_Form_Release( array( 'update' => $isUpdate ) );
		$form->setAction( $this->view->link( 'release', 'edit', null, '', 'default', !$isUpdate ) )
			 ->setMethod( 'post' )
			 ->setDefaults( $release->toArray() );

		// if $isUpdate, don't show the sprints fieldset
		if( $isUpdate === true )
		{
			$form->removeElement( 'sprintRadio' );
			$form->removeDisplayGroup( 'sprints' );
		}
			 
		// Création des informations et ajout/suppression
		if( $this->getRequest()->isPost() && $form->isValid( $_POST ) )
		{
			$values = $form->getValues();
			// $values['creator'] = Zend_Auth::getInstance()->getIdentity()->id;
			$release->setFromArray( array_intersect_key($values, $release->toArray() ) );

			// Sauvegarde des informations
			$this->_releaseMapper->save( $release );
			
			$this->_helper->FlashMessenger( "Insertion du produit '{$release->getName()}' effectuée ! " );
			$this->_redirect( $this->view->url( array( 'controller' => 'release', 'action' => 'index' ) ), array( 'prependBase' => false ) );
		}
		
		$this->view->form = $form;
	}
}
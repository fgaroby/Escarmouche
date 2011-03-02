<?php
class SprintController extends Zend_Controller_Action
{
	protected $_sprintMapper;
	
	
	public function init()
	{
		$this->view->setTitle( 'Sprints' );
		$this->_sprintMapper = new Application_Model_SprintMapper();
	}
	
	
	public function indexAction()
	{
		$this->view->sprints = $this->_sprintMapper->fetchAll();
	}
	
	
	public function displayAction()
	{
		$params = $this->getRequest()->getParams();
		$sprint = $this->_sprintMapper->find( $params['id' ] );
		if( null !== $sprint )
		{
			$this->view->sprint = $sprint;
			$this->view->setTitle( $sprint->getName() );	
		}
		else
			$this->_redirect( $this->view->url( array( 'controller' => 'sprint', 'action' => 'index' ) ), array( 'prependBase' => false ) );
	}
	
	
	/**
	 * 
	 * Edit the sprint, or create one if $param['id'] is empty
	 */
	public function editAction()
	{
		$params = $this->getRequest()->getParams();
		$isUpdate = isset( $params['id'] ) && !empty( $params['id'] );
		if( $isUpdate )
		{
			$params['id'] = (int ) $params['id'];
			
			$sprint = $this->_sprintMapper->find( $params['id'] );
			if( $sprint === null )
			{
				$this->getRequest()->clearParams();
				$this->_redirect( $this->view->url( array( 'controller' => 'sprint', 'action' => 'edit', 'id' => null ) ), array( 'prependBase' => false, 'code' => 303 ) );
			}
		}
		else
		{
			$sprint = new Application_Model_Sprint( array( 'name' => 'default sprint' ) );
		}
		$form = new Escarmouche_Form_Sprint();
		$form->setAction( $this->view->link( 'sprint', 'edit', null, '', 'default', !$isUpdate ) )
			 ->setMethod( 'post' )
			 ->setDefaults( $sprint->toArray() );

		/*
		 *  if release has ever started, we can't change the start date anymore.
		 */
		if( Application_Model_Status::isStarted( $sprint->getStatus() ) )
		{
			$form->startDate->setAttrib( 'class', 'readonly' );
			$form->startDate->setAttrib( 'readonly', 'readonly' );
		}
		
		// if release has ever finished, we can't change the end date anymore.
		if( Application_Model_Status::isFinished( $sprint->getStatus() ) )
		{
			$form->endDate->setAttrib( 'class', 'readonly' );
			$form->endDate->setAttrib( 'readonly', 'readonly' );
		}
		
		// We define the referrer URL
		if( isset( $_SERVER['HTTP_REFERER'] ) && !empty( $_SERVER['HTTP_REFERER'] ) )
			$form->referrer->setValue( $_SERVER['HTTP_REFERER'] );
		else
			$form->referrer->setValue( $this->view->url( array( 'controller' => 'sprint', 'action' => 'index' ) ) );
			 
		if( isset( $params['release'] ) )
			$form->release->setValue( $params['release'] );
			
		
		// Création des informations et ajout/suppression
		if( $this->getRequest()->isPost() && $form->isValid( $_POST ) )
		{
			$values = $form->getValues();
			// $values['creator'] = Zend_Auth::getInstance()->getIdentity()->id;
			$sprint->setFromArray( array_intersect_key( $values, $sprint->toArray() ) );

			// We save (INSERT or UPDATE) the data
			$this->_sprintMapper->save( $sprint );
			
			$this->_helper->FlashMessenger( "Insertion du produit '{$sprint->getName()}' effectuée ! " );
			// redirect to the referrer page or to the default, if referrer is empty
			$this->_redirect( $form->referrer->getValue(), array( 'prependBase' => false ) );
		}
		else
		{
			$this->_helper->FlashMessenger( "L'insertion du produit '{$sprint->getName()}' a échoué ! " );
			$this->view->form = $form;
		}
	}
	
	
	public function deleteAction()
	{
		
	}
}
<?php

/**
 * TaskController
 *
 * @author
 * @version
 */
class TaskController extends Zend_Controller_Action
{
	protected $_taskMapper;
	
	
	public function init()
	{
		$this->view->setTitle( 'Tasks' );
		$this->_taskMapper = new Application_Model_TaskMapper();
	}

	
	/**
	 * The default action - show the home page
	 */
	public function indexAction()
	{
		// TODO Auto-generated TaskController::indexAction() default action
	}
	
	
	public function displayAction()
	{
		$params = $this->getRequest()->getParams();
		$task = $this->_taskMapper->find( $params['id' ] );
		if( null !== $task )
		{
			$this->view->task = $task;
			$this->view->setTitle( $task->getName() );	
		}
		else
			$this->_redirect( $this->view->url( array( 'controller' => 'task', 'action' => 'index' ) ), array( 'prependBase' => false ) );
	}
	
	
	// Édite et enregistre un nouveau produit
	public function editAction()
	{
		$params = $this->getRequest()->getParams();
		$isUpdate = isset( $params['id'] ) && !empty( $params['id'] );
		if( $isUpdate )
		{
			$params['id'] = (int ) $params['id'];
			
			$task = $this->_taskMapper->find( $params['id'] );
			if( $task === null )
			{
				$this->getRequest()->clearParams();
				$this->_redirect( $this->view->url( array( 'controller' => 'task', 'action' => 'edit', 'id' => null ) ), array( 'prependBase' => false, 'code' => 303 ) );
			}
		}
		else
		{
			$task = new Application_Model_Task( array( 'name' => 'default task' ) );
		}
		
		$form = new Escarmouche_Form_Task();
		$form->setAction( $this->view->link( 'task', 'edit', null, '', 'default', !$isUpdate ) )
			 ->setMethod( 'post' )
			 ->setDefaults( $task->toArray() );
			 
		// Création des informations et ajout/suppression
		if( $this->getRequest()->isPost() && $form->isValid( $_POST ) )
		{
			$values = $form->getValues();
			// $values['creator'] = Zend_Auth::getInstance()->getIdentity()->id;
			$task->setFromArray( array_intersect_key($values, $task->toArray() ) );

			// Sauvegarde des informations
			$this->_taskMapper->save( $task );
			
			$this->_helper->FlashMessenger( "Insertion du produit '{$task->getName()}' effectuée ! " );
			$this->_redirect( $this->view->url( array( 'controller' => 'task', 'action' => 'index' ) ), array( 'prependBase' => false ) );
		}
		
		$this->view->form = $form;
	}
	
	
	public function deleteAction()
	{
		
	}
	
	
	public function postDispatch()
	{
		$this->view->flashMessenger = $this->_helper->FlashMessenger->getMessages();
	}
}
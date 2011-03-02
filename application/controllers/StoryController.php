<?php

/**
 * StoryController
 *
 * @author
 * @version
 */
class StoryController extends Zend_Controller_Action
{
	protected $_storyMapper;
	
	
	public function init()
	{
		$this->view->setTitle( 'Stories' );
		$this->_storyMapper = new Application_Model_StoryMapper();
	}

	
	/**
	 * The default action - show the home page
	 */
	public function indexAction()
	{
		$this->view->stories = $this->_storyMapper->fetchAll();
	}
	
	
	public function displayAction()
	{
		$params = $this->getRequest()->getParams();
		$story = $this->_storyMapper->find( $params['id' ] );
		if( null !== $story )
		{
			$this->view->story = $story;
			$this->view->setTitle( $story->getName() );	
		}
		else
			$this->_redirect( $this->view->url( array( 'controller' => 'story', 'action' => 'index' ) ), array( 'prependBase' => false ) );
	}
	
	
	// Édite et enregistre un nouveau produit
	public function editAction()
	{
		$params = $this->getRequest()->getParams();
		$isUpdate = isset( $params['id'] ) && !empty( $params['id'] );
		if( $isUpdate )
		{
			$params['id'] = (int ) $params['id'];
			
			$story = $this->_storyMapper->find( $params['id'] );
			if( $story === null )
			{
				$this->getRequest()->clearParams();
				$this->_redirect( $this->view->url( array( 'controller' => 'story', 'action' => 'edit', 'id' => null ) ), array( 'prependBase' => false, 'code' => 303 ) );
			}
		}
		else
			$story = new Application_Model_Story( array( 'name' => 'default story' ) );
		
		$form = new Escarmouche_Form_Story();
		$form->setAction( $this->view->link( 'story', 'edit', null, '', 'default', !$isUpdate ) )
			 ->setMethod( 'post' )
			 ->populate( $story->toArray() );
			 
		// Création des informations et ajout/suppression
		if( $this->getRequest()->isPost() && $form->isValid( $_POST ) )
		{
			$values = $form->getValues();
			// $values['creator'] = Zend_Auth::getInstance()->getIdentity()->id;
			$story->setFromArray( array_intersect_key($values, $story->toArray() ) );

			// Sauvegarde des informations
			$this->_storyMapper->save( $story );
			
			$this->_helper->FlashMessenger( "Insertion du produit '{$story->getName()}' effectuée ! " );
			$this->_redirect( $this->view->url( array( 'controller' => 'story', 'action' => 'index' ) ), array( 'prependBase' => false ) );
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
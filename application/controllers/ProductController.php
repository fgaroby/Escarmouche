<?php
class ProductController extends Zend_Controller_Action
{
	protected $_productMapper;
	
	
	public function init()
	{
		$this->view->setTitle( 'Products' );
		$this->_productMapper = new Application_Model_ProductMapper();
	}

	
	public function indexAction()
	{
		$this->view->products = $this->_productMapper->fetchAll();
	}
	
	
	public function displayAction()
	{
		$params = $this->getRequest()->getParams();
		if( isset( $params['id'] ) )
		{
			$this->view->product = $this->_productMapper->find( $params['id' ] );
		}
		else
			$this->_redirect( $this->view->url( array( 'controller' => 'product', 'action' => 'index' ) ), array( 'prependBase' => false ) );
	}
	
	
	// Édite et enregistre un nouveau produit
	public function editAction()
	{
		$params = $this->getRequest()->getParams();
		$isUpdate = isset( $params['id'] ) && !empty( $params['id'] );
		if( $isUpdate )
		{
			$params['id'] = (int ) $params['id'];
			
			$product = $this->_productMapper->find( $params['id'] );
			if( $product === null )
			{
				$this->getRequest()->clearParams();
				$this->_redirect( $this->view->url( array( 'controller' => 'product', 'action' => 'edit', 'id' => null ) ), array( 'prependBase' => false, 'code' => 303 ) );
			}
		}
		else
		{
			$product = new Application_Model_Product( array( 'name' => 'default product' ) );
		}
		
		$form = new Escarmouche_Form_Product();
		$form->setAction( $this->view->link( 'product', 'edit', null, '', 'default', !$isUpdate ) )
			 ->setMethod( 'post' )
			 ->setDefaults( $product->toArray() );
			 
		// Création des informations et ajout/suppression
		if( $this->getRequest()->isPost() && $form->isValid( $_POST ) )
		{
			$values = $form->getValues();
			// $values['creator'] = Zend_Auth::getInstance()->getIdentity()->id;
			$product->setFromArray( array_intersect_key( $values, $product->toArray() ) );

			// Sauvegarde des informations
			$this->_productMapper->save( $product );
			
			$this->_helper->FlashMessenger( "Insertion du produit '{$product->getName()}' effectuée ! " );
			$this->_redirect( $this->view->url( array( 'controller' => 'product', 'action' => 'index' ) ), array( 'prependBase' => false ) );
		}
		else
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
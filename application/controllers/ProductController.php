<?php
/**
 *  ProductController
 *  
 *  LICENSE
 *  
 *  Copyright (C) 2011  windu.2b
 *  
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as
 *  published by the Free Software Foundation, either version 3 of the
 *  License, or (at your option) any later version.
 *  
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *  
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *  
 *  @author windu.2b
 *  @license AGPL v3
 *  @since 0.1
 */

class ProductController extends Escarmouche_Controller_Abstract
{
	protected $_productMapper;
	
	
	public function init()
	{
		parent::init();
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
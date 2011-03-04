<?php
/**
 *  CommentController
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
 *  
 */


class CommentController extends Escarmouche_Controller_Abstract
{
	protected $_commentMapper;
	
	
	public function init()
	{
		parent::init();
		$this->view->setTitle( 'Comments' );
		$this->_commentMapper = new Application_Model_CommentMapper();
	}
	
	
	public function indexAction()
	{
		$this->view->comments = $this->_commentMapper->fetchAll();
	}

	
	public function displayAction()
	{
		$params = $this->getRequest()->getParams();
		if( isset( $params['id'] ) )
		{
			$this->view->comment = $this->_commentMapper->find( $params['id' ] );
		}
		else
			$this->_redirect( $this->view->url( array( 'controller' => 'comment', 'action' => 'index' ) ), array( 'prependBase' => false ) );
	}
	
	
	public function editAction()
	{
		$params = $this->getRequest()->getParams();
		$isUpdate = isset( $params['id'] ) && !empty( $params['id'] );
		if( $isUpdate )
		{
			$params['id'] = (int ) $params['id'];
			
			$comment = $this->_commentMapper->find( $params['id'] );
			if( $comment === null )
			{
				$this->getRequest()->clearParams();
				$this->_redirect( $this->view->url( array( 'controller' => 'comment', 'action' => 'edit', 'id' => null ) ), array( 'prependBase' => false, 'code' => 303 ) );
			}
		}
		else
			$comment = new Application_Model_Comment( array( 'name' => 'default comment' ) );
		
		$form = new Escarmouche_Form_Comment();
		$form->setAction( $this->view->link( 'comment', 'edit', null, '', 'default', !$isUpdate ) )
			 ->setMethod( 'post' )
			 ->populate( $comment->toArray() );
			
		// We define the referrer URL
		if( isset( $_SERVER['HTTP_REFERER'] ) && !empty( $_SERVER['HTTP_REFERER'] ) )
			$form->referrer->setValue( $_SERVER['HTTP_REFERER'] );
		else
			$form->referrer->setValue( $this->view->url( array( 'controller' => 'comment', 'action' => 'index' ) ) );
			 
		// Création des informations et ajout/suppression
		if( $this->getRequest()->isPost() && $form->isValid( $_POST ) )
		{
			$values = $form->getValues();
			// $values['creator'] = Zend_Auth::getInstance()->getIdentity()->id;
			$comment->setFromArray( array_intersect_key( $values, $comment->toArray() ) );

			// Sauvegarde des informations
			try
			{
				$this->_commentMapper->save( $comment );
			}
			catch (Exception $e )
			{
				Zend_Debug::dump( $e );die();
			}
			
			$this->_helper->FlashMessenger( "Insertion du commentaire '{$comment->getName()}' effectuée ! " );
			
			// redirect to the referrer page or to the default, if referrer is empty
			$this->_redirect( $form->referrer->getValue(), array( 'prependBase' => false ) );
		}
		
		$this->view->form = $form;
	}
}
<?php
/**
 *  StoryController
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

class StoryController extends Escarmouche_Controller_Abstract
{
	protected $_storyMapper;
	
	
	public function init()
	{
		parent::init();
		$this->view->setTitle( 'Stories' );
		$this->_storyMapper = Application_Model_StoryMapper::getInstance();
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
			$this->_redirect(	$this->view->url(	array(	'controller'	=> 'story',
															'action'		=> 'index' ) ),
								array(	'prependBase' => false ) );
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
				$this->_redirect(	$this->view->url(	array(	'controller'	=> 'story',
																'action'		=> 'edit',
																'id'			=> null ) ),
									array( 'prependBase' => false ) );
			}
		}
		else
			$story = new Application_Model_Story( array( 'name' => 'default story' ) );
		
		$form = new Escarmouche_Form_Story();
		$form->setAction( $this->view->link( 'story', 'edit', null, '', 'default', !$isUpdate ) )
			 ->setMethod( 'post' )
			 ->populate( $story->toArray() );
			
		// We define the referrer URL
		if( isset( $_SERVER['HTTP_REFERER'] ) && !empty( $_SERVER['HTTP_REFERER'] ) )
			$form->referrer->setValue( $_SERVER['HTTP_REFERER'] );
		else
			$form->referrer->setValue( $this->view->url( array( 'controller' => 'story', 'action' => 'index' ) ) );
			 
		// Création des informations et ajout/suppression
		if( $this->getRequest()->isPost() && $form->isValid( $_POST ) )
		{
			$values = $form->getValues();
			// $values['creator'] = Zend_Auth::getInstance()->getIdentity()->id;
			$story->setFromArray( array_intersect_key( $values, $story->toArray() ) );

			// Sauvegarde des informations
			try
			{
				$this->_storyMapper->save( $story );
			}
			catch (Exception $e )
			{
				Zend_Debug::dump( $e );die();
			}
			
			$this->_helper->FlashMessenger( "Insertion de la story '{$story->getName()}' effectuée ! " );
			
			// redirect to the referrer page or to the default, if referrer is empty
			$this->_redirect(	$form->referrer->getValue(),
								array( 'prependBase' => false ) );
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
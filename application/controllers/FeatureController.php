<?php
/**
 *  FeatureController
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

class FeatureController extends Escarmouche_Controller_Abstract
{
	protected $_featureMapper;
	
	
	public function init()
	{
		parent::init();
		$this->view->setTitle( 'Features' );
		$this->_featureMapper = Application_Model_FeatureMapper::getInstance();
	}
	
	
	public function indexAction()
	{
		$this->view->features = $this->_featureMapper->fetchAll();
	}
	
	
	public function displayAction()
	{
		$params = $this->getRequest()->getParams();
		if( isset( $params['id'] ) )
		{
			$this->view->feature = $this->_featureMapper->find( $params['id' ] );
		}
		else
			$this->_redirect(	$this->view->url(	array(	'controller'=> 'feature',
															'action'	=> 'index' ),
													null,
													true ),
								array(	'prependBase' => false ) );
	}
	
	
	public function editAction()
	{
		$params = $this->getRequest()->getParams();
		$isUpdate = isset( $params['id'] ) && !empty( $params['id'] );
		if( $isUpdate )
		{
			$params['id'] = (int ) $params['id'];
			
			$feature = $this->_featureMapper->find( $params['id'] );
			if( $feature === null )
			{
				$this->getRequest()->clearParams();
				$this->_redirect( $this->view->url( array( 'controller' => 'feature', 'action' => 'edit', 'id' => null ) ), array( 'prependBase' => false, 'code' => 303 ) );
			}
		}
		else
		{
			$feature = new Application_Model_Feature( array( 'name' => 'default feature' ) );
		}
		
		$form = new Escarmouche_Form_Feature();
		$form->setAction( $this->view->link( 'feature', 'edit', null, '', 'default', !$isUpdate ) )
			 ->setMethod( 'post' )
			 ->setDefaults( $feature->toArray() );
			 
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
			$feature->setFromArray( array_intersect_key( $values, $feature->toArray() ) );

			// Sauvegarde des informations
			$this->_featureMapper->save( $feature );
			
			$this->_helper->FlashMessenger( "Insertion du produit '{$feature->getName()}' effectuée ! " );
			$this->_redirect( $form->referrer->getValue(), array( 'prependBase' => false ) );
		}
		else
			$this->view->form = $form;
	}
}
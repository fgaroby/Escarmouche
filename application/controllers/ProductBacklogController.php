<?php
/**
 *  ProductBacklogController
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

require_once 'Zend/Controller/Action.php';

class ProductBacklogController extends Escarmouche_Controller_Abstract
{
	public function init()
	{
		parent::init();
		$this->view->setTitle( 'Product Backlog' );
	}
	
	
	public function indexAction()
	{
		$pm = Application_Model_ProductMapper::getInstance();
		$selectProductsWithPOStatus = $pm->getDbTable()
										 ->select()
										 ->setIntegrityCheck( false )
										 ->from(	array(	'p'	=> 'product' ) )
										 ->join(	array(	'po'	=> 'productOwner' ),
													'po.product = p.id',
													null )
										 ->join(	array(	'u'	=> 'user' ),
													'u.id = po.user',
													null );
		$selectProductsWithSMStatus = $pm->getDbTable()
										 ->select()
										 ->setIntegrityCheck( false )
										 ->from(	array(	'p'	=> 'product' ) )
										 ->join(	array(	'sm'	=> 'scrumMaster' ),
													'sm.product = p.id',
													null )
										 ->join(	array(	'u'	=> 'user' ),
													'u.id = sm.user',
													null );
		$selectProductsWithTeamStatus = $pm->getDbTable()
										   ->select()
										   ->setIntegrityCheck( false )
										   ->from(	array(	'p'	=> 'product' ) )
										   ->join(	array(	't'	=> 'team' ),
													't.product = p.id',
													null )
										   ->join(	array(	'u'	=> 'user' ),
													'u.id = t.user',
													null );
		
		$this->view->products = $pm->fetchAll( $pm->getDbTable()
												 ->select()
												 ->union( array(	$selectProductsWithPOStatus,
																	$selectProductsWithSMStatus,
																	$selectProductsWithTeamStatus ) )
										 		 ->order( 'p.name ASC' ) );
	}

	
	public function displayAction()
	{
		$sm = Application_Model_StoryMapper::getInstance();
		$params = $this->getRequest()->getParams();
		if( isset( $params['id'] ) )
			$id = $params['id'];
		/*
		 * @todo gérer la sélection de l'ID du produit par défaut
		 */
		else
			$id = Zend_Registry::get( 'session' )->defaultProduct;
		
		$selectStories = $sm->getDbTable()
							->select()
							->setIntegrityCheck( false )
							->from(	array( 's' => 'story' ) )
							->join(	array( 'st'=> 'story_status'	),
									's.id = st.story',
									null )
							->join(	array( 'sta'	=> 'status'	),
									'sta.id = st.status',
									null )
							->join(	array( 'sp'	=> 'sprint' ),
						 			's.sprint = sp.id',
								  	null )
							->join(	array( 'r'	=> 'release' ),
						 			'sp.release = r.id',
									null )
						 	->join(	array( 'p'	=> 'product' ),
									'r.product = p.id',
									null )
						 	->where( 'p.id = ?', $id )
						 	->order( array(	'st.status ASC',
						 					's.id ASC' ) );
									 
		Zend_Debug::dump( $selectStories->__toString() );
		$this->view->stories = $sm->fetchAll( $selectStories );
	}
}
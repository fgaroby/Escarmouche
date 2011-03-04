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
		$this->_featureMapper = new Application_Model_FeatureMapper();
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
			$this->_redirect( $this->view->url( array( 'controller' => 'feature', 'action' => 'index' ) ), array( 'prependBase' => false ) );
	}
	
	
	public function editAction()
	{
		
	}
}
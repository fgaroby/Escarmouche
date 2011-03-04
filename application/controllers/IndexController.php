<?php
/**
 *  IndexController
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

class IndexController extends Escarmouche_Controller_Abstract
{

	public function init()
	{
		parent::init();
	}

	
	public function indexAction()
	{
		$this->view->setTitle( 'Accueil' );
	}
	
	
	public function displayAction()
	{
		$this->view->setTitle( 'Tableau de bord' );
	}
	
	
	public function infoAction()
	{
		if( $this->getInvokeArg( 'debug' ) == 1 )
		{
			$this->getResponse()->setHeader( 'Cache-control', 'no-cache' );
			$this->view->setTitle( 'Contenu de request et response' );
			$this->view->request = $this->getRequest();
			$this->view->response = $this->getResponse();
		}
	}
}
<?php

/**
 * FeatureController
 *
 * @author windu.2b
 * @version
 */

require_once 'Zend/Controller/Action.php';

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
<?php

/**
 * FeatureController
 *
 * @author
 * @version
 */

require_once 'Zend/Controller/Action.php';

class FeatureController extends Zend_Controller_Action
{
	protected $_featureMapper;
	
	
	public function init()
	{
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
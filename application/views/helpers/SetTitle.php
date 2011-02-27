<?php
/**
 *
 * @author windu
 * @version 
 */

/**
 * SetTitie helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_SetTitle extends Zend_View_Helper_Abstract
{
	public function setTitle( $titre )
	{
		$config = Zend_Registry::get( 'config' )->application;
		$this->view->headTitle = $this->view->translate( $titre )
						. $config->separator
						. ' '
						. $config->name
						. ' v' . $config->version;
		$this->view->pageTitle = $this->view->translate( $titre );
	}
}

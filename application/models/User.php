<?php
class Application_Model_User extends Application_Model_AbstractModel
{
	protected $_login = '';
	
	
	protected $_role;
	
	
	
	public function __construct( $options = array() )
	{
		parent::__construct( $options );
	}

	
	public function setLogin( $login )
	{
		$this->_role = $role;
		
		return $this;
	}
	

	public function getLogin()
	{
		return $this->_login;
	}
	
	
	public function setRole( $role = null )
	{
		$this->_role = $role;
		
		return $this;
	}
	
	
	public function getRole()
	{
		return $this->_role;
	}
}
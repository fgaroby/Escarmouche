<?php
class Application_Model_Comment extends Application_Model_AbstractModel
{
	protected $_author;
	
	
	public function __construct( $options = array() )
	{
		parent::__construct( $options );
	}
	
	
	public function setAuthor( $author )
	{
		$this->_author = $author;
		
		return $this;
	}
	
	public function getAuthor()
	{
		if( $this->_author instanceof Application_Model_User )
			$this->_author = Application_Model_UserMapper::getInstance()->find( $this->_author );

		return $this->_author;
	}
}
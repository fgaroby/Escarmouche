<?php
class Application_Model_Type extends Application_Model_AbstractModel
{
	/**
	 * 
	 * The default type.
	 * @var int
	 */
	const STORY		= 1;
	
	
	/**
	 *
	 * @var int
	 */
	const TECHNICAL	= 2;
	
	
	/**
	 * 
	 * @var int
	 */
	const TEST		= 3;
	
	
	const TYPE		= 4;
	
	
	protected $_color;
	
	
	
	public function __construct( $options  = array() )
	{
		parent::__construct( $options );
	}
	
	
	public function setColor( $color )
	{
		$this->_color = $color;
		
		return $this;
	}
	
	
	public function getColor()
	{
		return $this->_color;
	}
	
	
	public static function isValid( $type )
	{
		if( $type instanceof Application_Model_Type )
			$type = $type->getId();
			
		return ( $type & ( Application_Model_Type::STORY
							| Application_Model_Type::TECHNICAL
							| Application_Model_Type::TEST
							| Application_Model_Type::TYPE ) ) > 0;
	}
}
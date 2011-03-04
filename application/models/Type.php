<?php
/**
 *  Application_Model_Type
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
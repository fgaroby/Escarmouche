<?php
/**
 *  Application_Model_User
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

class Application_Model_User extends Application_Model_AbstractModel
{
	protected $_id			= null;
	
	
	protected $_username	= '';
	
	
	protected $_login		= '';
	
	
	protected $_role		= null;
	
	
	protected $_products	= null;
	
	
	protected $_comments	= null;
	
	
	
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
	
	
	/**
	 * 
	 * Enter description here ...
	 * @param array[Application_Model_Product] | Application_Model_Product $product the product (or array of products) we want to add 
	 * @throws InvalidArgumentException
	 */
	public function addProduct( $product )
	{
		if( is_array( $product ) )
			foreach( $product as $s )
				$this->_addProduct( $s );
		else if( $product instanceof Application_Model_Product )
			$this->_addProduct( $product );
		else
			throw new UnexpectedValueException( "'\$product' is not a Application_Model_Product or an array of Application_Model_Product !" );
			
		return $this;
	}
	
	
	protected function _addProduct( Application_Model_Product $product )
	{
		$index = array_search( $product, $this->_products );
		if( $index === false )
		{
			$this->_products[] = $product;
			$product->setSprint( $this );
		}
		
		return $this;
	}
	
	
	public function removeProduct( $product )
	{
		if( $product === null || empty( $product ) )
			throw new InvalidArgumentException( "'\$product' cannot be 'null' or empty !" );
		
		if( is_array( $product ) )
			foreach( $product as $s )
				$this->_removeProduct( $s );
		else if( $product instanceof Application_Model_Product )
			$this->_removeProduct( $product );
		else
			throw new UnexpectedValueException( "'\$product' is not a Application_Model_Product or an array of Application_Model_Product !" );
			
		return $this;
	}
	
	
	protected function _removeProduct( Application_Model_Product $product )
	{
		$index = array_search( $product, $this->_products );
		if( $index !== false )
		{
			unset( $this->_products[$index] );
			$product->setSprint( null );
		}
		
		return $this;
	}
	
	
	public function getProduct( $index )
	{
		if( !is_numeric( $index ) )
			throw new InvalidArgumentException( "'\$index' is NaN !" );
		if( $index < 0 || $index >= sizeof( $this->_comments ) )
			throw new OutOfRangeException( "'\$index' cannot be negative, greater than or equal to the array size !" );
			
		return $this->_comments[$index];
	}
	
	
	public function getProducts()
	{
		$this->_loadProducts();
		
		return $this->_products;
	}
	
	
	protected function _loadProducts()
	{
		if( null === $this->_products )
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
														array(	'productOwner'	=> 'id' ) )
											 ->where(	'u.id = ?', $this->_id );
			$selectProductsWithSMStatus = $pm->getDbTable()
											 ->select()
											 ->setIntegrityCheck( false )
											 ->from(	array(	'p'	=> 'product' ) )
											 ->join(	array(	'sm'	=> 'scrumMaster' ),
														'sm.product = p.id',
														null )
											 ->join(	array(	'u'	=> 'user' ),
														'u.id = sm.user',
														array(	'scrumMaster'	=> 'id' ) )
											 ->where(	'u.id = ?', $this->_id );
			$selectProductsWithTeamStatus = $pm->getDbTable()
											   ->select()
											   ->setIntegrityCheck( false )
											   ->from(	array(	'p'	=> 'product' ) )
											   ->join(	array(	't'	=> 'team' ),
														't.product = p.id',
														null )
											   ->join(	array(	'u'	=> 'user' ),
														'u.id = t.user',
														array( 'developpers'	=> 'id' ) )
											 ->where(	'u.id = ?', $this->_id );
			
			$this->_products = $pm->fetchAll( $pm->getDbTable()
												 ->select()
												 ->union( array(	$selectProductsWithPOStatus,
																	$selectProductsWithSMStatus,
																	$selectProductsWithTeamStatus ) )
												 ->order( 'p.name ASC' ) );
		}
	}
	
	
	public function addComment( $comment )
	{
		if( is_array( $comment ) )
			foreach( $comment as $s )
				$this->_addComment( $s );
		else if( $comment instanceof Application_Model_Comment )
			$this->_addComment( $comment );
		else
			throw new UnexpectedValueException( "'\$comment' is not a Application_Model_Comment or an array of Application_Model_Comment !" );
			
		return $this;
	}
	
	
	protected function _addComment( Application_Model_Comment $comment )
	{
		$index = array_search( $comment, $this->_comments );
		if( $index === false )
		{
			$this->_comments[] = $comment;
			$comment->setSprint( $this );
		}
		
		return $this;
	}
	
	
	public function removeComment( $comment )
	{
		if( $comment === null || empty( $comment ) )
			throw new InvalidArgumentException( "'\$comment' cannot be 'null' or empty !" );
		
		if( is_array( $comment ) )
			foreach( $comment as $s )
				$this->_removeComment( $s );
		else if( $comment instanceof Application_Model_Comment )
			$this->_removeComment( $comment );
		else
			throw new UnexpectedValueException( "'\$comment' is not a Application_Model_Comment or an array of Application_Model_Comment !" );
			
		return $this;
	}
	
	
	protected function _removeComment( Application_Model_Comment $comment )
	{
		$index = array_search( $comment, $this->_comments );
		if( $index !== false )
		{
			unset( $this->_comments[$index] );
			$comment->setSprint( null );
		}
		
		return $this;
	}
	
	
	/**
	 * 
	 * Enter description here ...
	 * @param int $index
	 * @throws InvalidArgumentException
	 * @throws OutOfRangeException
	 * @return Application_Model_Sprint
	 */
	public function getComment( $index )
	{
		if( !is_numeric( $index ) )
			throw new InvalidArgumentException( "'\$index' is NaN !" );
		if( $index < 0 || $index >= sizeof( $this->_comments ) )
			throw new OutOfRangeException( "'\$index' cannot be negative, greater than or equal to the array size !" );
			
		return $this->_comments[$index];
	}
	
	
	public function getComments()
	{
		$this->_loadComments();
		
		return $this->_comments;
	}
	
	
	/**
	 * 
	 * Load the comments written by the user.
	 */
	protected function _loadComments()
	{
		if( null === $this->_comments )
		{
			$pm = Application_Model_CommentMapper::getInstance();
			$selectFeatureComments = $pm->getDbTable()
										->select()
										->setIntegrityCheck( false )
										->from(	array(	'c'		=> 'comment' ) )
									    ->join(	array(	'fc'	=> 'feature_comment' ),
								   						'fc.comment_id 	= c.id',
									    				null )
									    ->join(	array(	'f'		=> 'feature' ),
								   						'fc.feature_id = f.id',
									    				null )
										->where( 'c.id = ?', $this->_id );
									  	
			$selectStoryComments = $pm->getDbTable()
									  ->select()
									  ->setIntegrityCheck( false )
									  ->from(	array(	'c'		=> 'comment' ) )
									  ->join(	array(	'sc'	=> 'story_comment' ),
									 					'sc.comment_id 	= c.id',
									    				null )
									  ->join( 	array(	's'		=> 'story' ),
									 					'sc.story_id = s.id',
									    				null )
									  ->where( 'c.id = ?', $this->_id );
									  	
			$selectTaskComments = $pm->getDbTable()
									  ->select()
									  ->setIntegrityCheck( false )
									  ->from(	array(	'c'		=> 'comment' ) )
									  ->join(	array(	'tc'	=> 'task_comment' ),
									 					'tc.comment_id 	= c.id',
									    				null )
									  ->join( 	array(	't'		=> 'task' ),
									 					'tc.task_id = t.id',
									    				null )
									  ->where( 'c.id = ?', $this->_id );
									  	
			$selectSprintComments = $pm->getDbTable()
									   ->select()
									   ->setIntegrityCheck( false )
									   ->from(	array(	'c'		=> 'comment' ) )
									   ->join(	array(	'sc'	=> 'sprint_comment' ),
									 					'sc.comment_id 	= c.id',
									    				null )
									   ->join( 	array(	't'		=> 'task' ),
									 					'sc.sprint_id = t.id',
									    				null )
									  ->where( 'c.id = ?', $this->_id );
			$this->_comments = $pm->fetchAll( $selectStoryComments  );
			$this->_comments = $pm->fetchAll( $pm->getDbTable()
												 ->select()
												 ->union( array(	$selectFeatureComments,
																	$selectStoryComments,
																	$selectTaskComments ) )
												 ->order( 'created DESC' ) );
		}
	}
	
	
	public function __toString()
	{
		return ( string ) $this->_id;
	}
}
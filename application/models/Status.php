<?php
/**
 *  Application_Model_Status
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

class Application_Model_Status extends Application_Model_AbstractModel
{
	/**
	 *
	 * The default status value
	 * @var int
	 */
	const SUGGESTED	= 1;

	/**
	 *
	 * The status used when the story is accepted by the Product Owner.
	 * @var int
	 */
	const ACCEPTED	= 2;

	/**
	 *
	 * The status used when the story has been planified in a sprint, by the extended team.
	 * @var int
	 */
	const PLANIFIED	= 4;

	/**
	 *
	 * The status used when the story was estimated by team.
	 * @var int
	 */
	const ESTIMATED	= 8;
	
	
	/**
	 * 
	 * The status used when the task is ready to be developped, but not started yet.
	 * @var int
	 */
	const TODO		= 16;

	/**
	 *
	 * The status used when the story is in progress (WIP = Work In Progress).
	 * @var int
	 */
	const WIP		= 32;
	
	
	/**
	 * 
	 * The status used when a test (or a story) has failed.
	 * @var int
	 */
	const FAILED	= 64;
	
	
	/**
	 * 
	 * The status used when a test (or a story) has passed.
	 * A story can have this status only if all its tests have passed.
	 * @var int
	 */
	const PASSED	= 128;

	/**
	 *
	 * The status used when the task is finished or when all tasks of a sprin are finished.
	 * @var int
	 */
	const FINISHED	= 256;
	
	
	public function __construct( $options = array() )
	{
		parent::__construct( $options );
	}
	
	
	public function __toString()
	{
		return $this->_name;
	}
	
	
	public function getName()
	{
		return $this->_name;
	}
	
	
	public function getId()
	{
		return $this->_id;
	}
	
	
	public static function getStatus( $status )
	{
		if( !$status instanceof Application_Model_Status )
		{
			$sm = new Application_Model_StatusMapper();
			$status = $sm->find( $status );
		}
		
		return $status->getName();
	}
	
	
	public static function equals( $status1, $status2 )
	{
		if( $status1 instanceof Application_Model_Status && $status2 instanceof Application_Model_Status )
			return $status1->getId() === $status2->getId();
		
		if( $status1 instanceof Application_Model_Status && is_int( $status2 ) )
			return $status1->getId() === $status2;
		
		if( is_int( $status1 ) && $status2 instanceof Application_Model_Status )
			return $status1 === $status2->getId();
		
		return false;
	}
	
	/**
	 * 
	 * Checks if $status is a valid status
	 * @param int $status
	 * @return <code>true</code> if $status is a valid status, <code>false</code> otherwise.
	 */
	public static function isValid( $status )
	{
		if( $status instanceof Application_Model_Status )
			$status = $status->getId();
		if( !intval( $status, 10 ) )
			return false;
		
		$status = intval( $status, 10 );
		return ( $status & ( self::SUGGESTED
							| self::ACCEPTED
							| self::ESTIMATED
							| self::PLANIFIED
							| self::TODO
							| self::WIP
							| self::FAILED
							| self::PASSED
							| self::FINISHED ) ) > 0;
	}
	
	
	public static function isSandbox( $status )
	{
		if( $status instanceof Application_Model_Status )
			$status = $status->getId();
		
		return ( $status & self::SUGGESTED ) > 0 ;
	}
	
	
	/**
	 * 
	 * Ret
	 * @param int $status
	 * <code>false</code> otherwise.
	 */
	public static function isBacklogProduct( $status )
	{
		if( $status instanceof Application_Model_Status )
			$status = $status->getId();
		
		return ( $status & ( self::ACCEPTED
							| self::ESTIMATED ) ) > 0 ;
	}
	
	
	/**
	 * 
	 * Checks if $status has one of the status autohrized.
	 * @param int $status the status we want to check.
	 * @return <code>true</code> if $status is Application_Model_Status::TODO or Application_Model_Status::WIP or Application_Model_Status::FAILED or  Application_Model_Status::PASSED.
	 * <code>false</code> otherwise.
	 */
	public static function isSprintPlan( $status )
	{
		if( $status instanceof Application_Model_Status )
			$status = $status->getId();
		
		return ( $status & ( self::TODO
							| self::WIP
							| self::PASSED
							| self::FAILED ) ) > 0 ;
	}
	
	
	/**
	 * 
	 * Checks if $status has one of the status authorized.
	 * @param int $status the status we want to check.
	 * @return <code>true</code> if $status is Application_Model_Status::PLANIFIED or Application_Model_Status::WIP or Application_Model_Status::FINISHED.
	 * <code>false</code> otherwise.
	 */
	public static function isReleasePlan( $status )
	{
		if( $status instanceof Application_Model_Status )
			$status = $status->getId();
		
		return ( $status & ( self::PLANIFIED
							| self::TODO
							| self::WIP
							| self::PASSED
							| self::FAILED
							| self::FINISHED ) ) > 0 ;
	}
	
	
	public static function isValidTaskStatus( $status )
	{
		if( $status instanceof Application_Model_Status )
			$status = $status->getId();
		
		return ( $status & ( Application_Model_Status::TODO
							| Application_Model_Status::WIP
							| Application_Model_Status::FINISHED
							| Application_Model_Status::PASSED
							| Application_Model_Status::FAILED ) ) > 0;
	}
	
	
	public static function isValidSprintStatus( $status )
	{
		if( $status instanceof Application_Model_Status )
			$status = $status->getId();
		
		return ( $status & ( Application_Model_Status::SUGGESTED
							| Application_Model_Status::ACCEPTED
							| Application_Model_Status::PLANIFIED
							| Application_Model_Status::ESTIMATED
							| Application_Model_Status::WIP
							| Application_Model_Status::FINISHED ) ) > 0;
	}
	
	/**
	 * 
	 * Checks if $status has one of the status authorized to a feature
	 * @param int $status the status we want to check.
	 * @return <code>true</code> if s$tatus is Application_Model_Status::SUGGESTED or Application_Model_Status::PLANIFIED or Application_Model_Status::WIP or Application_Model_Status::FINISHED.
	 * <code>false</code> otherwise.
	 */
	public static function isValidFeatureStatus( $status )
	{
		if( $status instanceof Application_Model_Status )
			$status = $status->getId();
		
		
		return ( $status & ( self::TODO
							| self::WIP
							| self::FINISHED ) ) > 0;
	}
	
	
	
	/**
	 * 
	 * Checks if $status has one of the status authorized to a release
	 * @param int $status the status we want to check.
	 * @return <code>true</code> if s$tatus is Application_Model_Status::SUGGESTED or Application_Model_Status::PLANIFIED or Application_Model_Status::WIP or Application_Model_Status::FINISHED.
	 * <code>false</code> otherwise.
	 */
	public static function isValidReleaseStatus( $status )
	{
		if( $status instanceof Application_Model_Status )
			$status = $status->getId();
		
		
		return ( $status & ( self::SUGGESTED
							| self::PLANIFIED
							| self::WIP
							| self::FINISHED ) ) > 0;
	}
	
	
	public static function isStarted( $status )
	{
		if( $status instanceof Application_Model_Status )
			$status = $status->getId();
		
		
		return ( $status & ( self::WIP
							| self::FAILED
							| self::PASSED
							| self::FINISHED ) ) > 0;
	}
	
	
	public static function isFinished( $status )
	{
		if( $status instanceof Application_Model_Status )
			$status = $status->getId();
		
		
		return ( $status & ( self::PASSED
							| self::FINISHED ) ) > 0;
	}
}
<?php
abstract class Application_Model_Status
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
	 * The status used when the story was estimated by team.
	 * @var int
	 */
	const ESTIMATED	= 4;

	/**
	 *
	 * The status used when the story has been planified in a sprint, by the extended team.
	 * @var int
	 */
	const PLANIFIED	= 8;
	
	
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
	
	
	
	/**
	 * 
	 * Checks if $status is a valid status
	 * @param int $status
	 * @return <code>true</code> if $status is a valid status, <code>false</code> otherwise.
	 */
	public static function isValid( $status )
	{
		if( !is_int( (int ) $status ) )
			return false;
		
		$status = (int ) $status;
		return $status === self::SUGGESTED
			|| $status === self::ACCEPTED
			|| $status === self::ESTIMATED
			|| $status === self::PLANIFIED
			|| $status === self::TODO
			|| $status === self::WIP
			|| $status === self::FAILED
			|| $status === self::PASSED
			|| $status === self::FINISHED;
	}
	
	
	public static function isSandbox( $status )
	{
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
		return ( $status & ( self::TODO
							| self::WIP
							| self::PASSED
							| self::FAILED ) ) > 0 ;
	}
	
	
	/**
	 * 
	 * Checks if $status has one of the status autohrized.
	 * @param int $status the status we want to check.
	 * @return <code>true</code> if $status is Application_Model_Status::PLANIFIED or Application_Model_Status::WIP or Application_Model_Status::FINISHED.
	 * <code>false</code> otherwise.
	 */
	public static function isReleasePlan( $status )
	{
		return ( $status & ( self::PLANIFIED
							| self::TODO
							| self::WIP
							| self::PASSED
							| self::FAILED
							| self::FINISHED ) ) > 0 ;
	}
}
<?php
require_once( 'application/models/AbstractTest.php' );

class Application_Model_StatusTest extends Application_Model_AbstractTest
{
	public function testIsValidShouldFail()
	{
		$this->assertFalse( Application_Model_Status::isValid( -1 ) );
		$this->assertFalse( Application_Model_Status::isValid( 1000000000 ) );
	}
	
	
	public function testIsValidShouldPass()
	{
		$this->assertTrue( Application_Model_Status::isValid( Application_Model_Status::ACCEPTED ) );
		$this->assertTrue( Application_Model_Status::isValid( Application_Model_Status::ESTIMATED ) );
		$this->assertTrue( Application_Model_Status::isValid( Application_Model_Status::FAILED ) );
		$this->assertTrue( Application_Model_Status::isValid( Application_Model_Status::FINISHED ) );
		$this->assertTrue( Application_Model_Status::isValid( Application_Model_Status::PASSED ) );
		$this->assertTrue( Application_Model_Status::isValid( Application_Model_Status::PLANIFIED ) );
		$this->assertTrue( Application_Model_Status::isValid( Application_Model_Status::SUGGESTED ) );
		$this->assertTrue( Application_Model_Status::isValid( Application_Model_Status::TODO ) );
		$this->assertTrue( Application_Model_Status::isValid( Application_Model_Status::WIP ) );
	}
	
	
	public function testIsSandboxShouldFail()
	{
		$this->assertFalse( Application_Model_Status::isSandbox( Application_Model_Status::ACCEPTED ) );
		$this->assertFalse( Application_Model_Status::isSandbox( Application_Model_Status::ESTIMATED ) );
		$this->assertFalse( Application_Model_Status::isSandbox( Application_Model_Status::FAILED ) );
		$this->assertFalse( Application_Model_Status::isSandbox( Application_Model_Status::FINISHED ) );
		$this->assertFalse( Application_Model_Status::isSandbox( Application_Model_Status::PASSED ) );
		$this->assertFalse( Application_Model_Status::isSandbox( Application_Model_Status::PLANIFIED ) );
		$this->assertFalse( Application_Model_Status::isSandbox( Application_Model_Status::TODO ) );
		$this->assertFalse( Application_Model_Status::isSandbox( Application_Model_Status::WIP ) );
	}
	
	
	public function testIsSandboxShouldPass()
	{
		$this->assertTrue( Application_Model_Status::isSandbox( Application_Model_Status::SUGGESTED ) );
	}
	
	
	public function testIsBacklogProductShouldFail()
	{
		$this->assertFalse( Application_Model_Status::isBacklogProduct( Application_Model_Status::FAILED ) );
		$this->assertFalse( Application_Model_Status::isBacklogProduct( Application_Model_Status::FINISHED ) );
		$this->assertFalse( Application_Model_Status::isBacklogProduct( Application_Model_Status::PASSED ) );
		$this->assertFalse( Application_Model_Status::isBacklogProduct( Application_Model_Status::PLANIFIED ) );
		$this->assertFalse( Application_Model_Status::isBacklogProduct( Application_Model_Status::SUGGESTED ) );
		$this->assertFalse( Application_Model_Status::isBacklogProduct( Application_Model_Status::TODO ) );
		$this->assertFalse( Application_Model_Status::isBacklogProduct( Application_Model_Status::WIP ) );
	}
	
	
	public function testIsBacklogProductShouldPass()
	{
		$this->assertTrue( Application_Model_Status::isBacklogProduct( Application_Model_Status::ACCEPTED ) );
		$this->assertTrue( Application_Model_Status::isBacklogProduct( Application_Model_Status::ESTIMATED ) );
	}
	
	
	public function testIsSprintPlanShouldFail()
	{
		$this->assertFalse( Application_Model_Status::isSprintPlan( Application_Model_Status::ACCEPTED ) );
		$this->assertFalse( Application_Model_Status::isSprintPlan( Application_Model_Status::ESTIMATED ) );
		$this->assertFalse( Application_Model_Status::isSprintPlan( Application_Model_Status::FINISHED ) );
		$this->assertFalse( Application_Model_Status::isSprintPlan( Application_Model_Status::SUGGESTED ) );
	}
	
	
	public function testIsSprintPlanShouldPass()
	{
		$this->assertTrue( Application_Model_Status::isSprintPlan( Application_Model_Status::FAILED ) );
		$this->assertTrue( Application_Model_Status::isSprintPlan( Application_Model_Status::PASSED ) );
		$this->assertTrue( Application_Model_Status::isSprintPlan( Application_Model_Status::TODO ) );
		$this->assertTrue( Application_Model_Status::isSprintPlan( Application_Model_Status::WIP ) );
	}
	
	
	public function testIsReleasePlanShouldFail()
	{
		$this->assertFalse( Application_Model_Status::isReleasePlan( Application_Model_Status::ACCEPTED ) );
		$this->assertFalse( Application_Model_Status::isReleasePlan( Application_Model_Status::ESTIMATED ) );
		$this->assertFalse( Application_Model_Status::isReleasePlan( Application_Model_Status::SUGGESTED ) );
	}
	
	
	public function testIsReleasePlanShouldPass()
	{
		$this->assertTrue( Application_Model_Status::isReleasePlan( Application_Model_Status::PLANIFIED ) );
		$this->assertTrue( Application_Model_Status::isReleasePlan( Application_Model_Status::FAILED ) );
		$this->assertTrue( Application_Model_Status::isReleasePlan( Application_Model_Status::FINISHED ) );
		$this->assertTrue( Application_Model_Status::isReleasePlan( Application_Model_Status::PASSED ) );
		$this->assertTrue( Application_Model_Status::isReleasePlan( Application_Model_Status::WIP ) );
	}
}
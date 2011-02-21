<?php
require_once( 'application/models/AbstractTest.php' );

class Application_Model_ReleaseTest extends Application_Model_AbstractTest
{
	public function setUp()
	{
		$this->_options = array(	'id' => 12345,
									'name' => 'release name' );
	}
	
	
	
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testEmptyConstructor()
	{
		$release = new Application_Model_Release( array() );
	}
	

	public function testIdConstructor()
	{
		$release = new Application_Model_Release( array( 'id' => $this->_options['id'] ) );
		
		$this->assertEquals( $this->_options['id'], $release->getId() );
		$this->assertEmpty( $release->getName() );
		$this->assertEmpty( $release->getDescription() );
		$this->assertEquals( Application_Model_Status::SUGGESTED, $release->getStatus() );
		$this->assertNull( $release->getProduct() );
		$this->assertEmpty( $release->getSprints() );
	}
	
	
	public function testNameConstructor()
	{
		$release = new Application_Model_Release( array( 'name' => $this->_options['name'] ) );
		
		$this->assertEmpty( $release->getId() );
		$this->assertEquals( $this->_options['name'], $release->getName() );
		$this->assertEmpty( $release->getDescription() );
		$this->assertEquals( Application_Model_Status::SUGGESTED, $release->getStatus() );
		$this->assertNull( $release->getProduct() );
		$this->assertEmpty( $release->getSprints() );
	}
	
	
	public function testAddSprint()
	{
		$sprint = new Application_Model_Sprint( array( 'name' => 'sprint name' ) );
		$release = new Application_Model_Release( array( 'name' => $this->_options['name'] ) );
		
		$this->assertEquals( 0, sizeof( $release->getSprints() ) );
		
		$release->addSprint( $sprint );
		$this->assertEquals( 1, sizeof( $release->getSprints() ) );
	}
	
	
	public function testAddSameSprintSeveralTimes()
	{
		$sprint = new Application_Model_Sprint( array( 'name' => 'sprint name' ) );
		$release = new Application_Model_Release( array( 'name' => $this->_options['name'] ) );
		
		$this->assertEquals( 0, sizeof( $release->getSprints() ) );
		
		$release->addSprint( $sprint );
		$this->assertEquals( 1, sizeof( $release->getSprints() ) );
		
		$release->addSprint( $sprint );
		$this->assertEquals( 1, sizeof( $release->getSprints() ) );
	}
	
	
	public function testAddArrayOfSprints()
	{
		$sprint1 = new Application_Model_Sprint( array( 'name' => 'sprint1 name' ) );
		$sprint2 = new Application_Model_Sprint( array( 'name' => 'sprint2 name' ) );
		$release = new Application_Model_Release( array( 'name' => $this->_options['name'] ) );
		
		$this->assertEmpty( $release->getSprints() );
		
		$release->addSprint( array( $sprint1, $sprint2 ) );
		$this->assertNotEmpty( $release->getSprints() );
	}
	
	
	public function testAddSeveralSprints()
	{
		$sprint1 = new Application_Model_Sprint( array( 'name' => 'sprint1 name' ) );
		$sprint2 = new Application_Model_Sprint( array( 'name' => 'sprint2 name' ) );
		$release = new Application_Model_Release( array( 'name' => $this->_options['name'] ) );
		
		$this->assertEmpty( $release->getSprints() );
		
		$release->addSprint( $sprint1 );
		$this->assertNotEmpty( $release->getSprints() );
		$this->assertEquals( array( $sprint1 ), $release->getSprints() );
		
		$release->addSprint( $sprint2 );
		$this->assertNotEmpty( $release->getSprints() );
		$this->assertEquals( 2, sizeof( $release->getSprints() ) );
		$this->assertEquals( array( $sprint1, $sprint2 ), $release->getSprints() );
	}
	
	
	public function testRemoveSprint()
	{
		$sprint = new Application_Model_Sprint( array( 'name' => 'sprint name' ) );
		$release = new Application_Model_Release( array( 'name' => $this->_options['name'], 'sprint' => array( $sprint ) ) );
		
		$release->removeSprint( $sprint );
		$this->assertEquals( 0, sizeof( $release->getSprints() ) );
	}
	
	
	public function testRemoveSameSprintSeveralTimes()
	{
		$sprint = new Application_Model_Sprint( array( 'name' => 'sprint name' ) );
		$release = new Application_Model_Release( array( 'name' => $this->_options['name'], 'sprint' => array( $sprint ) ) );
		
		
		$release->removeSprint( $sprint );
		$this->assertEquals( 0, sizeof( $release->getSprints() ) );
		
		$release->removeSprint( $sprint );
		$this->assertEquals( 0, sizeof( $release->getSprints() ) );
	}
	
	
	public function testRemoveArrayOfSprints()
	{
		$sprint1 = new Application_Model_Sprint( array( 'name' => 'sprint1 name' ) );
		$sprint2 = new Application_Model_Sprint( array( 'name' => 'sprint2 name' ) );
		$release = new Application_Model_Release( array( 'name' => $this->_options['name'], 'sprint' => array( $sprint1, $sprint2 ) ) );
		
		
		$release->removeSprint( array( $sprint1, $sprint2 ) );
		$this->assertEmpty( $release->getSprints() );
	}
	
	
	public function testRemoveSeveralSprints()
	{
		$sprint1 = new Application_Model_Sprint( array( 'name' => 'sprint1 name' ) );
		$sprint2 = new Application_Model_Sprint( array( 'name' => 'sprint2 name' ) );
		$release = new Application_Model_Release( array( 'name' => $this->_options['name'], 'sprint' => array( $sprint1, $sprint2 ) ) );
		
		$release->removeSprint( $sprint1 );
		$this->assertNotEmpty( $release->getSprints() );
		$this->assertEquals( array( 1 => $sprint2 ), $release->getSprints() );
		
		$release->removeSprint( $sprint2 );
		$this->assertEmpty( $release->getSprints() );
	}
	
	
	public function testGetSprintShouldReturnASprint()
	{
		$sprint = new Application_Model_Sprint( array( 'name' => 'sprint name' ) );
		$release = new Application_Model_Release( array( 'name' => $this->_options['name'], 'sprint' => array( $sprint ) ) );
		
		$this->assertEquals( 1, sizeof( $release->getSprints() ) );
		$this->assertEquals( $sprint, $release->getSprint( 0 ) );
	}
	
	
	/**
	 * @expectedException OutOfRangeException
	 */
	public function testGetSprintWithNegativeIndexShouldThrowOutOfRangeException()
	{
		$release = new Application_Model_Release( array( 'name' => $this->_options['name'] ) );
		$release->getSprint( -1 );
	}
	
	
	/**
	 * @expectedException OutOfRangeException
	 */
	public function testGetSprintWithIndexTooHighShouldThrowOutOfRangeException()
	{
		$sprint = new Application_Model_Sprint( array( 'name' => 'sprint name' ) );
		$release = new Application_Model_Release( array( 'name' => $this->_options['name'], 'sprint' => array( $sprint ) ) );
		$release->getSprint( 1 );
	}
	
	
	public function testSetAndGetStatusShouldPass()
	{
		$release = new Application_Model_Release( array( 'name' => $this->_options['name'] ) );
		
		$this->assertEquals( Application_Model_Status::SUGGESTED, $release->getStatus() );
		
		$release->setStatus( Application_Model_Status::WIP );
		$this->assertEquals( Application_Model_Status::WIP, $release->getStatus() );
	}
	
	
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSetNegativeStatusShouldFail()
	{
		$release = new Application_Model_Release( array( 'name' => $this->_options['name'] ) );
		
		$release->setStatus( -1 );
	}
	
	
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSetUnknownStatusShouldFail()
	{
		$release = new Application_Model_Release( array( 'name' => $this->_options['name'] ) );
		
		$release->setStatus( 1023 );
	}
	
	
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSetNaNStatusShouldFail()
	{
		$release = new Application_Model_Release( array( 'name' => $this->_options['name'] ) );
		
		$release->setStatus( 'a' );
	}
	
	
	public function testSetAndGetProductShouldPass()
	{
		$release = new Application_Model_Release( array( 'name' => $this->_options['name'] ) );
		$product = new Application_Model_Product( array( 'name' => 'product name' ) );
		
		$this->assertEmpty( $release->getProduct() );
		
		$release->setProduct( $product );
		$this->assertEquals( $product, $release->getProduct() );
	}
	
	
	public function testSetAndGetNullProductShouldPass()
	{
		$release = new Application_Model_Release( array( 'name' => $this->_options['name'] ) );
		
		$this->assertEmpty( $release->getProduct() );
		
		$release->setProduct( null );
		$this->assertEmpty( $release->getProduct() );
		$this->assertNull( $release->getProduct() );
	}
}
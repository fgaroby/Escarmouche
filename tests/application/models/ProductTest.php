<?php
require_once( 'application/models/AbstractTest.php' );

class Application_Model_ProductTest extends Application_Model_AbstractTest
{
	public function setUp()
	{
		$this->_options = array(	'id' => 12345,
									'name' => 'product name' );
	}
	
	
	
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testEmptyConstructor()
	{
		$product = new Application_Model_Product();
	}
	
	
	public function testIdConstructor()
	{
		$product = new Application_Model_Product( array( 'id' => $this->_options['id'] ) );
		
		$this->assertEquals( $this->_options['id'], $product->getId() );
		$this->assertEmpty( $product->getName() );
		$this->assertEmpty( $product->getDescription() );
		$this->assertNull( $product->getScrumMaster() );
		$this->assertNull( $product->getProductOwner() );
		$this->assertEmpty( $product->getReleases() );
	}
	
	
	public function testNameConstructor()
	{
		$product = new Application_Model_Product( array( 'name' => $this->_options['name'] ) );
		
		$this->assertEmpty( $product->getId() );
		$this->assertNotNull( $product->getName() );
		$this->assertEquals( $this->_options['name'], $product->getName() );
		$this->assertEmpty( $product->getDescription() );
		$this->assertNull( $product->getScrumMaster() );
		$this->assertNull( $product->getProductOwner() );
		$this->assertEmpty( $product->getReleases() );
	}
	
	
	public function testSetAndGetProductOwner()
	{
		$product = new Application_Model_Product( array( 'id' => $this->_options['id'] ) );
		$po = new Application_Model_User( array(	'id'	=> 1,
													'name'	=> 'user 1' ) );
		
		$this->assertNull( $product->getProductOwner() );
		
		$product->setProductOwner( $po );
		$this->assertNotNull( $product->getProductOwner() );
		$this->assertEquals( $po, $product->getProductOwner() );
	}
	
	
	public function testSetAndGetIntProductOwner()
	{
		$product = new Application_Model_Product( array( 'id' => $this->_options['id'] ) );
		$po = Application_Model_UserMapper::getInstance()->find( 1 );
		
		$this->assertNull( $product->getProductOwner() );
		
		$product->setProductOwner( 1 );
		$this->assertNotNull( $product->getProductOwner() );
		$this->assertEquals( $po, $product->getProductOwner() );
	}
	
	
	public function testSetAndGetScrumMaster()
	{
		$product = new Application_Model_Product( array( 'id' => $this->_options['id'] ) );
		$sc = new Application_Model_User( array(	'id'	=> 1,
													'name'	=> 'user 1' ) );
		
		$this->assertNull( $product->getScrumMaster() );
		
		$product->setScrumMaster( $sc );
		$this->assertNotNull( $product->getScrumMaster() );
		$this->assertEquals( $sc, $product->getScrumMaster() );
	}
	
	
	public function testSetAndGetIntScrumMaster()
	{
		$product = new Application_Model_Product( array( 'id' => $this->_options['id'] ) );
		$user = Application_Model_UserMapper::getInstance();
		$sc = $user->find( 1 );
		
		$this->assertNull( $product->getScrumMaster() );
		
		$product->setScrumMaster( 1 );
		$this->assertNotNull( $product->getScrumMaster() );
		$this->assertEquals( $sc, $product->getScrumMaster() );
	}
	
	
	public function testAddRelease()
	{
		$product = new Application_Model_Product( array( 'id' => $this->_options['id'] ) );
		$release = new Application_Model_Release( array( 'id' => 1 ) );
		
		$this->assertEmpty( $product->getReleases() );
		
		$product->addRelease( $release );
		$this->assertNotEmpty( $product->getReleases() );
		$this->assertEquals( 1, sizeof( $product->getReleases() ) );
		$this->assertEquals( $release, $product->getRelease( 0 ) );
	}
	
	
	public function testAddSameReleaseSeveralTimes()
	{
		$product = new Application_Model_Product( array( 'id' => $this->_options['id'] ) );
		$release = new Application_Model_Release( array( 'id' => 1 ) );
		
		$this->assertEmpty( $product->getReleases() );
		
		$product->addRelease( $release );
		$this->assertNotEmpty( $product->getReleases() );
		$this->assertEquals( 1, sizeof( $product->getReleases() ) );
		$this->assertEquals( $release, $product->getRelease( 0 ) );
		
		$product->addRelease( $release );
		$this->assertNotEmpty( $product->getReleases() );
		$this->assertEquals( 1, sizeof( $product->getReleases() ) );
		$this->assertEquals( $release, $product->getRelease( 0 ) );
	}
	
	
	public function testAddSeveralReleases()
	{
		$product = new Application_Model_Product( array( 'id' => $this->_options['id'] ) );
		$release1 = new Application_Model_Release( array( 'id' => 1 ) );
		$release2 = new Application_Model_Release( array( 'id' => 2 ) );
		
		$this->assertEmpty( $product->getReleases() );
		
		$product->addRelease( $release1 );
		$this->assertNotEmpty( $product->getReleases() );
		$this->assertEquals( 1, sizeof( $product->getReleases() ) );
		$this->assertEquals( $release1, $product->getRelease( 0 ) );
		
		$product->addRelease( $release2 );
		$this->assertNotEmpty( $product->getReleases() );
		$this->assertEquals( 2, sizeof( $product->getReleases() ) );
		$this->assertEquals( $release2, $product->getRelease( 1 ) );
	}
	
	
	public function testAddArrayOfReleases()
	{
		$product = new Application_Model_Product( array( 'id' => $this->_options['id'] ) );
		$release1 = new Application_Model_Release( array( 'id' => 1 ) );
		$release2 = new Application_Model_Release( array( 'id' => 2 ) );
		
		$this->assertEmpty( $product->getReleases() );
		
		$product->addRelease( array( $release1, $release2 ) );
		
		$product->addRelease( $release2 );
		$this->assertNotEmpty( $product->getReleases() );
		$this->assertEquals( 2, sizeof( $product->getReleases() ) );
		$this->assertEquals( $release1, $product->getRelease( 0 ) );
		$this->assertEquals( $release2, $product->getRelease( 1 ) );
	}
	
	
	public function testRemoveRelease()
	{
		$release = new Application_Model_Release( array( 'id' => 1 ) );
		$product = new Application_Model_Product( array( 'id' => $this->_options['id'], 'release' => array( $release ) ) );
		
		$this->assertNotEmpty( $product->getReleases() );
		$this->assertEquals( 1, sizeof( $product->getReleases() ) );
		$this->assertEquals( $release, $product->getRelease( 0 ) );
		
		$product->removeRelease( $release );
		$this->assertEmpty( $product->getReleases() );
	}
	
	
	public function testRemoveSameReleaseSeveralTimes()
	{
		$release = new Application_Model_Release( array( 'id' => 1 ) );
		$product = new Application_Model_Product( array(	'id' => $this->_options['id'],
															'release' => array( $release ) ) );
		
		$this->assertNotEmpty( $product->getReleases() );
		$this->assertEquals( 1, sizeof( $product->getReleases() ) );
		$this->assertEquals( $release, $product->getRelease( 0 ) );
		
		$product->removeRelease( $release );
		$this->assertEmpty( $product->getReleases() );
		
		$product->removeRelease( $release );
		$this->assertEmpty( $product->getReleases() );
	}
	
	
	public function testRemoveSeveralReleases()
	{
		$release1 = new Application_Model_Release( array( 'id' => 1 ) );
		$release2 = new Application_Model_Release( array( 'id' => 2 ) );
		$product = new Application_Model_Product( array( 'id' => $this->_options['id'] ) );
		$product->addRelease( $release1 )->addRelease( $release2 );
		
		$product->removeRelease( $release1 );
		$this->assertNotEmpty( $product->getReleases() );
		$this->assertEquals( 1, sizeof( $product->getReleases() ) );
		
		$product->removeRelease( $release2 );
		$this->assertEmpty( $product->getReleases() );
	}
	
	
	public function testRemoveArrayOfReleases()
	{
		$release1 = new Application_Model_Release( array( 'id' => 1 ) );
		$release2 = new Application_Model_Release( array( 'id' => 2 ) );
		$product = new Application_Model_Product( array(	'id' => $this->_options['id'],
															'release' => array( $release1, $release2 ) ) );
		
		$product->removeRelease( array( $release1, $release2 ) );
		$this->assertEmpty( $product->getReleases() );
	}
}
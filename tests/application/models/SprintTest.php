<?php
require_once( 'application/models/AbstractTest.php' );

class Application_Model_SprintTest extends Application_Model_AbstractTest
{
	public function setUp()
	{
		$this->_options = array(	'id' => 12345,
									'name' => 'sprint name' );
	}
	
	
	
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testEmptyConstructor()
	{
		$sprint = new Application_Model_Sprint( array() );
	}
	

	public function testIdConstructor()
	{
		$sprint = new Application_Model_Sprint( array( 'id' => $this->_options['id'] ) );

		$this->assertEquals( $this->_options['id'], $sprint->getId() );
		$this->assertEmpty( $sprint->getName() );
		$this->assertEmpty( $sprint->getDescription() );
		$this->assertEmpty( $sprint->getRelease() );
		$this->assertEquals( Application_Model_Status::SUGGESTED, $sprint->getStatus() );
		$this->assertEmpty( sizeof( $sprint->getStories() ) );
		$this->assertNull( $sprint->getStartDate() );
		$this->assertNull( $sprint->getEndDate() );
	}


	public function testNameConstructor()
	{
		$sprint = new Application_Model_Sprint( array( 'name' => $this->_options['name'] ) );

		$this->assertEmpty( $sprint->getId() );
		$this->assertEquals( $this->_options['name'], $sprint->getName() );
		$this->assertEmpty( $sprint->getDescription() );
		$this->assertNull( $sprint->getRelease() );
		$this->assertEquals( Application_Model_Status::SUGGESTED, $sprint->getStatus() );
		$this->assertEmpty( sizeof( $sprint->getStories() ) );
		$this->assertNull( $sprint->getStartDate() );
		$this->assertNull( $sprint->getEndDate() );
	}



	public function testSetAndGetDescription()
	{
		$sprint = new Application_Model_Sprint( array( 'name' => $this->_options['name'] ) );
		$description = 'description';

		$sprint->setDescription( $description );
		$this->assertNotNull( $sprint->getDescription() );
		$this->assertNotEmpty( $sprint->getDescription() );
		$this->assertEquals( $description, $sprint->getDescription() );
	}


	public function testRemoveDescription()
	{
		$sprint = new Application_Model_Sprint( array( 'name' => $this->_options['name'] ) );
		$description = 'description';

		$sprint->setDescription( $description );
		$this->assertNotNull( $sprint->getDescription() );
		$this->assertNotEmpty( $sprint->getDescription() );
		$this->assertEquals( $description, $sprint->getDescription() );

		$sprint->setDescription( null );
		$this->assertNull( $sprint->getDescription() );
	}


	public function testAddStory()
	{
		$sprint = new Application_Model_Sprint( array( 'name' => $this->_options['name'] ) );
		$this->assertEquals( 0, sizeof( $sprint->getStories() ) );

		$story = new Application_Model_Story( array( 'name' => 'story name' ) );
		$sprint->addStory( $story );
		$this->assertEquals( 1, sizeof( $sprint->getStories() ) );
		$this->assertEquals( $story, $sprint->getStory( 0 ) );
	}


	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testAddNullStory()
	{
		$story = new Application_Model_Story( array( 'name' => 'story name' ) );
		$sprint = new Application_Model_Sprint( array( 'name' => $this->_options['name'] ) );
		$sprint->addStory( null );
	}


	public function testGetStory()
	{
		$story = new Application_Model_Story( array( 'name' => 'story name' ) );
		$sprint = new Application_Model_Sprint( array( 'name' => $this->_options['name'] ) );
		$sprint->addStory( $story );
		$this->assertEquals( $story, $sprint->getStory( 0 ) );
	}


	public function testRemoveSeveralStories()
	{
		$sprint = new Application_Model_Sprint( array( 'name' => $this->_options['name'] ) );
		$this->assertEquals( 0, sizeof( $sprint->getStories() ) );

		$story1 = new Application_Model_Story( array( 'name' => 'story name1' ) );
		$story2 = new Application_Model_Story( array( 'name' => 'story name2' ) );
		
		$sprint->addStory( $story1 )
			   ->addStory( $story2 );
		$this->assertEquals( 2, sizeof( $sprint->getStories() ) );
		
		$sprint->removeStory( $story2 );
		$this->assertEquals( 1, sizeof( $sprint->getStories() ) );

		$sprint->removeStory( $story1 );
		$this->assertEquals( 0, sizeof( $sprint->getStories() ) );
	}


	public function testRemoveSameStorySeveralTimes()
	{
		$sprint = new Application_Model_Sprint( array( 'name' => $this->_options['name'] ) );
		
		$this->assertEquals( 0, sizeof( $sprint->getStories() ) );

		$story = new Application_Model_Story( array( 'name' => 'story name' ) );
		$sprint->addStory( $story );

		$sprint->removeStory( $story );
		$this->assertEquals( 0, sizeof( $sprint->getStories() ) );

		$sprint->removeStory( $story );
		$this->assertEquals( 0, sizeof( $sprint->getStories() ) );
	}
	

	public function testAddSeveralStories()
	{
		$sprint = new Application_Model_Sprint( array( 'name' => $this->_options['name'] ) );
		
		$this->assertEquals( 0, sizeof( $sprint->getStories() ) );

		$story1 = new Application_Model_Story( array( 'name' => 'story name1' ) );
		$sprint->addStory( $story1 );
		$this->assertEquals( 1, sizeof( $sprint->getStories() ) );

		$story2 = new Application_Model_Story( array( 'name' => 'story name2' ) );
		$sprint->addStory( $story2 );
		$this->assertEquals( 2, sizeof( $sprint->getStories() ) );
	}


	public function testAddSameStorySeveralTimes()
	{
		$sprint = new Application_Model_Sprint( array( 'name' => $this->_options['name'] ) );
		
		$this->assertEquals( 0, sizeof( $sprint->getStories() ) );

		$story = new Application_Model_Story( array( 'name' => 'story name' ) );
		$sprint->addStory( $story );
		$this->assertEquals( 1, sizeof( $sprint->getStories() ) );

		$sprint->addStory( $story );
		$this->assertEquals( 1, sizeof( $sprint->getStories() ) );
	}
}
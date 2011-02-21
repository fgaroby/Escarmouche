<?php
require_once( 'application/models/AbstractTest.php' );

class Application_Model_TaskTest extends Application_Model_AbstractTest
{
	public function setUp()
	{
		$this->_options = array(	'id' => 12345,
									'name' => 'story name' );
	}
	
	
	
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testEmptyConstructor()
	{
		$task = new Application_Model_Task( array() );
	}


	public function testIdConstructor()
	{
		$task = new Application_Model_Task( array( 'id' => $this->_options['id'] ) );

		$this->assertNotNull( $task->getId() );
		$this->assertEquals( $this->_options['id'], $task->getId() );
		$this->assertEmpty( $task->getName() );
		$this->assertEmpty( $task->getDescription() );
		$this->assertEmpty( $task->getStories() );
		$this->assertEquals( Application_Model_Status::SUGGESTED, $task->getStatus() );
	}


	public function testNameConstructor()
	{
		$task = new Application_Model_Task( array( 'name' => $this->_options['name'] ) );

		$this->assertEmpty( $task->getId() );
		$this->assertNotEmpty( $task->getName() );
		$this->assertEquals( $this->_options['name'], $task->getName() );
		$this->assertEmpty( $task->getDescription() );
		$this->assertEmpty( $task->getStories() );
		$this->assertNotNull( $task->getStatus() );
		$this->assertEquals( Application_Model_Status::SUGGESTED, $task->getStatus() );
	}


	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSetNullId()
	{
		$this->_options['id'] = 12345;
		$task = new Application_Model_Task( $this->_options );

		$task->setId( null );
	}


	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSetEmptyId()
	{
		$this->_options['id'] = 12345;
		$task = new Application_Model_Task( $this->_options );

		$task->setId( '' );
	}


	public function testSetAndGetId()
	{
		$this->_options = array( 'id' => 12345 );
		$id = 54321;
		$task = new Application_Model_Task( $this->_options );
		$this->assertNotNull( $task->getId() );

		$task->setId( $id );

		$this->assertNotNull( $task->getId() );
		$this->assertNotEmpty( $task->getId() );
		$this->assertEquals( $id, $task->getId() );
	}


	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSetNullName()
	{
		$this->_options['name'] = 'name task';
		$task = new Application_Model_Task( $this->_options );

		$task->setName( null );
	}


	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSetEmptyName()
	{
		$this->_options['name'] = 'name task';
		$task = new Application_Model_Task( $this->_options );

		$task->setName( '' );
	}


	public function testSetAndGetName()
	{
		$name = 'name task';
		$task = new Application_Model_Task( array( 'name' => $this->_options['name'] ) );
		$this->assertEquals( $this->_options['name'], $task->getName() );

		$task->setName( $name );
		$this->assertEquals( $name, $task->getName() );
	}


	public function testSetAndGetDescription()
	{
		$description = 'description task';
		$task = new Application_Model_Task( array( 'name' => $this->_options['name'] ) );
		$this->assertEmpty( $task->getDescription() );

		$task->setDescription( $description );

		$this->assertNotEmpty( $task->getDescription() );
		$this->assertEquals( $description, $task->getDescription() );
	}


	public function testSetAndGetNullDescription()
	{
		$this->_options['description'] = 'description task';
		$task = new Application_Model_Task( $this->_options );

		$task->setDescription( null );
		$this->assertNull( $task->getDescription() );
	}


	public function testAddStory()
	{
		$story = new Application_Model_Story( array( 'name' => 'story name' ) );
		$task = new Application_Model_Task( array( 'name' => $this->_options['name'] ) );
		$this->assertEquals( 0, sizeof( $task->getStories() ) );

		$task->addStory( $story );
		$this->assertNotEmpty( $task->getStories() );
		$this->assertEquals( 1, sizeof( $task->getStories() ) );
	}
	
	
	public function testGetStory()
	{
		$story = new Application_Model_Story( array( 'name' => 'story name' ) );
		$task = new Application_Model_Task( array( 'name' => $this->_options['name'] ) );
		$task->addStory( $story );
		
		$this->assertNotNull( $task->getStory( 0 ) );
		$this->assertEquals( $story, $task->getStory( 0 ) );
	}


	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testGetNullStory()
	{
		$task = new Application_Model_Task();

		$task->getStory( null );
	}


	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSetNullStatus()
	{
		$task = new Application_Model_Task();
		$this->assertNotNull( $task->getStatus() );
		$this->assertEquals( Application_Model_Status::SUGGESTED, $task->getStatus() );

		$task->setStatus( null );
	}
	
	
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testInvalidSuggestedStatus()
	{
		$task = new Application_Model_Task();
		$task->setStatus( Application_Model_Status::SUGGESTED );
	}
	
	
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testInvalidAcceptedStatus()
	{
		$task = new Application_Model_Task();
		$task->setStatus( Application_Model_Status::ACCEPTED );
	}
	
	
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testInvalidEstimatedStatus()
	{
		$task = new Application_Model_Task();
		$task->setStatus( Application_Model_Status::ESTIMATED );
	}
	
	
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testInvalidPlanifiedStatus()
	{
		$task = new Application_Model_Task();
		$task->setStatus( Application_Model_Status::PLANIFIED );
	}


	public function testSetAndGetStatus()
	{
		$status = Application_Model_Status::FINISHED;
		$task = new Application_Model_Task( $this->_options );
		$this->assertNotEmpty( $task->getStatus() );
		$this->assertEquals( Application_Model_Status::SUGGESTED, $task->getStatus() );

		$task->setStatus( $status );

		$this->assertNotNull( $task->getStatus() );
		$this->assertEquals( $status, $task->getStatus() );
	}
}
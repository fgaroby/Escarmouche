	<?php
require_once( 'application/models/AbstractTest.php' );

class Application_Model_StoryTest extends Application_Model_AbstractTest
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
		$story = new Application_Model_Story( array() );
	}
	
	
	public function testExistentIdConstructor()
	{
		$story = new Application_Model_Story( array( 'id' => $this->_options ['id'] ) );
		$this->assertEquals( $this->_options['id'], $story->getId() );
		$this->assertEmpty( $story->getName() );
		$this->assertEmpty( $story->getDescription() );
		$this->assertEquals( Application_Model_Status::SUGGESTED, $story->getStatus() );
		$this->assertNull( $story->getSprint() );
		$this->assertEmpty( $story->getTasks() );
	}
	
	
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testNullValueNameConstructor()
	{
		$story = new Application_Model_Story( array( 'name' => null ) );
	}
	
	
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testEmptyNameConstructor()
	{
		$story = new Application_Model_Story( array( 'name' => '' ) );
	}
	
	
	public function testNameConstructor()
	{
		$story = new Application_Model_Story( array( 'name' => $this->_options['name'] ) );
		
		$this->assertEmpty( $story->getId() );
		$this->assertNotNull( $story->getName() );
		$this->assertEquals( $this->_options['name'], $story->getName() );
		$this->assertEmpty( $story->getDescription() );
		$this->assertEquals( Application_Model_Status::SUGGESTED, $story->getStatus() );
		$this->assertNull( $story->getSprint() );
		$this->assertEmpty( $story->getTasks() );
	}
	
	
	public function testSetAndGetId()
	{
		$id = 12345;
		$story = new Application_Model_Story( array( 'name' => $this->_options['name'] ) );
		
		$story->setId( $id );
		$this->assertNotNull( $story->getId() );
		$this->assertNotEmpty( $story->getId() );
		$this->assertEquals( $id, $story->getId() );
	}
	
	
	/**
     * @expectedException InvalidArgumentException
     */
    public function testSetNullId()
	{
		$this->_options = array( 'id' => 12345 );
		$story = new Application_Model_Story( $this->_options );
		
		$story->setId( null );
	}
	
	
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSetEmptyId()
	{
		$this->_options = array( 'id' => 12345 );
		$story = new Application_Model_Story( $this->_options );
		
		$story->setId( '' );
	}
	
	
	public function testSetAndGetName()
	{
		$name = 'name story';
		$story = new Application_Model_Story( array( 'name' => $this->_options['name'] ) );
		
		$story->setName( $name );
		$this->assertNotNull( $story->getName() );
		$this->assertNotEmpty( $story->getName() );
		$this->assertEquals( $name, $story->getName() );
	}
	
	
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSetNullName()
	{
		$this->_options = array( 'name' => 'name story' );
		$story = new Application_Model_Story( $this->_options );
		
		$story->setName( null );
	}
	
	
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSetEmptyName()
	{
		$this->_options = array( 'name' => 'name story' );
		$story = new Application_Model_Story( $this->_options );
		
		$story->setName( '' );
	}
	
	
	public function testSetAndGetDescription()
	{
		$description = 'description story';
		$story = new Application_Model_Story( array( 'name' => $this->_options['name'] ) );
		
		$story->setDescription( $description );
		$this->assertNotEmpty( $story->getDescription() );
		$this->assertEquals( $description, $story->getDescription() );
	}
	
	
	public function testSetNullDescription()
	{
		$description = 'description story';
		$story = new Application_Model_Story( $this->_options );
		$story->setDescription( $description );
		
		$story->setDescription( null );
		$this->assertNull( $story->getDescription() );
	}
	
	
	public function testSetEmptyDescription()
	{
		$description = 'description story';
		$story = new Application_Model_Story( $this->_options );
		$story->setDescription( $description );
		
		$story->setDescription( '' );
		$this->assertNotNull( $story->getDescription() );
		$this->assertEmpty( $story->getDescription() );
	}
	
	
	public function testSetAndGetStatus()
	{
		$status = Application_Model_Status::FINISHED;
		$story = new Application_Model_Story( array( 'name' => $this->_options['name'] ) );
		
		$story->setStatus( $status );
		$this->assertNotNull( $story->getStatus() );
		$this->assertEquals( $status, $story->getStatus() );
	}
	
	
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSetNullStatus()
	{
		$this->_options = array( 'status' => Application_Model_Status::FINISHED );
		$story = new Application_Model_Story( $this->_options );
		
		$story->setStatus( null );
	}
	
	
	public function testSetAndGetSprint()
	{
		$sprint = new Application_Model_Sprint( array( 'name' => 'sprint name' ) );
		$story = new Application_Model_Story( array( 'name' => $this->_options['name'] ) );
		
		$story->setSprint( $sprint );
		$this->assertNotNull( $story->getSprint() );
		$this->assertEquals( $sprint, $story->getSprint() );
	}
	
	
	public function testSetNullSprint()
	{
		$sprint = new Application_Model_Sprint( array( 'name' => 'sprint name' ) );
		$story = new Application_Model_Story( $this->_options );
		$story->setSprint( $sprint );
		
		$story->setSprint( null );
		$this->assertNull( $story->getSprint() );
	}
	
	
	public function testAddTask()
	{
		$task = new Application_Model_Task( array( 'name' => 'task name' ) );
		$story = new Application_Model_Story( array( 'name' => $this->_options['name'] ) );
		
		$story->addTask( $task );
		$this->assertEquals( 1, sizeof( $story->getTasks() ) );
		$this->assertEquals( 1, sizeof( $task->getStories() ) );
	}


	/**
	 * @depends testEmptyConstructor
	 * @expectedException InvalidArgumentException
	 */
	public function testAddNullTask()
	{
		$story = new Application_Model_Story( array( 'name' => $this->_options['name'] ) );
		$story->addTask( null );
	}
	
	
	public function testAddSeveralTasks()
	{
		$task1 = new Application_Model_Task( array( 'name' => 'task name 1' ) );
		$task2 = new Application_Model_Task( array( 'name' => 'task name 2' ) );
		$story = new Application_Model_Story( array( 'name' => $this->_options['name'] ) );
		
		$story->addTask( $task1 );
		$this->assertEquals( 1, sizeof( $story->getTasks() ) );
		
		$story->addTask( $task2 );
		$this->assertEquals( 2, sizeof( $story->getTasks() ) );
	}
	
	
	public function testAddSameTaskSeveralTimes()
	{
		$task = new Application_Model_Task( array( 'name' => 'task name' ) );
		$story = new Application_Model_Story( array( 'name' => $this->_options['name'] ) );;
		
		$story->addTask( $task );
		$this->assertEquals( 1, sizeof( $story->getTasks() ) );
		
		$story->addTask( $task );
		$this->assertEquals( 1, sizeof( $story->getTasks() ) );
	}
	
	
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testGetNaNIndexTask()
	{
		$story = new Application_Model_Story( array( 'name' => $this->_options['name'] ) );
		
		$story->getTask( 'a' );
	}
	
	
	/**
	 * @expectedException OutOfRangeException
	 */
	public function testGetNegativeIndexTask()
	{
		$story = new Application_Model_Story( array( 'name' => $this->_options['name'] ) );;
		
		$story->getTask( -1 );
	}
	
	
	/**
	 * @expectedException OutOfRangeException
	 */
	public function testGetTooHighIndexTask()
	{
		$story = new Application_Model_Story( array( 'name' => $this->_options['name'] ) );
		
		$story->getTask( 0 );
	}
	
	
	public function testGetTask()
	{
		$task = new Application_Model_Task( array( 'name' => 'task name' ) );
		$story = new Application_Model_Story( array( 'name' => $this->_options['name'] ) );
		$story->addTask( $task );
		
		$this->assertEquals( 1, sizeof( $story->getTasks() ) );
		$this->assertEquals( $task, $story->getTask( 0 ) );
	}
	
	
	public function testGetTasks()
	{
		$task = new Application_Model_Task( array( 'name' => 'task name' ) );
		$story = new Application_Model_Story( array( 'name' => $this->_options['name'] ) );
		
		$this->assertEquals( 0, sizeof( $story->getTasks() ) );
		$story->addTask( $task );
		$this->assertEquals( 1, sizeof( $story->getTasks() ) );
	}
}
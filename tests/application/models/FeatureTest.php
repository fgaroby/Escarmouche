<?php
require_once( 'application/models/AbstractTest.php' );

class Application_Model_FeatureTest extends Application_Model_AbstractTest
{
	public function setUp()
	{
		$this->_options = array(	'id' => 12345,
									'name' => 'feature name' );
	}
	
	
	
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testEmptyConstructor()
	{
		$feature = new Application_Model_Feature();
	}
	
	
	public function testSetAndGetStatus()
	{
		$status = Application_Model_Status::FINISHED;
		$feature = new Application_Model_Feature( $this->_options );
		$this->assertNotEmpty( $feature->getStatus() );
		$this->assertEquals( Application_Model_Status::SUGGESTED, $feature->getStatus() );

		$feature->setStatus( $status );

		$this->assertNotNull( $feature->getStatus() );
		$this->assertEquals( $status, $feature->getStatus() );
	}
	
	
	public function testSetAndGetRelease()
	{
		$release = new Application_Model_Release( array( 'name' => 'release name' ) );
		$feature = new Application_Model_Feature( $this->_options );
		$this->assertNull( $feature->getRelease() );

		$feature->setRelease( $release );

		$this->assertNotNull( $feature->getRelease() );
		$this->assertEquals( $release, $feature->getRelease() );
	}
	
	
	/**
	 * @expectedException UnexpectedValueException
	 */
	public function testAddStoryShouldFail()
	{
		$story = new Application_Model_Story( array( 'name' => 'story name' ) );
		$feature = new Application_Model_Feature( $this->_options );
		$this->assertEmpty( $feature->getStories() );

		$feature->addStory( null );
	}
	
	
	public function testAddStoryShouldPass()
	{
		$story = new Application_Model_Story( array( 'name' => 'story name' ) );
		$feature = new Application_Model_Feature( $this->_options );
		$this->assertEmpty( $feature->getStories() );

		$feature->addStory( $story );
		$this->assertNotEmpty( $feature->getStories() );
		$this->assertEquals( 1, sizeof( $feature->getStories() ) ); 
	}
	
	
	public function testAddSameStorySeveralTimes()
	{
		$story = new Application_Model_Story( array( 'name' => 'story name' ) );
		$feature = new Application_Model_Feature( $this->_options );
		$this->assertEmpty( $feature->getStories() );

		$feature->addStory( $story );
		$this->assertNotEmpty( $feature->getStories() );
		$this->assertEquals( 1, sizeof( $feature->getStories() ) ); 

		$feature->addStory( $story );
		$this->assertNotEmpty( $feature->getStories() );
		$this->assertEquals( 1, sizeof( $feature->getStories() ) ); 
	}
	
	
	public function testAddSeveralStories()
	{
		$story1 = new Application_Model_Story( array( 'name' => 'story name 1' ) );
		$story2 = new Application_Model_Story( array( 'name' => 'story name 2' ) );
		$feature = new Application_Model_Feature( $this->_options );
		$this->assertEmpty( $feature->getStories() );

		$feature->addStory( $story1 );
		$this->assertNotEmpty( $feature->getStories() );
		$this->assertEquals( 1, sizeof( $feature->getStories() ) ); 
		
		$feature->addStory( $story2 );
		$this->assertNotEmpty( $feature->getStories() );
		$this->assertEquals( 2, sizeof( $feature->getStories() ) ); 
	}
	
	
	public function testAddArrayOfStories()
	{
		$story1 = new Application_Model_Story( array( 'name' => 'story name 1' ) );
		$story2 = new Application_Model_Story( array( 'name' => 'story name 2' ) );
		$feature = new Application_Model_Feature( $this->_options );
		$this->assertEmpty( $feature->getStories() );

		$feature->addStory( array( $story1, $story2 ) );
		$this->assertNotEmpty( $feature->getStories() );
		$this->assertEquals( 2, sizeof( $feature->getStories() ) ); 
	}
	
	
	/**
	 * @expectedException UnexpectedValueException
	 */
	public function testRemoveStoryShouldFail()
	{
		$story = new Application_Model_Story( array( 'name' => 'story name' ) );
		$feature = new Application_Model_Feature( $this->_options );

		$feature->removeStory( null );
	}
	
	
	public function testRemoveStoryShouldPass()
	{
		$story = new Application_Model_Story( array( 'name' => 'story name' ) );
		$feature = new Application_Model_Feature( $this->_options );
		$feature->addStory( $story );

		$feature->removeStory( $story );
		$this->assertEmpty( $feature->getStories() );
		$this->assertEquals( 0, sizeof( $feature->getStories() ) ); 
	}
	
	
	public function testRemoveSameStorySeveralTimes()
	{
		$story = new Application_Model_Story( array( 'name' => 'story name' ) );
		$feature = new Application_Model_Feature( $this->_options );
		$feature->addStory( $story );

		$feature->removeStory( $story );
		$this->assertEmpty( $feature->getStories() );
		$this->assertEquals( 0, sizeof( $feature->getStories() ) ); 

		$feature->removeStory( $story );
		$this->assertEmpty( $feature->getStories() );
		$this->assertEquals( 0 ,sizeof( $feature->getStories() ) ); 
	}
	
	
	public function testRemoveSeveralStories()
	{
		$story1 = new Application_Model_Story( array( 'name' => 'story name 1' ) );
		$story2 = new Application_Model_Story( array( 'name' => 'story name 2' ) );
		$feature = new Application_Model_Feature( $this->_options );
		$this->assertEmpty( $feature->getStories() );
		$feature->addStory( $story1 )
				->addStory( $story2 );

		$feature->removeStory( $story2 );
		$this->assertNotEmpty( $feature->getStories() );
		$this->assertEquals( 1, sizeof( $feature->getStories() ) ); 
		
		$feature->removeStory( $story1 );
		$this->assertEmpty( $feature->getStories() );
		$this->assertEquals( 0, sizeof( $feature->getStories() ) ); 
	}
	
	
	public function testRemoveArrayOfStories()
	{
		$story1 = new Application_Model_Story( array( 'name' => 'story name 1' ) );
		$story2 = new Application_Model_Story( array( 'name' => 'story name 2' ) );
		$feature = new Application_Model_Feature( $this->_options );
		$this->assertEmpty( $feature->getStories() );

		$feature->removeStory( array( $story1, $story2 ) );
		$this->assertEmpty( $feature->getStories() );
	}
	
	
	public function testGetStory()
	{
		$story = new Application_Model_Story( array( 'name' => 'story name ' ) );
		$feature = new Application_Model_Feature( $this->_options );
		$this->assertEmpty( $feature->getStories() );

		$feature->addStory( $story );
		$this->assertEquals( $story, $feature->getStory( 0 ) ); 
	}
	
	
	public function testToArray()
	{
		$options = array(	'id'			=> 1,
							'name'			=> 'feature name',
							'description'	=> 'feature desc.',
							'status'		=> 16,
							'release'		=> 1,
							'color'			=> 'red' );
		$feature = new Application_Model_Feature( $options );
		$this->assertEquals($options, $feature->toArray() );
	}
}
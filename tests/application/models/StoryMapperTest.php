<?php

require_once( 'application/db/AbstractTest.php' );
class Application_Model_StoryMapperTest extends Application_Db_AbstractTest
{
	protected $_initialSeedFile = 'storiesSeed.xml';
	
	
	/**
	 * CRUD Test : CREATE
	 */
	public function testCreateStory()
	{
		$data = array(	'name'			=> 'story3',
						'description'	=> 'story desc. 3',
						'status'		=> Application_Model_Status::WIP,
						'sprint'		=> 3,
						'feature'		=> 2,
						'priority'		=> 3 );

		$story = new Application_Model_Story( $data );
		$storyMapper = Application_Model_StoryMapper::getInstance();

		$storyMapper->save( $story );

		$dataSet = new Zend_Test_PHPUnit_Db_DataSet_QueryDataSet( $this->getConnection() );
		$dataSet->addTable( 'story', 'SELECT * FROM story' );

		// verify expected vs actual
		$this->assertDataSetsMatchXML( 'storiesInsertionIntoAssertion.xml', $dataSet );
	}
	
	
	/**
	 * CRUD Test : RETRIEVE
	 */
	public function testRetrieveStory()
	{
		$recordId = 2;
		
		$storyMapper = Application_Model_StoryMapper::getInstance();
		$story = $storyMapper->find( $recordId );
		
		$dataSet = $this->convertRecordToDataSet( $story->toArray(), 'story' );
		$this->assertDataSetsMatchXML( 'storiesRetrieveOneAssertion.xml', $dataSet );
	}
	
	
	/**
	 * CRUD Test : UPDATE
	 */
	public function testUpdateStory()
	{
		$recordId = 2;

		$feature2 = Application_Model_FeatureMapper::getInstance();
		$row = $feature2->find( 2 );
		$data = array(	'description'	=> 'new story desc. 2',
						'feature'		=> $row,
						'priority'		=> 2 );

		$storyMapper = Application_Model_StoryMapper::getInstance();
		$story = $storyMapper->find( $recordId );
		$story->setDescription( $data['description'] );
		$story->setFeature( $data['feature'] );
		$story->setPriority( $data['priority'] );

		$storyMapper->save( $story );

		$dataSet = new Zend_Test_PHPUnit_Db_DataSet_QueryDataSet( $this->getConnection() );
		$dataSet->addTable( 'story', 'SELECT * FROM story' );

		// verify expected vs actual
		$this->assertDataSetsMatchXML( 'storiesUpdateAssertion.xml', $dataSet );
	}
	
	
	/**
	 * CRUD Test : DELETE
	 */
	public function testDeleteStory()
	{
		$recordId = 1;

		$storyMapper = Application_Model_StoryMapper::getInstance();

		$story = $storyMapper->find( $recordId );

		// exercise
		$storyMapper->delete( $story );

		// get data from the testing database
		$dataSet = new Zend_Test_PHPUnit_Db_DataSet_QueryDataSet( $this->getConnection() );
		$dataSet->addTable( 'story', 'SELECT * FROM story' );

		// verify expected vs actual
		$this->assertDataSetsMatchXML( 'storiesDeleteAssertion.xml', $dataSet );
	}
}
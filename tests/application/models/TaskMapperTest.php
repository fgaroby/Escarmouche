<?php

require_once( 'application/db/AbstractTest.php' );
class Application_Model_TaskMapperTest extends Application_Db_AbstractTest
{
	protected $_initialSeedFile = 'tasksSeed.xml';


	/**
	 * CRUD Test : CREATE
	 */
	public function testCreateTask()
	{
		$data = array(	'name'			=> 'task3',
						'description'	=> 'task desc. 3',
						'status'		=> Application_Model_Status::WIP );

		$task = new Application_Model_Task( $data );
		$taskMapper = new Application_Model_TaskMapper();

		$taskMapper->save( $task );

		$dataSet = new Zend_Test_PHPUnit_Db_DataSet_QueryDataSet( $this->getConnection() );
		$dataSet->addTable( 'task', 'SELECT * FROM task' );

		// verify expected vs actual
		$this->assertDataSetsMatchXML( 'tasksInsertionIntoAssertion.xml', $dataSet );
	}

	
	/**
	 * CRUD Test : RETRIEVE
	 */
	public function testRetrieveOneTask()
	{
		$recordId = 2;
		
		$taskMapper = new Application_Model_TaskMapper();
		$task = $taskMapper->find( $recordId );
		
		$dataSet = $this->convertRecordToDataSet( $task->toArray(), 'task' );
		$this->assertDataSetsMatchXML( 'tasksRetrieveOneAssertion.xml', $dataSet );
	}
	

	/**
	 * CRUD Test : UPDATE
	 */
	public function testUpdateTask()
	{
		$recordId = 2;

		$data = array(	'description'	=> 'new task desc. 2' );

		$taskMapper = new Application_Model_TaskMapper();
		$task = $taskMapper->find( $recordId );
		$task->setDescription( $data['description'] );

		$taskMapper->save( $task );

		$dataSet = new Zend_Test_PHPUnit_Db_DataSet_QueryDataSet( $this->getConnection() );
		$dataSet->addTable( 'task', 'SELECT * FROM task' );

		// verify expected vs actual
		$this->assertDataSetsMatchXML( 'tasksUpdateAssertion.xml', $dataSet );
	}


	/**
	 * CRUD Test: DELETE
	 */
	public function testDeleteTask()
	{
		$recordId = 1;

		$taskMapper = new Application_Model_TaskMapper();

		$task = $taskMapper->find( $recordId );

		// exercise
		$taskMapper->delete( $task );

		// get data from the testing database
		$dataSet = new Zend_Test_PHPUnit_Db_DataSet_QueryDataSet( $this->getConnection() );
		$dataSet->addTable( 'task', 'SELECT * FROM task' );

		// verify expected vs actual
		$this->assertDataSetsMatchXML( 'tasksDeleteAssertion.xml', $dataSet );
	}
}
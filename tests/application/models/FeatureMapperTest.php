<?php

require_once( 'application/db/AbstractTest.php' );
class Application_Model_FeatureMapperTest extends Application_Db_AbstractTest
{
	protected $_initialSeedFile = 'featuresSeed.xml';
	
	
	/**
	 * CRUD Test : CREATE
	 */
	public function testCreateFeature()
	{
		$data = array(	'name'			=> 'feature3',
						'description'	=> 'feature desc. 3',
						'status'		=> Application_Model_Status::WIP,
						'color'			=> '#C6C9B3',
						'release'		=> 1 );

		$feature = new Application_Model_Feature( $data );
		Application_Model_FeatureMapper::getInstance()->save( $feature );

		$dataSet = new Zend_Test_PHPUnit_Db_DataSet_QueryDataSet( $this->getConnection() );
		$dataSet->addTable( 'feature', 'SELECT * FROM feature' );

		// verify expected vs actual
		$this->assertDataSetsMatchXML( 'featuresInsertionIntoAssertion.xml', $dataSet );
	}
	
	
	/**
	 * CRUD Test : RETRIEVE
	 */
	public function testRetrieveFeature()
	{
		$recordId = 2;
		
		$feature = Application_Model_FeatureMapper::getInstance()->find( $recordId );
		
		$dataSet = $this->convertRecordToDataSet( $feature->toArray(), 'feature' );
		$this->assertDataSetsMatchXML( 'featuresRetrieveOneAssertion.xml', $dataSet );
	}
	
	
	/**
	 * CRUD Test : UPDATE
	 */
	public function testUpdateFeature()
	{
		$recordId = 2;
		$data = array(	'description'	=> 'new feature desc. 2',
						'color'			=> 'blue' );

		$feature = Application_Model_FeatureMapper::getInstance()->find( $recordId );
		$feature->setDescription( $data['description'] );
		$feature->setColor( $data['color'] );

		$featureMapper->save( $feature );

		$dataSet = new Zend_Test_PHPUnit_Db_DataSet_QueryDataSet( $this->getConnection() );
		$dataSet->addTable( 'feature', 'SELECT * FROM feature' );

		// verify expected vs actual
		$this->assertDataSetsMatchXML( 'featuresUpdateAssertion.xml', $dataSet );
	}
	
	
	/**
	 * CRUD Test : DELETE
	 */
	public function testDeleteFeature()
	{
		$recordId = 1;

		$featureMapper = Application_Model_FeatureMapper::getInstance();

		$feature = $featureMapper->find( $recordId );

		// exercise
		$featureMapper->delete( $feature );

		// get data from the testing database
		$dataSet = new Zend_Test_PHPUnit_Db_DataSet_QueryDataSet( $this->getConnection() );
		$dataSet->addTable( 'feature', 'SELECT * FROM feature' );

		// verify expected vs actual
		$this->assertDataSetsMatchXML( 'featuresDeleteAssertion.xml', $dataSet );
	}
}
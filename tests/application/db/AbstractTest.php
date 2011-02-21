<?php
/**
 * Database test case
 *
 * Encapsulate reusable code for DB testing, providing methods to compare
 * DB results with expected data stored in XML files.
 *
 * @package Library
 * @author Devis Lucato <devis@ibuildings.com>
 * @copyright Ibuildings UK
 * @date November 2010
 */

require_once 'PHPUnit/Extensions/Database/DataSet/DefaultDataSet.php';

abstract class Application_Db_AbstractTest extends Zend_Test_PHPUnit_DatabaseTestCase
{
	const DEFAULT_CONNECTION_SCHEMA = 'main';

	/**
	 * Variable to be defined in each test case
	 *
	 * @var string
	 */
	protected $_initialSeedFile = '';

	/**
	 * Allow to override the global configuration for a particular test case
	 *
	 * @var string
	 */
	protected $_seedFilesPath = NULL;

	/**
	 * Parameter for Zend_Test_PHPUnit_Db_Connection
	 *
	 * @var string
	 */
	protected $_connectionSchema = self::DEFAULT_CONNECTION_SCHEMA;

	/**
	 * Connection to testing database
	 *
	 * @var Zend_Test_PHPUnit_Db_Connection
	 */
	protected $_connectionMock;

	/**
	 * Application configuration
	 *
	 * @var Zend_Config_Ini
	 */
	private $__configuration = NULL;

	/**
	 * Returns the application configuration
	 *
	 * @return Zend_Config_Ini
	 */
	public function getConfiguration()
	{
		if( $this->__configuration == NULL )
			$this->__configuration = new Zend_Config_Ini( APPLICATION_PATH . '/configs/tests.ini');

		return $this->__configuration;
	}

	/**
	 * Returns the seed files folder path
	 *
	 * @return string
	 */
	public function getSeedFilesPath()
	{
		if ($this->_seedFilesPath == NULL) {
			$this->_seedFilesPath = $this->getConfiguration()->tests->seeds->folder;
		}

		return rtrim($this->_seedFilesPath, '/') . '/';
	}

	/**
	 * Returns the test database connection.
	 *
	 * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
	 */
	protected function getConnection()
	{
		if( $this->_connectionMock == NULL )
		{
			$dbAdapterName = $this->getConfiguration()->tests->dbadapter;
			$dbAdapterParams = $this->getConfiguration()->tests->toArray();

			$connection = Zend_Db::factory( $dbAdapterName, $dbAdapterParams );

			$this->_connectionMock = $this->createZendDbConnection( $connection, $this->_connectionSchema );
		}
		
		return $this->_connectionMock;
	}
	

	/**
	 * Retrieve from flat XML files data used to populate the database
	 *
	 * @return PHPUnit_Extensions_Database_DataSet_IDataSet
	 */
	protected function getDataSet()
	{
		return $this->createFlatXmlDataSet( $this->getSeedFilesPath() . $this->_initialSeedFile );
	}
	

	/**
	 * Convert a Rowset to a Dataset
	 *
	 * @param  Zend_Db_Table_Rowset_Abstract $rowset
	 * @param  string $tableName
	 * @return PHPUnit_Extensions_Database_DataSet_DefaultDataSet
	 */
	public function convertRowsetToDataSet($rowset, $tableName = NULL)
	{
		$rowsetDataSet = new Zend_Test_PHPUnit_Db_DataSet_DbRowset($rowset, $tableName);
		return new PHPUnit_Extensions_Database_DataSet_DefaultDataSet(array($rowsetDataSet));
	}

	/**
	 * Convert a Record to a Dataset
	 *
	 * @param  array $data
	 * @param  string $tableName
	 * @return PHPUnit_Extensions_Database_DataSet_DefaultDataSet
	 */
	public function convertRecordToDataSet(Array $data, $tableName)
	{
		$rowset = new Zend_Db_Table_Rowset(array('data' => array($data)));
		return $this->convertRowsetToDataSet($rowset, $tableName);
	}

	/**
	 * Compare dataset with data stored in the file
	 *
	 * @param  string $filename
	 * @param  PHPUnit_Extensions_Database_DataSet_IDataSet $expected
	 * @return boolean
	 */
	public function assertDataSetsMatchXML($filename, PHPUnit_Extensions_Database_DataSet_IDataSet $actual)
	{
		if (empty($filename) || !is_string($filename))
		throw new InvalidArgumentException( 'Second parameter "filename" is not a valid string.' );

                  $expected = $this->createFlatXmlDataSet($this->getSeedFilesPath() . $filename);

                  return $this->assertDataSetsEqual($expected, $actual);
	}
}
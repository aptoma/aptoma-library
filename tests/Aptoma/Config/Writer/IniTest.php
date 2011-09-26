<?php
class Aptoma_Config_Writer_IniTest extends PHPUnit_Framework_TestCase
{
	private $tmpFile;
	private $tmpPath;

	public function setUp()
	{
		$this->tmpFile = '/tmp/IniWriterTest' . uniqid() . '.ini';
		$this->tmpPath = '/tmp/' . uniqid();
	}

	/**
	 * @dataProvider configProvider
	 */
	public function testBackupDefault($config)
	{
		$options = array(
			'filename' => $this->tmpFile,
			'config' => $config
		);

		$writer = new Aptoma_Config_Writer_Ini($options);
		$writer->write();

		//check written config
		$loadedConfig = new Zend_Config_Ini($this->tmpFile);
		$this->assertEquals($config->toArray(), $loadedConfig->toArray());

		$writer->write(); //doing backup

		//check backup
		$backupConfig = new Zend_Config_Ini($writer->getLastBackupFilename());
		$this->assertEquals($loadedConfig->toArray(), $backupConfig->toArray());

		@unlink($writer->getLastBackupFilename());
	}

	/**
	 * @dataProvider configProvider
	 */
	public function testBackupDisabled($config)
	{
		$options = array(
			'filename' => $this->tmpFile,
			'config' => $config,
			'backupEnabled' => false
		);

		$writer = new Aptoma_Config_Writer_Ini($options);

		$writer->write();
		//no backup file should have been created
		$this->assertNull($writer->getLastBackupFilename());

		$loadedConfig = new Zend_Config_Ini($this->tmpFile);
		$this->assertEquals($config->toArray(), $loadedConfig->toArray());

		$config->g = 'h';

		$writer->setBackupEnabled(true);
		//disable backup on write function
		$writer->write($options['filename'], $config, null, false);

		//no backup file should have been created
		$this->assertNull($writer->getLastBackupFilename());

		$modifiedConfig = new Zend_Config_Ini($this->tmpFile);
		$this->assertEquals($config->toArray(), $modifiedConfig->toArray());
	}

	/**
	 * @dataProvider configProvider
	 */
	public function testBackupPath($config)
	{
		$options = array(
			'filename' => $this->tmpFile,
			'config' => $config,
			'backupPath' => $this->tmpPath
		);

		mkdir($this->tmpPath);

		$writer = new Aptoma_Config_Writer_Ini($options);
		$writer->write();

		//creating backup
		$writer->write();

		$loadedConfig = new Zend_Config_Ini($writer->getLastBackupFilename());
		$this->assertEquals($config->toArray(), $loadedConfig->toArray());

		$this->assertEquals($this->tmpPath, dirname($writer->getLastBackupFilename()));

		@unlink($writer->getLastBackupFilename());
	}

	public static function configProvider()
	{
		$config = new Zend_Config(array(
			'a' => 'b',
			'c' => 'd',
			'e' => 'f'
		), true);

		return array(array($config));
	}

	public function tearDown()
	{
		@unlink($this->tmpFile);
		@unlink($this->tmpPath . '/' . basename($this->tmpFile));
		@rmdir($this->tmpPath);
	}
}
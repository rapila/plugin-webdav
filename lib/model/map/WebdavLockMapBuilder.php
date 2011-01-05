<?php

require_once 'propel/map/MapBuilder.php';
include_once 'creole/CreoleTypes.php';


/**
 * This class adds structure of 'webdav_locks' table to 'mini_cms' DatabaseMap object.
 *
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    model.map
 */
class WebdavLockMapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'model.map.WebdavLockMapBuilder';

	/**
	 * The database map.
	 */
	private $dbMap;

	/**
	 * Tells us if this DatabaseMapBuilder is built so that we
	 * don't have to re-build it every time.
	 *
	 * @return     boolean true if this DatabaseMapBuilder is built, false otherwise.
	 */
	public function isBuilt()
	{
		return ($this->dbMap !== null);
	}

	/**
	 * Gets the databasemap this map builder built.
	 *
	 * @return     the databasemap
	 */
	public function getDatabaseMap()
	{
		return $this->dbMap;
	}

	/**
	 * The doBuild() method builds the DatabaseMap
	 *
	 * @return     void
	 * @throws     PropelException
	 */
	public function doBuild()
	{
		$this->dbMap = Propel::getDatabaseMap('mini_cms');

		$tMap = $this->dbMap->addTable('webdav_locks');
		$tMap->setPhpName('WebdavLock');

		$tMap->setUseIdGenerator(false);

		$tMap->addPrimaryKey('TOKEN', 'Token', 'string', CreoleTypes::VARCHAR, true, 255);

		$tMap->addColumn('PATH', 'Path', 'string', CreoleTypes::VARCHAR, false, 200);

		$tMap->addForeignKey('OWNER', 'Owner', 'int', CreoleTypes::INTEGER, 'users', 'ID', true, null);

		$tMap->addColumn('IS_RECURSIVE', 'IsRecursive', 'boolean', CreoleTypes::BOOLEAN, false, 1);

		$tMap->addColumn('IS_EXCLUSIVE', 'IsExclusive', 'boolean', CreoleTypes::BOOLEAN, false, 1);

		$tMap->addColumn('CREATED_AT', 'CreatedAt', 'int', CreoleTypes::TIMESTAMP, false, null);

		$tMap->addColumn('UPDATED_AT', 'UpdatedAt', 'int', CreoleTypes::TIMESTAMP, false, null);

		$tMap->addColumn('EXPIRES_AT', 'ExpiresAt', 'int', CreoleTypes::TIMESTAMP, false, null);

	} // doBuild()

} // WebdavLockMapBuilder

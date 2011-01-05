<?php


/**
 * This class defines the structure of the 'webdav_locks' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.model.map
 */
class WebdavLockTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'model.map.WebdavLockTableMap';

	/**
	 * Initialize the table attributes, columns and validators
	 * Relations are not initialized by this method since they are lazy loaded
	 *
	 * @return     void
	 * @throws     PropelException
	 */
	public function initialize()
	{
	  // attributes
		$this->setName('webdav_locks');
		$this->setPhpName('WebdavLock');
		$this->setClassname('WebdavLock');
		$this->setPackage('model');
		$this->setUseIdGenerator(false);
		// columns
		$this->addPrimaryKey('TOKEN', 'Token', 'VARCHAR', true, 255, null);
		$this->addColumn('PATH', 'Path', 'VARCHAR', false, 200, null);
		$this->addForeignKey('OWNER', 'Owner', 'INTEGER', 'users', 'ID', true, null, null);
		$this->addColumn('IS_RECURSIVE', 'IsRecursive', 'BOOLEAN', false, 1, false);
		$this->addColumn('IS_EXCLUSIVE', 'IsExclusive', 'BOOLEAN', false, 1, false);
		$this->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null, null);
		$this->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null, null);
		$this->addColumn('EXPIRES_AT', 'ExpiresAt', 'TIMESTAMP', false, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('User', 'User', RelationMap::MANY_TO_ONE, array('owner' => 'id', ), null, null);
	} // buildRelations()

	/**
	 * 
	 * Gets the list of behaviors registered for this table
	 * 
	 * @return array Associative array (name => parameters) of behaviors
	 */
	public function getBehaviors()
	{
		return array(
			'extended_timestampable' => array(),
			'attributable' => array(),
		);
	} // getBehaviors()

} // WebdavLockTableMap

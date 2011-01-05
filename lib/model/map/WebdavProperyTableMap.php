<?php


/**
 * This class defines the structure of the 'webdav_properties' table.
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
class WebdavProperyTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'model.map.WebdavProperyTableMap';

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
		$this->setName('webdav_properties');
		$this->setPhpName('WebdavPropery');
		$this->setClassname('WebdavPropery');
		$this->setPackage('model');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
		$this->addColumn('PATH', 'Path', 'VARCHAR', true, 255, '');
		$this->addColumn('NAME', 'Name', 'VARCHAR', true, 60, '');
		$this->addColumn('NS', 'Ns', 'VARCHAR', true, 10, 'DAV:');
		$this->addColumn('VALUE', 'Value', 'LONGVARCHAR', false, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
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

} // WebdavProperyTableMap

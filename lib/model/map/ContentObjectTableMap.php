<?php



/**
 * This class defines the structure of the 'objects' table.
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
class ContentObjectTableMap extends TableMap
{

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'model.map.ContentObjectTableMap';

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
		$this->setName('objects');
		$this->setPhpName('ContentObject');
		$this->setClassname('ContentObject');
		$this->setPackage('model');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
		$this->addForeignKey('PAGE_ID', 'PageId', 'INTEGER', 'pages', 'ID', true, null, null);
		$this->addColumn('CONTAINER_NAME', 'ContainerName', 'VARCHAR', false, 50, null);
		$this->addColumn('OBJECT_TYPE', 'ObjectType', 'VARCHAR', false, 50, null);
		$this->addColumn('CONDITION_SERIALIZED', 'ConditionSerialized', 'BLOB', false, null, null);
		$this->addColumn('SORT', 'Sort', 'TINYINT', false, 3, null);
		$this->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null, null);
		$this->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null, null);
		$this->addForeignKey('CREATED_BY', 'CreatedBy', 'INTEGER', 'users', 'ID', false, null, null);
		$this->addForeignKey('UPDATED_BY', 'UpdatedBy', 'INTEGER', 'users', 'ID', false, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
		$this->addRelation('Page', 'Page', RelationMap::MANY_TO_ONE, array('page_id' => 'id', ), 'CASCADE', null);
		$this->addRelation('UserRelatedByCreatedBy', 'User', RelationMap::MANY_TO_ONE, array('created_by' => 'id', ), 'SET NULL', null);
		$this->addRelation('UserRelatedByUpdatedBy', 'User', RelationMap::MANY_TO_ONE, array('updated_by' => 'id', ), 'SET NULL', null);
		$this->addRelation('LanguageObject', 'LanguageObject', RelationMap::ONE_TO_MANY, array('id' => 'object_id', ), 'CASCADE', null, 'LanguageObjects');
		$this->addRelation('LanguageObjectHistory', 'LanguageObjectHistory', RelationMap::ONE_TO_MANY, array('id' => 'object_id', ), 'CASCADE', null, 'LanguageObjectHistorys');
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
			'extended_timestampable' => array('create_column' => 'created_at', 'update_column' => 'updated_at', ),
			'denyable' => array('mode' => 'allow', 'role_key' => '', ),
			'attributable' => array('create_column' => 'created_by', 'update_column' => 'updated_by', ),
		);
	} // getBehaviors()

} // ContentObjectTableMap

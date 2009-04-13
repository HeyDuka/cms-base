<?php

require_once 'propel/om/BaseObject.php';

require_once 'propel/om/Persistent.php';

include_once 'creole/util/Clob.php';
include_once 'creole/util/Blob.php';


include_once 'propel/util/Criteria.php';

include_once 'model/ContentObjectPeer.php';

/**
 * Base class that represents a row from the 'objects' table.
 *
 * 
 *
 * @package    model.om
 */
abstract class BaseContentObject extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        ContentObjectPeer
	 */
	protected static $peer;


	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;


	/**
	 * The value for the page_id field.
	 * @var        int
	 */
	protected $page_id;


	/**
	 * The value for the container_name field.
	 * @var        string
	 */
	protected $container_name;


	/**
	 * The value for the object_type field.
	 * @var        string
	 */
	protected $object_type;


	/**
	 * The value for the condition_serialized field.
	 * @var        string
	 */
	protected $condition_serialized;


	/**
	 * The value for the sort field.
	 * @var        int
	 */
	protected $sort;

	/**
	 * @var        Page
	 */
	protected $aPage;

	/**
	 * Collection to store aggregation of collLanguageObjects.
	 * @var        array
	 */
	protected $collLanguageObjects;

	/**
	 * The criteria used to select the current contents of collLanguageObjects.
	 * @var        Criteria
	 */
	protected $lastLanguageObjectCriteria = null;

	/**
	 * Collection to store aggregation of collLanguageObjectHistorys.
	 * @var        array
	 */
	protected $collLanguageObjectHistorys;

	/**
	 * The criteria used to select the current contents of collLanguageObjectHistorys.
	 * @var        Criteria
	 */
	protected $lastLanguageObjectHistoryCriteria = null;

	/**
	 * Flag to prevent endless save loop, if this object is referenced
	 * by another object which falls in this transaction.
	 * @var        boolean
	 */
	protected $alreadyInSave = false;

	/**
	 * Flag to prevent endless validation loop, if this object is referenced
	 * by another object which falls in this transaction.
	 * @var        boolean
	 */
	protected $alreadyInValidation = false;

	/**
	 * Get the [id] column value.
	 * 
	 * @return     int
	 */
	public function getId()
	{

		return $this->id;
	}

	/**
	 * Get the [page_id] column value.
	 * 
	 * @return     int
	 */
	public function getPageId()
	{

		return $this->page_id;
	}

	/**
	 * Get the [container_name] column value.
	 * 
	 * @return     string
	 */
	public function getContainerName()
	{

		return $this->container_name;
	}

	/**
	 * Get the [object_type] column value.
	 * 
	 * @return     string
	 */
	public function getObjectType()
	{

		return $this->object_type;
	}

	/**
	 * Get the [condition_serialized] column value.
	 * 
	 * @return     string
	 */
	public function getConditionSerialized()
	{

		return $this->condition_serialized;
	}

	/**
	 * Get the [sort] column value.
	 * 
	 * @return     int
	 */
	public function getSort()
	{

		return $this->sort;
	}

	/**
	 * Set the value of [id] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setId($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = ContentObjectPeer::ID;
		}

	} // setId()

	/**
	 * Set the value of [page_id] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setPageId($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->page_id !== $v) {
			$this->page_id = $v;
			$this->modifiedColumns[] = ContentObjectPeer::PAGE_ID;
		}

		if ($this->aPage !== null && $this->aPage->getId() !== $v) {
			$this->aPage = null;
		}

	} // setPageId()

	/**
	 * Set the value of [container_name] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setContainerName($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->container_name !== $v) {
			$this->container_name = $v;
			$this->modifiedColumns[] = ContentObjectPeer::CONTAINER_NAME;
		}

	} // setContainerName()

	/**
	 * Set the value of [object_type] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setObjectType($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->object_type !== $v) {
			$this->object_type = $v;
			$this->modifiedColumns[] = ContentObjectPeer::OBJECT_TYPE;
		}

	} // setObjectType()

	/**
	 * Set the value of [condition_serialized] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setConditionSerialized($v)
	{

		// if the passed in parameter is the *same* object that
		// is stored internally then we use the Lob->isModified()
		// method to know whether contents changed.
		if ($v instanceof Lob && $v === $this->condition_serialized) {
			$changed = $v->isModified();
		} else {
			$changed = ($this->condition_serialized !== $v);
		}
		if ($changed) {
			if ( !($v instanceof Lob) ) {
				$obj = new Blob();
				$obj->setContents($v);
			} else {
				$obj = $v;
			}
			$this->condition_serialized = $obj;
			$this->modifiedColumns[] = ContentObjectPeer::CONDITION_SERIALIZED;
		}

	} // setConditionSerialized()

	/**
	 * Set the value of [sort] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setSort($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->sort !== $v) {
			$this->sort = $v;
			$this->modifiedColumns[] = ContentObjectPeer::SORT;
		}

	} // setSort()

	/**
	 * Hydrates (populates) the object variables with values from the database resultset.
	 *
	 * An offset (1-based "start column") is specified so that objects can be hydrated
	 * with a subset of the columns in the resultset rows.  This is needed, for example,
	 * for results of JOIN queries where the resultset row includes columns from two or
	 * more tables.
	 *
	 * @param      ResultSet $rs The ResultSet class with cursor advanced to desired record pos.
	 * @param      int $startcol 1-based offset column which indicates which restultset column to start with.
	 * @return     int next starting column
	 * @throws     PropelException  - Any caught Exception will be rewrapped as a PropelException.
	 */
	public function hydrate(ResultSet $rs, $startcol = 1)
	{
		try {

			$this->id = $rs->getInt($startcol + 0);

			$this->page_id = $rs->getInt($startcol + 1);

			$this->container_name = $rs->getString($startcol + 2);

			$this->object_type = $rs->getString($startcol + 3);

			$this->condition_serialized = $rs->getBlob($startcol + 4);

			$this->sort = $rs->getInt($startcol + 5);

			$this->resetModified();

			$this->setNew(false);

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 6; // 6 = ContentObjectPeer::NUM_COLUMNS - ContentObjectPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating ContentObject object", $e);
		}
	}

	/**
	 * Removes this object from datastore and sets delete attribute.
	 *
	 * @param      Connection $con
	 * @return     void
	 * @throws     PropelException
	 * @see        BaseObject::setDeleted()
	 * @see        BaseObject::isDeleted()
	 */
	public function delete($con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(ContentObjectPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			ContentObjectPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	/**
	 * Stores the object in the database.  If the object is new,
	 * it inserts it; otherwise an update is performed.  This method
	 * wraps the doSave() worker method in a transaction.
	 *
	 * @param      Connection $con
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws     PropelException
	 * @see        doSave()
	 */
	public function save($con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(ContentObjectPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			$affectedRows = $this->doSave($con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	/**
	 * Stores the object in the database.
	 *
	 * If the object is new, it inserts it; otherwise an update is performed.
	 * All related objects are also updated in this method.
	 *
	 * @param      Connection $con
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws     PropelException
	 * @see        save()
	 */
	protected function doSave($con)
	{
		$affectedRows = 0; // initialize var to track total num of affected rows
		if (!$this->alreadyInSave) {
			$this->alreadyInSave = true;


			// We call the save method on the following object(s) if they
			// were passed to this object by their coresponding set
			// method.  This object relates to these object(s) by a
			// foreign key reference.

			if ($this->aPage !== null) {
				if ($this->aPage->isModified()) {
					$affectedRows += $this->aPage->save($con);
				}
				$this->setPage($this->aPage);
			}


			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = ContentObjectPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += ContentObjectPeer::doUpdate($this, $con);
				}
				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collLanguageObjects !== null) {
				foreach($this->collLanguageObjects as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collLanguageObjectHistorys !== null) {
				foreach($this->collLanguageObjectHistorys as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			$this->alreadyInSave = false;
		}
		return $affectedRows;
	} // doSave()

	/**
	 * Array of ValidationFailed objects.
	 * @var        array ValidationFailed[]
	 */
	protected $validationFailures = array();

	/**
	 * Gets any ValidationFailed objects that resulted from last call to validate().
	 *
	 *
	 * @return     array ValidationFailed[]
	 * @see        validate()
	 */
	public function getValidationFailures()
	{
		return $this->validationFailures;
	}

	/**
	 * Validates the objects modified field values and all objects related to this table.
	 *
	 * If $columns is either a column name or an array of column names
	 * only those columns are validated.
	 *
	 * @param      mixed $columns Column name or an array of column names.
	 * @return     boolean Whether all columns pass validation.
	 * @see        doValidate()
	 * @see        getValidationFailures()
	 */
	public function validate($columns = null)
	{
		$res = $this->doValidate($columns);
		if ($res === true) {
			$this->validationFailures = array();
			return true;
		} else {
			$this->validationFailures = $res;
			return false;
		}
	}

	/**
	 * This function performs the validation work for complex object models.
	 *
	 * In addition to checking the current object, all related objects will
	 * also be validated.  If all pass then <code>true</code> is returned; otherwise
	 * an aggreagated array of ValidationFailed objects will be returned.
	 *
	 * @param      array $columns Array of column names to validate.
	 * @return     mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objets otherwise.
	 */
	protected function doValidate($columns = null)
	{
		if (!$this->alreadyInValidation) {
			$this->alreadyInValidation = true;
			$retval = null;

			$failureMap = array();


			// We call the validate method on the following object(s) if they
			// were passed to this object by their coresponding set
			// method.  This object relates to these object(s) by a
			// foreign key reference.

			if ($this->aPage !== null) {
				if (!$this->aPage->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aPage->getValidationFailures());
				}
			}


			if (($retval = ContentObjectPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collLanguageObjects !== null) {
					foreach($this->collLanguageObjects as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collLanguageObjectHistorys !== null) {
					foreach($this->collLanguageObjectHistorys as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}


			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	/**
	 * Retrieves a field from the object by name passed in as a string.
	 *
	 * @param      string $name name
	 * @param      string $type The type of fieldname the $name is of:
	 *                     one of the class type constants TYPE_PHPNAME,
	 *                     TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
	 * @return     mixed Value of field.
	 */
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = ContentObjectPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->getByPosition($pos);
	}

	/**
	 * Retrieves a field from the object by Position as specified in the xml schema.
	 * Zero-based.
	 *
	 * @param      int $pos position in xml schema
	 * @return     mixed Value of field at $pos
	 */
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getId();
				break;
			case 1:
				return $this->getPageId();
				break;
			case 2:
				return $this->getContainerName();
				break;
			case 3:
				return $this->getObjectType();
				break;
			case 4:
				return $this->getConditionSerialized();
				break;
			case 5:
				return $this->getSort();
				break;
			default:
				return null;
				break;
		} // switch()
	}

	/**
	 * Exports the object as an array.
	 *
	 * You can specify the key type of the array by passing one of the class
	 * type constants.
	 *
	 * @param      string $keyType One of the class type constants TYPE_PHPNAME,
	 *                        TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
	 * @return     an associative array containing the field names (as keys) and field values
	 */
	public function toArray($keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = ContentObjectPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getPageId(),
			$keys[2] => $this->getContainerName(),
			$keys[3] => $this->getObjectType(),
			$keys[4] => $this->getConditionSerialized(),
			$keys[5] => $this->getSort(),
		);
		return $result;
	}

	/**
	 * Sets a field from the object by name passed in as a string.
	 *
	 * @param      string $name peer name
	 * @param      mixed $value field value
	 * @param      string $type The type of fieldname the $name is of:
	 *                     one of the class type constants TYPE_PHPNAME,
	 *                     TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
	 * @return     void
	 */
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = ContentObjectPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	/**
	 * Sets a field from the object by Position as specified in the xml schema.
	 * Zero-based.
	 *
	 * @param      int $pos position in xml schema
	 * @param      mixed $value field value
	 * @return     void
	 */
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setId($value);
				break;
			case 1:
				$this->setPageId($value);
				break;
			case 2:
				$this->setContainerName($value);
				break;
			case 3:
				$this->setObjectType($value);
				break;
			case 4:
				$this->setConditionSerialized($value);
				break;
			case 5:
				$this->setSort($value);
				break;
		} // switch()
	}

	/**
	 * Populates the object using an array.
	 *
	 * This is particularly useful when populating an object from one of the
	 * request arrays (e.g. $_POST).  This method goes through the column
	 * names, checking to see whether a matching key exists in populated
	 * array. If so the setByName() method is called for that column.
	 *
	 * You can specify the key type of the array by additionally passing one
	 * of the class type constants TYPE_PHPNAME, TYPE_COLNAME, TYPE_FIELDNAME,
	 * TYPE_NUM. The default key type is the column's phpname (e.g. 'authorId')
	 *
	 * @param      array  $arr     An array to populate the object from.
	 * @param      string $keyType The type of keys the array uses.
	 * @return     void
	 */
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = ContentObjectPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setPageId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setContainerName($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setObjectType($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setConditionSerialized($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setSort($arr[$keys[5]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(ContentObjectPeer::DATABASE_NAME);

		if ($this->isColumnModified(ContentObjectPeer::ID)) $criteria->add(ContentObjectPeer::ID, $this->id);
		if ($this->isColumnModified(ContentObjectPeer::PAGE_ID)) $criteria->add(ContentObjectPeer::PAGE_ID, $this->page_id);
		if ($this->isColumnModified(ContentObjectPeer::CONTAINER_NAME)) $criteria->add(ContentObjectPeer::CONTAINER_NAME, $this->container_name);
		if ($this->isColumnModified(ContentObjectPeer::OBJECT_TYPE)) $criteria->add(ContentObjectPeer::OBJECT_TYPE, $this->object_type);
		if ($this->isColumnModified(ContentObjectPeer::CONDITION_SERIALIZED)) $criteria->add(ContentObjectPeer::CONDITION_SERIALIZED, $this->condition_serialized);
		if ($this->isColumnModified(ContentObjectPeer::SORT)) $criteria->add(ContentObjectPeer::SORT, $this->sort);

		return $criteria;
	}

	/**
	 * Builds a Criteria object containing the primary key for this object.
	 *
	 * Unlike buildCriteria() this method includes the primary key values regardless
	 * of whether or not they have been modified.
	 *
	 * @return     Criteria The Criteria object containing value(s) for primary key(s).
	 */
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(ContentObjectPeer::DATABASE_NAME);

		$criteria->add(ContentObjectPeer::ID, $this->id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getId();
	}

	/**
	 * Generic method to set the primary key (id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setId($key);
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of ContentObject (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setPageId($this->page_id);

		$copyObj->setContainerName($this->container_name);

		$copyObj->setObjectType($this->object_type);

		$copyObj->setConditionSerialized($this->condition_serialized);

		$copyObj->setSort($this->sort);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach($this->getLanguageObjects() as $relObj) {
				$copyObj->addLanguageObject($relObj->copy($deepCopy));
			}

			foreach($this->getLanguageObjectHistorys() as $relObj) {
				$copyObj->addLanguageObjectHistory($relObj->copy($deepCopy));
			}

		} // if ($deepCopy)


		$copyObj->setNew(true);

		$copyObj->setId(NULL); // this is a pkey column, so set to default value

	}

	/**
	 * Makes a copy of this object that will be inserted as a new row in table when saved.
	 * It creates a new object filling in the simple attributes, but skipping any primary
	 * keys that are defined for the table.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @return     ContentObject Clone of current object.
	 * @throws     PropelException
	 */
	public function copy($deepCopy = false)
	{
		// we use get_class(), because this might be a subclass
		$clazz = get_class($this);
		$copyObj = new $clazz();
		$this->copyInto($copyObj, $deepCopy);
		return $copyObj;
	}

	/**
	 * Returns a peer instance associated with this om.
	 *
	 * Since Peer classes are not to have any instance attributes, this method returns the
	 * same instance for all member of this class. The method could therefore
	 * be static, but this would prevent one from overriding the behavior.
	 *
	 * @return     ContentObjectPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new ContentObjectPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a Page object.
	 *
	 * @param      Page $v
	 * @return     void
	 * @throws     PropelException
	 */
	public function setPage($v)
	{


		if ($v === null) {
			$this->setPageId(NULL);
		} else {
			$this->setPageId($v->getId());
		}


		$this->aPage = $v;
	}


	/**
	 * Get the associated Page object
	 *
	 * @param      Connection Optional Connection object.
	 * @return     Page The associated Page object.
	 * @throws     PropelException
	 */
	public function getPage($con = null)
	{
		// include the related Peer class
		include_once 'model/om/BasePagePeer.php';

		if ($this->aPage === null && ($this->page_id !== null)) {

			$this->aPage = PagePeer::retrieveByPK($this->page_id, $con);

			/* The following can be used instead of the line above to
			   guarantee the related object contains a reference
			   to this object, but this level of coupling
			   may be undesirable in many circumstances.
			   As it can lead to a db query with many results that may
			   never be used.
			   $obj = PagePeer::retrieveByPK($this->page_id, $con);
			   $obj->addPages($this);
			 */
		}
		return $this->aPage;
	}

	/**
	 * Temporary storage of collLanguageObjects to save a possible db hit in
	 * the event objects are add to the collection, but the
	 * complete collection is never requested.
	 * @return     void
	 */
	public function initLanguageObjects()
	{
		if ($this->collLanguageObjects === null) {
			$this->collLanguageObjects = array();
		}
	}

	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this ContentObject has previously
	 * been saved, it will retrieve related LanguageObjects from storage.
	 * If this ContentObject is new, it will return
	 * an empty collection or the current collection, the criteria
	 * is ignored on a new object.
	 *
	 * @param      Connection $con
	 * @param      Criteria $criteria
	 * @throws     PropelException
	 */
	public function getLanguageObjects($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'model/om/BaseLanguageObjectPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collLanguageObjects === null) {
			if ($this->isNew()) {
			   $this->collLanguageObjects = array();
			} else {

				$criteria->add(LanguageObjectPeer::OBJECT_ID, $this->getId());

				LanguageObjectPeer::addSelectColumns($criteria);
				$this->collLanguageObjects = LanguageObjectPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(LanguageObjectPeer::OBJECT_ID, $this->getId());

				LanguageObjectPeer::addSelectColumns($criteria);
				if (!isset($this->lastLanguageObjectCriteria) || !$this->lastLanguageObjectCriteria->equals($criteria)) {
					$this->collLanguageObjects = LanguageObjectPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastLanguageObjectCriteria = $criteria;
		return $this->collLanguageObjects;
	}

	/**
	 * Returns the number of related LanguageObjects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      Connection $con
	 * @throws     PropelException
	 */
	public function countLanguageObjects($criteria = null, $distinct = false, $con = null)
	{
		// include the Peer class
		include_once 'model/om/BaseLanguageObjectPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(LanguageObjectPeer::OBJECT_ID, $this->getId());

		return LanguageObjectPeer::doCount($criteria, $distinct, $con);
	}

	/**
	 * Method called to associate a LanguageObject object to this object
	 * through the LanguageObject foreign key attribute
	 *
	 * @param      LanguageObject $l LanguageObject
	 * @return     void
	 * @throws     PropelException
	 */
	public function addLanguageObject(LanguageObject $l)
	{
		$this->collLanguageObjects[] = $l;
		$l->setContentObject($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this ContentObject is new, it will return
	 * an empty collection; or if this ContentObject has previously
	 * been saved, it will retrieve related LanguageObjects from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in ContentObject.
	 */
	public function getLanguageObjectsJoinLanguage($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'model/om/BaseLanguageObjectPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collLanguageObjects === null) {
			if ($this->isNew()) {
				$this->collLanguageObjects = array();
			} else {

				$criteria->add(LanguageObjectPeer::OBJECT_ID, $this->getId());

				$this->collLanguageObjects = LanguageObjectPeer::doSelectJoinLanguage($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(LanguageObjectPeer::OBJECT_ID, $this->getId());

			if (!isset($this->lastLanguageObjectCriteria) || !$this->lastLanguageObjectCriteria->equals($criteria)) {
				$this->collLanguageObjects = LanguageObjectPeer::doSelectJoinLanguage($criteria, $con);
			}
		}
		$this->lastLanguageObjectCriteria = $criteria;

		return $this->collLanguageObjects;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this ContentObject is new, it will return
	 * an empty collection; or if this ContentObject has previously
	 * been saved, it will retrieve related LanguageObjects from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in ContentObject.
	 */
	public function getLanguageObjectsJoinUserRelatedByCreatedBy($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'model/om/BaseLanguageObjectPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collLanguageObjects === null) {
			if ($this->isNew()) {
				$this->collLanguageObjects = array();
			} else {

				$criteria->add(LanguageObjectPeer::OBJECT_ID, $this->getId());

				$this->collLanguageObjects = LanguageObjectPeer::doSelectJoinUserRelatedByCreatedBy($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(LanguageObjectPeer::OBJECT_ID, $this->getId());

			if (!isset($this->lastLanguageObjectCriteria) || !$this->lastLanguageObjectCriteria->equals($criteria)) {
				$this->collLanguageObjects = LanguageObjectPeer::doSelectJoinUserRelatedByCreatedBy($criteria, $con);
			}
		}
		$this->lastLanguageObjectCriteria = $criteria;

		return $this->collLanguageObjects;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this ContentObject is new, it will return
	 * an empty collection; or if this ContentObject has previously
	 * been saved, it will retrieve related LanguageObjects from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in ContentObject.
	 */
	public function getLanguageObjectsJoinUserRelatedByUpdatedBy($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'model/om/BaseLanguageObjectPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collLanguageObjects === null) {
			if ($this->isNew()) {
				$this->collLanguageObjects = array();
			} else {

				$criteria->add(LanguageObjectPeer::OBJECT_ID, $this->getId());

				$this->collLanguageObjects = LanguageObjectPeer::doSelectJoinUserRelatedByUpdatedBy($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(LanguageObjectPeer::OBJECT_ID, $this->getId());

			if (!isset($this->lastLanguageObjectCriteria) || !$this->lastLanguageObjectCriteria->equals($criteria)) {
				$this->collLanguageObjects = LanguageObjectPeer::doSelectJoinUserRelatedByUpdatedBy($criteria, $con);
			}
		}
		$this->lastLanguageObjectCriteria = $criteria;

		return $this->collLanguageObjects;
	}

	/**
	 * Temporary storage of collLanguageObjectHistorys to save a possible db hit in
	 * the event objects are add to the collection, but the
	 * complete collection is never requested.
	 * @return     void
	 */
	public function initLanguageObjectHistorys()
	{
		if ($this->collLanguageObjectHistorys === null) {
			$this->collLanguageObjectHistorys = array();
		}
	}

	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this ContentObject has previously
	 * been saved, it will retrieve related LanguageObjectHistorys from storage.
	 * If this ContentObject is new, it will return
	 * an empty collection or the current collection, the criteria
	 * is ignored on a new object.
	 *
	 * @param      Connection $con
	 * @param      Criteria $criteria
	 * @throws     PropelException
	 */
	public function getLanguageObjectHistorys($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'model/om/BaseLanguageObjectHistoryPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collLanguageObjectHistorys === null) {
			if ($this->isNew()) {
			   $this->collLanguageObjectHistorys = array();
			} else {

				$criteria->add(LanguageObjectHistoryPeer::OBJECT_ID, $this->getId());

				LanguageObjectHistoryPeer::addSelectColumns($criteria);
				$this->collLanguageObjectHistorys = LanguageObjectHistoryPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(LanguageObjectHistoryPeer::OBJECT_ID, $this->getId());

				LanguageObjectHistoryPeer::addSelectColumns($criteria);
				if (!isset($this->lastLanguageObjectHistoryCriteria) || !$this->lastLanguageObjectHistoryCriteria->equals($criteria)) {
					$this->collLanguageObjectHistorys = LanguageObjectHistoryPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastLanguageObjectHistoryCriteria = $criteria;
		return $this->collLanguageObjectHistorys;
	}

	/**
	 * Returns the number of related LanguageObjectHistorys.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      Connection $con
	 * @throws     PropelException
	 */
	public function countLanguageObjectHistorys($criteria = null, $distinct = false, $con = null)
	{
		// include the Peer class
		include_once 'model/om/BaseLanguageObjectHistoryPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(LanguageObjectHistoryPeer::OBJECT_ID, $this->getId());

		return LanguageObjectHistoryPeer::doCount($criteria, $distinct, $con);
	}

	/**
	 * Method called to associate a LanguageObjectHistory object to this object
	 * through the LanguageObjectHistory foreign key attribute
	 *
	 * @param      LanguageObjectHistory $l LanguageObjectHistory
	 * @return     void
	 * @throws     PropelException
	 */
	public function addLanguageObjectHistory(LanguageObjectHistory $l)
	{
		$this->collLanguageObjectHistorys[] = $l;
		$l->setContentObject($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this ContentObject is new, it will return
	 * an empty collection; or if this ContentObject has previously
	 * been saved, it will retrieve related LanguageObjectHistorys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in ContentObject.
	 */
	public function getLanguageObjectHistorysJoinLanguage($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'model/om/BaseLanguageObjectHistoryPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collLanguageObjectHistorys === null) {
			if ($this->isNew()) {
				$this->collLanguageObjectHistorys = array();
			} else {

				$criteria->add(LanguageObjectHistoryPeer::OBJECT_ID, $this->getId());

				$this->collLanguageObjectHistorys = LanguageObjectHistoryPeer::doSelectJoinLanguage($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(LanguageObjectHistoryPeer::OBJECT_ID, $this->getId());

			if (!isset($this->lastLanguageObjectHistoryCriteria) || !$this->lastLanguageObjectHistoryCriteria->equals($criteria)) {
				$this->collLanguageObjectHistorys = LanguageObjectHistoryPeer::doSelectJoinLanguage($criteria, $con);
			}
		}
		$this->lastLanguageObjectHistoryCriteria = $criteria;

		return $this->collLanguageObjectHistorys;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this ContentObject is new, it will return
	 * an empty collection; or if this ContentObject has previously
	 * been saved, it will retrieve related LanguageObjectHistorys from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in ContentObject.
	 */
	public function getLanguageObjectHistorysJoinUser($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'model/om/BaseLanguageObjectHistoryPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collLanguageObjectHistorys === null) {
			if ($this->isNew()) {
				$this->collLanguageObjectHistorys = array();
			} else {

				$criteria->add(LanguageObjectHistoryPeer::OBJECT_ID, $this->getId());

				$this->collLanguageObjectHistorys = LanguageObjectHistoryPeer::doSelectJoinUser($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(LanguageObjectHistoryPeer::OBJECT_ID, $this->getId());

			if (!isset($this->lastLanguageObjectHistoryCriteria) || !$this->lastLanguageObjectHistoryCriteria->equals($criteria)) {
				$this->collLanguageObjectHistorys = LanguageObjectHistoryPeer::doSelectJoinUser($criteria, $con);
			}
		}
		$this->lastLanguageObjectHistoryCriteria = $criteria;

		return $this->collLanguageObjectHistorys;
	}

} // BaseContentObject

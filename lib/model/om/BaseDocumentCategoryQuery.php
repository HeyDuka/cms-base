<?php


/**
 * Base class that represents a query for the 'document_categories' table.
 *
 * 
 *
 * @method     DocumentCategoryQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     DocumentCategoryQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     DocumentCategoryQuery orderBySort($order = Criteria::ASC) Order by the sort column
 * @method     DocumentCategoryQuery orderByMaxWidth($order = Criteria::ASC) Order by the max_width column
 * @method     DocumentCategoryQuery orderByIsExternallyManaged($order = Criteria::ASC) Order by the is_externally_managed column
 * @method     DocumentCategoryQuery orderByIsInactive($order = Criteria::ASC) Order by the is_inactive column
 * @method     DocumentCategoryQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     DocumentCategoryQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method     DocumentCategoryQuery orderByCreatedBy($order = Criteria::ASC) Order by the created_by column
 * @method     DocumentCategoryQuery orderByUpdatedBy($order = Criteria::ASC) Order by the updated_by column
 *
 * @method     DocumentCategoryQuery groupById() Group by the id column
 * @method     DocumentCategoryQuery groupByName() Group by the name column
 * @method     DocumentCategoryQuery groupBySort() Group by the sort column
 * @method     DocumentCategoryQuery groupByMaxWidth() Group by the max_width column
 * @method     DocumentCategoryQuery groupByIsExternallyManaged() Group by the is_externally_managed column
 * @method     DocumentCategoryQuery groupByIsInactive() Group by the is_inactive column
 * @method     DocumentCategoryQuery groupByCreatedAt() Group by the created_at column
 * @method     DocumentCategoryQuery groupByUpdatedAt() Group by the updated_at column
 * @method     DocumentCategoryQuery groupByCreatedBy() Group by the created_by column
 * @method     DocumentCategoryQuery groupByUpdatedBy() Group by the updated_by column
 *
 * @method     DocumentCategoryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     DocumentCategoryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     DocumentCategoryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     DocumentCategoryQuery leftJoinUserRelatedByCreatedBy($relationAlias = '') Adds a LEFT JOIN clause to the query using the UserRelatedByCreatedBy relation
 * @method     DocumentCategoryQuery rightJoinUserRelatedByCreatedBy($relationAlias = '') Adds a RIGHT JOIN clause to the query using the UserRelatedByCreatedBy relation
 * @method     DocumentCategoryQuery innerJoinUserRelatedByCreatedBy($relationAlias = '') Adds a INNER JOIN clause to the query using the UserRelatedByCreatedBy relation
 *
 * @method     DocumentCategoryQuery leftJoinUserRelatedByUpdatedBy($relationAlias = '') Adds a LEFT JOIN clause to the query using the UserRelatedByUpdatedBy relation
 * @method     DocumentCategoryQuery rightJoinUserRelatedByUpdatedBy($relationAlias = '') Adds a RIGHT JOIN clause to the query using the UserRelatedByUpdatedBy relation
 * @method     DocumentCategoryQuery innerJoinUserRelatedByUpdatedBy($relationAlias = '') Adds a INNER JOIN clause to the query using the UserRelatedByUpdatedBy relation
 *
 * @method     DocumentCategoryQuery leftJoinDocument($relationAlias = '') Adds a LEFT JOIN clause to the query using the Document relation
 * @method     DocumentCategoryQuery rightJoinDocument($relationAlias = '') Adds a RIGHT JOIN clause to the query using the Document relation
 * @method     DocumentCategoryQuery innerJoinDocument($relationAlias = '') Adds a INNER JOIN clause to the query using the Document relation
 *
 * @method     DocumentCategory findOne(PropelPDO $con = null) Return the first DocumentCategory matching the query
 * @method     DocumentCategory findOneById(int $id) Return the first DocumentCategory filtered by the id column
 * @method     DocumentCategory findOneByName(string $name) Return the first DocumentCategory filtered by the name column
 * @method     DocumentCategory findOneBySort(int $sort) Return the first DocumentCategory filtered by the sort column
 * @method     DocumentCategory findOneByMaxWidth(int $max_width) Return the first DocumentCategory filtered by the max_width column
 * @method     DocumentCategory findOneByIsExternallyManaged(boolean $is_externally_managed) Return the first DocumentCategory filtered by the is_externally_managed column
 * @method     DocumentCategory findOneByIsInactive(boolean $is_inactive) Return the first DocumentCategory filtered by the is_inactive column
 * @method     DocumentCategory findOneByCreatedAt(string $created_at) Return the first DocumentCategory filtered by the created_at column
 * @method     DocumentCategory findOneByUpdatedAt(string $updated_at) Return the first DocumentCategory filtered by the updated_at column
 * @method     DocumentCategory findOneByCreatedBy(int $created_by) Return the first DocumentCategory filtered by the created_by column
 * @method     DocumentCategory findOneByUpdatedBy(int $updated_by) Return the first DocumentCategory filtered by the updated_by column
 *
 * @method     array findById(int $id) Return DocumentCategory objects filtered by the id column
 * @method     array findByName(string $name) Return DocumentCategory objects filtered by the name column
 * @method     array findBySort(int $sort) Return DocumentCategory objects filtered by the sort column
 * @method     array findByMaxWidth(int $max_width) Return DocumentCategory objects filtered by the max_width column
 * @method     array findByIsExternallyManaged(boolean $is_externally_managed) Return DocumentCategory objects filtered by the is_externally_managed column
 * @method     array findByIsInactive(boolean $is_inactive) Return DocumentCategory objects filtered by the is_inactive column
 * @method     array findByCreatedAt(string $created_at) Return DocumentCategory objects filtered by the created_at column
 * @method     array findByUpdatedAt(string $updated_at) Return DocumentCategory objects filtered by the updated_at column
 * @method     array findByCreatedBy(int $created_by) Return DocumentCategory objects filtered by the created_by column
 * @method     array findByUpdatedBy(int $updated_by) Return DocumentCategory objects filtered by the updated_by column
 *
 * @package    propel.generator.model.om
 */
abstract class BaseDocumentCategoryQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseDocumentCategoryQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'mini_cms', $modelName = 'DocumentCategory', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new DocumentCategoryQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    DocumentCategoryQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof DocumentCategoryQuery) {
			return $criteria;
		}
		$query = new DocumentCategoryQuery();
		if (null !== $modelAlias) {
			$query->setModelAlias($modelAlias);
		}
		if ($criteria instanceof Criteria) {
			$query->mergeWith($criteria);
		}
		return $query;
	}

	/**
	 * Find object by primary key
	 * Use instance pooling to avoid a database query if the object exists
	 * <code>
	 * $obj  = $c->findPk(12, $con);
	 * </code>
	 * @param     mixed $key Primary key to use for the query
	 * @param     PropelPDO $con an optional connection object
	 *
	 * @return    DocumentCategory|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = DocumentCategoryPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
			// the object is alredy in the instance pool
			return $obj;
		} else {
			// the object has not been requested yet, or the formatter is not an object formatter
			$criteria = $this->isKeepQuery() ? clone $this : $this;
			$stmt = $criteria
				->filterByPrimaryKey($key)
				->getSelectStatement($con);
			return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
		}
	}

	/**
	 * Find objects by primary key
	 * <code>
	 * $objs = $c->findPks(array(12, 56, 832), $con);
	 * </code>
	 * @param     array $keys Primary keys to use for the query
	 * @param     PropelPDO $con an optional connection object
	 *
	 * @return    PropelObjectCollection|array|mixed the list of results, formatted by the current formatter
	 */
	public function findPks($keys, $con = null)
	{	
		$criteria = $this->isKeepQuery() ? clone $this : $this;
		return $this
			->filterByPrimaryKeys($keys)
			->find($con);
	}

	/**
	 * Filter the query by primary key
	 *
	 * @param     mixed $key Primary key to use for the query
	 *
	 * @return    DocumentCategoryQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(DocumentCategoryPeer::ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    DocumentCategoryQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(DocumentCategoryPeer::ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the id column
	 * 
	 * @param     int|array $id The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DocumentCategoryQuery The current query, for fluid interface
	 */
	public function filterById($id = null, $comparison = null)
	{
		if (is_array($id) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(DocumentCategoryPeer::ID, $id, $comparison);
	}

	/**
	 * Filter the query on the name column
	 * 
	 * @param     string $name The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DocumentCategoryQuery The current query, for fluid interface
	 */
	public function filterByName($name = null, $comparison = null)
	{
		if (is_array($name)) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		} elseif (preg_match('/[\%\*]/', $name)) {
			$name = str_replace('*', '%', $name);
			if (null === $comparison) {
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(DocumentCategoryPeer::NAME, $name, $comparison);
	}

	/**
	 * Filter the query on the sort column
	 * 
	 * @param     int|array $sort The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DocumentCategoryQuery The current query, for fluid interface
	 */
	public function filterBySort($sort = null, $comparison = null)
	{
		if (is_array($sort)) {
			$useMinMax = false;
			if (isset($sort['min'])) {
				$this->addUsingAlias(DocumentCategoryPeer::SORT, $sort['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($sort['max'])) {
				$this->addUsingAlias(DocumentCategoryPeer::SORT, $sort['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(DocumentCategoryPeer::SORT, $sort, $comparison);
	}

	/**
	 * Filter the query on the max_width column
	 * 
	 * @param     int|array $maxWidth The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DocumentCategoryQuery The current query, for fluid interface
	 */
	public function filterByMaxWidth($maxWidth = null, $comparison = null)
	{
		if (is_array($maxWidth)) {
			$useMinMax = false;
			if (isset($maxWidth['min'])) {
				$this->addUsingAlias(DocumentCategoryPeer::MAX_WIDTH, $maxWidth['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($maxWidth['max'])) {
				$this->addUsingAlias(DocumentCategoryPeer::MAX_WIDTH, $maxWidth['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(DocumentCategoryPeer::MAX_WIDTH, $maxWidth, $comparison);
	}

	/**
	 * Filter the query on the is_externally_managed column
	 * 
	 * @param     boolean|string $isExternallyManaged The value to use as filter.
	 *            Accepts strings ('false', 'off', '-', 'no', 'n', and '0' are false, the rest is true)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DocumentCategoryQuery The current query, for fluid interface
	 */
	public function filterByIsExternallyManaged($isExternallyManaged = null, $comparison = null)
	{
		if (is_string($isExternallyManaged)) {
			$is_externally_managed = in_array(strtolower($isExternallyManaged), array('false', 'off', '-', 'no', 'n', '0')) ? false : true;
		}
		return $this->addUsingAlias(DocumentCategoryPeer::IS_EXTERNALLY_MANAGED, $isExternallyManaged, $comparison);
	}

	/**
	 * Filter the query on the is_inactive column
	 * 
	 * @param     boolean|string $isInactive The value to use as filter.
	 *            Accepts strings ('false', 'off', '-', 'no', 'n', and '0' are false, the rest is true)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DocumentCategoryQuery The current query, for fluid interface
	 */
	public function filterByIsInactive($isInactive = null, $comparison = null)
	{
		if (is_string($isInactive)) {
			$is_inactive = in_array(strtolower($isInactive), array('false', 'off', '-', 'no', 'n', '0')) ? false : true;
		}
		return $this->addUsingAlias(DocumentCategoryPeer::IS_INACTIVE, $isInactive, $comparison);
	}

	/**
	 * Filter the query on the created_at column
	 * 
	 * @param     string|array $createdAt The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DocumentCategoryQuery The current query, for fluid interface
	 */
	public function filterByCreatedAt($createdAt = null, $comparison = null)
	{
		if (is_array($createdAt)) {
			$useMinMax = false;
			if (isset($createdAt['min'])) {
				$this->addUsingAlias(DocumentCategoryPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($createdAt['max'])) {
				$this->addUsingAlias(DocumentCategoryPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(DocumentCategoryPeer::CREATED_AT, $createdAt, $comparison);
	}

	/**
	 * Filter the query on the updated_at column
	 * 
	 * @param     string|array $updatedAt The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DocumentCategoryQuery The current query, for fluid interface
	 */
	public function filterByUpdatedAt($updatedAt = null, $comparison = null)
	{
		if (is_array($updatedAt)) {
			$useMinMax = false;
			if (isset($updatedAt['min'])) {
				$this->addUsingAlias(DocumentCategoryPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($updatedAt['max'])) {
				$this->addUsingAlias(DocumentCategoryPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(DocumentCategoryPeer::UPDATED_AT, $updatedAt, $comparison);
	}

	/**
	 * Filter the query on the created_by column
	 * 
	 * @param     int|array $createdBy The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DocumentCategoryQuery The current query, for fluid interface
	 */
	public function filterByCreatedBy($createdBy = null, $comparison = null)
	{
		if (is_array($createdBy)) {
			$useMinMax = false;
			if (isset($createdBy['min'])) {
				$this->addUsingAlias(DocumentCategoryPeer::CREATED_BY, $createdBy['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($createdBy['max'])) {
				$this->addUsingAlias(DocumentCategoryPeer::CREATED_BY, $createdBy['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(DocumentCategoryPeer::CREATED_BY, $createdBy, $comparison);
	}

	/**
	 * Filter the query on the updated_by column
	 * 
	 * @param     int|array $updatedBy The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DocumentCategoryQuery The current query, for fluid interface
	 */
	public function filterByUpdatedBy($updatedBy = null, $comparison = null)
	{
		if (is_array($updatedBy)) {
			$useMinMax = false;
			if (isset($updatedBy['min'])) {
				$this->addUsingAlias(DocumentCategoryPeer::UPDATED_BY, $updatedBy['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($updatedBy['max'])) {
				$this->addUsingAlias(DocumentCategoryPeer::UPDATED_BY, $updatedBy['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(DocumentCategoryPeer::UPDATED_BY, $updatedBy, $comparison);
	}

	/**
	 * Filter the query by a related User object
	 *
	 * @param     User $user  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DocumentCategoryQuery The current query, for fluid interface
	 */
	public function filterByUserRelatedByCreatedBy($user, $comparison = null)
	{
		return $this
			->addUsingAlias(DocumentCategoryPeer::CREATED_BY, $user->getId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the UserRelatedByCreatedBy relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    DocumentCategoryQuery The current query, for fluid interface
	 */
	public function joinUserRelatedByCreatedBy($relationAlias = '', $joinType = Criteria::LEFT_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('UserRelatedByCreatedBy');
		
		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		if ($previousJoin = $this->getPreviousJoin()) {
			$join->setPreviousJoin($previousJoin);
		}
		
		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'UserRelatedByCreatedBy');
		}
		
		return $this;
	}

	/**
	 * Use the UserRelatedByCreatedBy relation User object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    UserQuery A secondary query class using the current class as primary query
	 */
	public function useUserRelatedByCreatedByQuery($relationAlias = '', $joinType = Criteria::LEFT_JOIN)
	{
		return $this
			->joinUserRelatedByCreatedBy($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'UserRelatedByCreatedBy', 'UserQuery');
	}

	/**
	 * Filter the query by a related User object
	 *
	 * @param     User $user  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DocumentCategoryQuery The current query, for fluid interface
	 */
	public function filterByUserRelatedByUpdatedBy($user, $comparison = null)
	{
		return $this
			->addUsingAlias(DocumentCategoryPeer::UPDATED_BY, $user->getId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the UserRelatedByUpdatedBy relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    DocumentCategoryQuery The current query, for fluid interface
	 */
	public function joinUserRelatedByUpdatedBy($relationAlias = '', $joinType = Criteria::LEFT_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('UserRelatedByUpdatedBy');
		
		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		if ($previousJoin = $this->getPreviousJoin()) {
			$join->setPreviousJoin($previousJoin);
		}
		
		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'UserRelatedByUpdatedBy');
		}
		
		return $this;
	}

	/**
	 * Use the UserRelatedByUpdatedBy relation User object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    UserQuery A secondary query class using the current class as primary query
	 */
	public function useUserRelatedByUpdatedByQuery($relationAlias = '', $joinType = Criteria::LEFT_JOIN)
	{
		return $this
			->joinUserRelatedByUpdatedBy($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'UserRelatedByUpdatedBy', 'UserQuery');
	}

	/**
	 * Filter the query by a related Document object
	 *
	 * @param     Document $document  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DocumentCategoryQuery The current query, for fluid interface
	 */
	public function filterByDocument($document, $comparison = null)
	{
		return $this
			->addUsingAlias(DocumentCategoryPeer::ID, $document->getDocumentCategoryId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the Document relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    DocumentCategoryQuery The current query, for fluid interface
	 */
	public function joinDocument($relationAlias = '', $joinType = Criteria::LEFT_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('Document');
		
		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		if ($previousJoin = $this->getPreviousJoin()) {
			$join->setPreviousJoin($previousJoin);
		}
		
		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'Document');
		}
		
		return $this;
	}

	/**
	 * Use the Document relation Document object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    DocumentQuery A secondary query class using the current class as primary query
	 */
	public function useDocumentQuery($relationAlias = '', $joinType = Criteria::LEFT_JOIN)
	{
		return $this
			->joinDocument($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'Document', 'DocumentQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     DocumentCategory $documentCategory Object to remove from the list of results
	 *
	 * @return    DocumentCategoryQuery The current query, for fluid interface
	 */
	public function prune($documentCategory = null)
	{
		if ($documentCategory) {
			$this->addUsingAlias(DocumentCategoryPeer::ID, $documentCategory->getId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

	// extended_timestampable behavior
	
	/**
	 * Filter by the latest updated
	 *
	 * @param      int $nbDays Maximum age of the latest update in days
	 *
	 * @return     DocumentCategoryQuery The current query, for fuid interface
	 */
	public function recentlyUpdated($nbDays = 7)
	{
		return $this->addUsingAlias(DocumentCategoryPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
	}
	
	/**
	 * Filter by the latest created
	 *
	 * @param      int $nbDays Maximum age of in days
	 *
	 * @return     DocumentCategoryQuery The current query, for fuid interface
	 */
	public function recentlyCreated($nbDays = 7)
	{
		return $this->addUsingAlias(DocumentCategoryPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
	}
	
	/**
	 * Order by update date desc
	 *
	 * @return     DocumentCategoryQuery The current query, for fuid interface
	 */
	public function lastUpdatedFirst()
	{
		return $this->addDescendingOrderByColumn(DocumentCategoryPeer::UPDATED_AT);
	}
	
	/**
	 * Order by update date asc
	 *
	 * @return     DocumentCategoryQuery The current query, for fuid interface
	 */
	public function firstUpdatedFirst()
	{
		return $this->addAscendingOrderByColumn(DocumentCategoryPeer::UPDATED_AT);
	}
	
	/**
	 * Order by create date desc
	 *
	 * @return     DocumentCategoryQuery The current query, for fuid interface
	 */
	public function lastCreatedFirst()
	{
		return $this->addDescendingOrderByColumn(DocumentCategoryPeer::CREATED_AT);
	}
	
	/**
	 * Order by create date asc
	 *
	 * @return     DocumentCategoryQuery The current query, for fuid interface
	 */
	public function firstCreatedFirst()
	{
		return $this->addAscendingOrderByColumn(DocumentCategoryPeer::CREATED_AT);
	}

} // BaseDocumentCategoryQuery
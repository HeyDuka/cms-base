<?php


/**
 * Base class that represents a query for the 'rights' table.
 *
 * 
 *
 * @method     RightQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     RightQuery orderByRoleKey($order = Criteria::ASC) Order by the role_key column
 * @method     RightQuery orderByPageId($order = Criteria::ASC) Order by the page_id column
 * @method     RightQuery orderByIsInherited($order = Criteria::ASC) Order by the is_inherited column
 * @method     RightQuery orderByMayEditPageDetails($order = Criteria::ASC) Order by the may_edit_page_details column
 * @method     RightQuery orderByMayEditPageContents($order = Criteria::ASC) Order by the may_edit_page_contents column
 * @method     RightQuery orderByMayDelete($order = Criteria::ASC) Order by the may_delete column
 * @method     RightQuery orderByMayCreateChildren($order = Criteria::ASC) Order by the may_create_children column
 * @method     RightQuery orderByMayViewPage($order = Criteria::ASC) Order by the may_view_page column
 * @method     RightQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     RightQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method     RightQuery orderByCreatedBy($order = Criteria::ASC) Order by the created_by column
 * @method     RightQuery orderByUpdatedBy($order = Criteria::ASC) Order by the updated_by column
 *
 * @method     RightQuery groupById() Group by the id column
 * @method     RightQuery groupByRoleKey() Group by the role_key column
 * @method     RightQuery groupByPageId() Group by the page_id column
 * @method     RightQuery groupByIsInherited() Group by the is_inherited column
 * @method     RightQuery groupByMayEditPageDetails() Group by the may_edit_page_details column
 * @method     RightQuery groupByMayEditPageContents() Group by the may_edit_page_contents column
 * @method     RightQuery groupByMayDelete() Group by the may_delete column
 * @method     RightQuery groupByMayCreateChildren() Group by the may_create_children column
 * @method     RightQuery groupByMayViewPage() Group by the may_view_page column
 * @method     RightQuery groupByCreatedAt() Group by the created_at column
 * @method     RightQuery groupByUpdatedAt() Group by the updated_at column
 * @method     RightQuery groupByCreatedBy() Group by the created_by column
 * @method     RightQuery groupByUpdatedBy() Group by the updated_by column
 *
 * @method     RightQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     RightQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     RightQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     RightQuery leftJoinRole($relationAlias = null) Adds a LEFT JOIN clause to the query using the Role relation
 * @method     RightQuery rightJoinRole($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Role relation
 * @method     RightQuery innerJoinRole($relationAlias = null) Adds a INNER JOIN clause to the query using the Role relation
 *
 * @method     RightQuery leftJoinPage($relationAlias = null) Adds a LEFT JOIN clause to the query using the Page relation
 * @method     RightQuery rightJoinPage($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Page relation
 * @method     RightQuery innerJoinPage($relationAlias = null) Adds a INNER JOIN clause to the query using the Page relation
 *
 * @method     RightQuery leftJoinUserRelatedByCreatedBy($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserRelatedByCreatedBy relation
 * @method     RightQuery rightJoinUserRelatedByCreatedBy($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserRelatedByCreatedBy relation
 * @method     RightQuery innerJoinUserRelatedByCreatedBy($relationAlias = null) Adds a INNER JOIN clause to the query using the UserRelatedByCreatedBy relation
 *
 * @method     RightQuery leftJoinUserRelatedByUpdatedBy($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserRelatedByUpdatedBy relation
 * @method     RightQuery rightJoinUserRelatedByUpdatedBy($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserRelatedByUpdatedBy relation
 * @method     RightQuery innerJoinUserRelatedByUpdatedBy($relationAlias = null) Adds a INNER JOIN clause to the query using the UserRelatedByUpdatedBy relation
 *
 * @method     Right findOne(PropelPDO $con = null) Return the first Right matching the query
 * @method     Right findOneOrCreate(PropelPDO $con = null) Return the first Right matching the query, or a new Right object populated from the query conditions when no match is found
 *
 * @method     Right findOneById(int $id) Return the first Right filtered by the id column
 * @method     Right findOneByRoleKey(string $role_key) Return the first Right filtered by the role_key column
 * @method     Right findOneByPageId(int $page_id) Return the first Right filtered by the page_id column
 * @method     Right findOneByIsInherited(boolean $is_inherited) Return the first Right filtered by the is_inherited column
 * @method     Right findOneByMayEditPageDetails(boolean $may_edit_page_details) Return the first Right filtered by the may_edit_page_details column
 * @method     Right findOneByMayEditPageContents(boolean $may_edit_page_contents) Return the first Right filtered by the may_edit_page_contents column
 * @method     Right findOneByMayDelete(boolean $may_delete) Return the first Right filtered by the may_delete column
 * @method     Right findOneByMayCreateChildren(boolean $may_create_children) Return the first Right filtered by the may_create_children column
 * @method     Right findOneByMayViewPage(boolean $may_view_page) Return the first Right filtered by the may_view_page column
 * @method     Right findOneByCreatedAt(string $created_at) Return the first Right filtered by the created_at column
 * @method     Right findOneByUpdatedAt(string $updated_at) Return the first Right filtered by the updated_at column
 * @method     Right findOneByCreatedBy(int $created_by) Return the first Right filtered by the created_by column
 * @method     Right findOneByUpdatedBy(int $updated_by) Return the first Right filtered by the updated_by column
 *
 * @method     array findById(int $id) Return Right objects filtered by the id column
 * @method     array findByRoleKey(string $role_key) Return Right objects filtered by the role_key column
 * @method     array findByPageId(int $page_id) Return Right objects filtered by the page_id column
 * @method     array findByIsInherited(boolean $is_inherited) Return Right objects filtered by the is_inherited column
 * @method     array findByMayEditPageDetails(boolean $may_edit_page_details) Return Right objects filtered by the may_edit_page_details column
 * @method     array findByMayEditPageContents(boolean $may_edit_page_contents) Return Right objects filtered by the may_edit_page_contents column
 * @method     array findByMayDelete(boolean $may_delete) Return Right objects filtered by the may_delete column
 * @method     array findByMayCreateChildren(boolean $may_create_children) Return Right objects filtered by the may_create_children column
 * @method     array findByMayViewPage(boolean $may_view_page) Return Right objects filtered by the may_view_page column
 * @method     array findByCreatedAt(string $created_at) Return Right objects filtered by the created_at column
 * @method     array findByUpdatedAt(string $updated_at) Return Right objects filtered by the updated_at column
 * @method     array findByCreatedBy(int $created_by) Return Right objects filtered by the created_by column
 * @method     array findByUpdatedBy(int $updated_by) Return Right objects filtered by the updated_by column
 *
 * @package    propel.generator.model.om
 */
abstract class BaseRightQuery extends ModelCriteria
{
    
    /**
     * Initializes internal state of BaseRightQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'rapila', $modelName = 'Right', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new RightQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return    RightQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof RightQuery) {
            return $criteria;
        }
        $query = new RightQuery();
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
     * @return    Right|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ((null !== ($obj = RightPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
     * @return    RightQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        return $this->addUsingAlias(RightPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return    RightQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        return $this->addUsingAlias(RightPeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return    RightQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id) && null === $comparison) {
            $comparison = Criteria::IN;
        }
        return $this->addUsingAlias(RightPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the role_key column
     *
     * Example usage:
     * <code>
     * $query->filterByRoleKey('fooValue');   // WHERE role_key = 'fooValue'
     * $query->filterByRoleKey('%fooValue%'); // WHERE role_key LIKE '%fooValue%'
     * </code>
     *
     * @param     string $roleKey The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return    RightQuery The current query, for fluid interface
     */
    public function filterByRoleKey($roleKey = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($roleKey)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $roleKey)) {
                $roleKey = str_replace('*', '%', $roleKey);
                $comparison = Criteria::LIKE;
            }
        }
        return $this->addUsingAlias(RightPeer::ROLE_KEY, $roleKey, $comparison);
    }

    /**
     * Filter the query on the page_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPageId(1234); // WHERE page_id = 1234
     * $query->filterByPageId(array(12, 34)); // WHERE page_id IN (12, 34)
     * $query->filterByPageId(array('min' => 12)); // WHERE page_id > 12
     * </code>
     *
     * @see       filterByPage()
     *
     * @param     mixed $pageId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return    RightQuery The current query, for fluid interface
     */
    public function filterByPageId($pageId = null, $comparison = null)
    {
        if (is_array($pageId)) {
            $useMinMax = false;
            if (isset($pageId['min'])) {
                $this->addUsingAlias(RightPeer::PAGE_ID, $pageId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pageId['max'])) {
                $this->addUsingAlias(RightPeer::PAGE_ID, $pageId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }
        return $this->addUsingAlias(RightPeer::PAGE_ID, $pageId, $comparison);
    }

    /**
     * Filter the query on the is_inherited column
     *
     * Example usage:
     * <code>
     * $query->filterByIsInherited(true); // WHERE is_inherited = true
     * $query->filterByIsInherited('yes'); // WHERE is_inherited = true
     * </code>
     *
     * @param     boolean|string $isInherited The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return    RightQuery The current query, for fluid interface
     */
    public function filterByIsInherited($isInherited = null, $comparison = null)
    {
        if (is_string($isInherited)) {
            $is_inherited = in_array(strtolower($isInherited), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }
        return $this->addUsingAlias(RightPeer::IS_INHERITED, $isInherited, $comparison);
    }

    /**
     * Filter the query on the may_edit_page_details column
     *
     * Example usage:
     * <code>
     * $query->filterByMayEditPageDetails(true); // WHERE may_edit_page_details = true
     * $query->filterByMayEditPageDetails('yes'); // WHERE may_edit_page_details = true
     * </code>
     *
     * @param     boolean|string $mayEditPageDetails The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return    RightQuery The current query, for fluid interface
     */
    public function filterByMayEditPageDetails($mayEditPageDetails = null, $comparison = null)
    {
        if (is_string($mayEditPageDetails)) {
            $may_edit_page_details = in_array(strtolower($mayEditPageDetails), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }
        return $this->addUsingAlias(RightPeer::MAY_EDIT_PAGE_DETAILS, $mayEditPageDetails, $comparison);
    }

    /**
     * Filter the query on the may_edit_page_contents column
     *
     * Example usage:
     * <code>
     * $query->filterByMayEditPageContents(true); // WHERE may_edit_page_contents = true
     * $query->filterByMayEditPageContents('yes'); // WHERE may_edit_page_contents = true
     * </code>
     *
     * @param     boolean|string $mayEditPageContents The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return    RightQuery The current query, for fluid interface
     */
    public function filterByMayEditPageContents($mayEditPageContents = null, $comparison = null)
    {
        if (is_string($mayEditPageContents)) {
            $may_edit_page_contents = in_array(strtolower($mayEditPageContents), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }
        return $this->addUsingAlias(RightPeer::MAY_EDIT_PAGE_CONTENTS, $mayEditPageContents, $comparison);
    }

    /**
     * Filter the query on the may_delete column
     *
     * Example usage:
     * <code>
     * $query->filterByMayDelete(true); // WHERE may_delete = true
     * $query->filterByMayDelete('yes'); // WHERE may_delete = true
     * </code>
     *
     * @param     boolean|string $mayDelete The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return    RightQuery The current query, for fluid interface
     */
    public function filterByMayDelete($mayDelete = null, $comparison = null)
    {
        if (is_string($mayDelete)) {
            $may_delete = in_array(strtolower($mayDelete), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }
        return $this->addUsingAlias(RightPeer::MAY_DELETE, $mayDelete, $comparison);
    }

    /**
     * Filter the query on the may_create_children column
     *
     * Example usage:
     * <code>
     * $query->filterByMayCreateChildren(true); // WHERE may_create_children = true
     * $query->filterByMayCreateChildren('yes'); // WHERE may_create_children = true
     * </code>
     *
     * @param     boolean|string $mayCreateChildren The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return    RightQuery The current query, for fluid interface
     */
    public function filterByMayCreateChildren($mayCreateChildren = null, $comparison = null)
    {
        if (is_string($mayCreateChildren)) {
            $may_create_children = in_array(strtolower($mayCreateChildren), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }
        return $this->addUsingAlias(RightPeer::MAY_CREATE_CHILDREN, $mayCreateChildren, $comparison);
    }

    /**
     * Filter the query on the may_view_page column
     *
     * Example usage:
     * <code>
     * $query->filterByMayViewPage(true); // WHERE may_view_page = true
     * $query->filterByMayViewPage('yes'); // WHERE may_view_page = true
     * </code>
     *
     * @param     boolean|string $mayViewPage The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return    RightQuery The current query, for fluid interface
     */
    public function filterByMayViewPage($mayViewPage = null, $comparison = null)
    {
        if (is_string($mayViewPage)) {
            $may_view_page = in_array(strtolower($mayViewPage), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }
        return $this->addUsingAlias(RightPeer::MAY_VIEW_PAGE, $mayViewPage, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return    RightQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(RightPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(RightPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }
        return $this->addUsingAlias(RightPeer::CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return    RightQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(RightPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(RightPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }
        return $this->addUsingAlias(RightPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the created_by column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedBy(1234); // WHERE created_by = 1234
     * $query->filterByCreatedBy(array(12, 34)); // WHERE created_by IN (12, 34)
     * $query->filterByCreatedBy(array('min' => 12)); // WHERE created_by > 12
     * </code>
     *
     * @see       filterByUserRelatedByCreatedBy()
     *
     * @param     mixed $createdBy The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return    RightQuery The current query, for fluid interface
     */
    public function filterByCreatedBy($createdBy = null, $comparison = null)
    {
        if (is_array($createdBy)) {
            $useMinMax = false;
            if (isset($createdBy['min'])) {
                $this->addUsingAlias(RightPeer::CREATED_BY, $createdBy['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdBy['max'])) {
                $this->addUsingAlias(RightPeer::CREATED_BY, $createdBy['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }
        return $this->addUsingAlias(RightPeer::CREATED_BY, $createdBy, $comparison);
    }

    /**
     * Filter the query on the updated_by column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedBy(1234); // WHERE updated_by = 1234
     * $query->filterByUpdatedBy(array(12, 34)); // WHERE updated_by IN (12, 34)
     * $query->filterByUpdatedBy(array('min' => 12)); // WHERE updated_by > 12
     * </code>
     *
     * @see       filterByUserRelatedByUpdatedBy()
     *
     * @param     mixed $updatedBy The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return    RightQuery The current query, for fluid interface
     */
    public function filterByUpdatedBy($updatedBy = null, $comparison = null)
    {
        if (is_array($updatedBy)) {
            $useMinMax = false;
            if (isset($updatedBy['min'])) {
                $this->addUsingAlias(RightPeer::UPDATED_BY, $updatedBy['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedBy['max'])) {
                $this->addUsingAlias(RightPeer::UPDATED_BY, $updatedBy['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }
        return $this->addUsingAlias(RightPeer::UPDATED_BY, $updatedBy, $comparison);
    }

    /**
     * Filter the query by a related Role object
     *
     * @param     Role|PropelCollection $role The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return    RightQuery The current query, for fluid interface
     */
    public function filterByRole($role, $comparison = null)
    {
        if ($role instanceof Role) {
            return $this
                ->addUsingAlias(RightPeer::ROLE_KEY, $role->getRoleKey(), $comparison);
        } elseif ($role instanceof PropelCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
            return $this
                ->addUsingAlias(RightPeer::ROLE_KEY, $role->toKeyValue('PrimaryKey', 'RoleKey'), $comparison);
        } else {
            throw new PropelException('filterByRole() only accepts arguments of type Role or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Role relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return    RightQuery The current query, for fluid interface
     */
    public function joinRole($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Role');

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
            $this->addJoinObject($join, 'Role');
        }

        return $this;
    }

    /**
     * Use the Role relation Role object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return    RoleQuery A secondary query class using the current class as primary query
     */
    public function useRoleQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinRole($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Role', 'RoleQuery');
    }

    /**
     * Filter the query by a related Page object
     *
     * @param     Page|PropelCollection $page The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return    RightQuery The current query, for fluid interface
     */
    public function filterByPage($page, $comparison = null)
    {
        if ($page instanceof Page) {
            return $this
                ->addUsingAlias(RightPeer::PAGE_ID, $page->getId(), $comparison);
        } elseif ($page instanceof PropelCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
            return $this
                ->addUsingAlias(RightPeer::PAGE_ID, $page->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPage() only accepts arguments of type Page or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Page relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return    RightQuery The current query, for fluid interface
     */
    public function joinPage($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Page');

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
            $this->addJoinObject($join, 'Page');
        }

        return $this;
    }

    /**
     * Use the Page relation Page object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return    PageQuery A secondary query class using the current class as primary query
     */
    public function usePageQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPage($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Page', 'PageQuery');
    }

    /**
     * Filter the query by a related User object
     *
     * @param     User|PropelCollection $user The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return    RightQuery The current query, for fluid interface
     */
    public function filterByUserRelatedByCreatedBy($user, $comparison = null)
    {
        if ($user instanceof User) {
            return $this
                ->addUsingAlias(RightPeer::CREATED_BY, $user->getId(), $comparison);
        } elseif ($user instanceof PropelCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
            return $this
                ->addUsingAlias(RightPeer::CREATED_BY, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUserRelatedByCreatedBy() only accepts arguments of type User or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserRelatedByCreatedBy relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return    RightQuery The current query, for fluid interface
     */
    public function joinUserRelatedByCreatedBy($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function useUserRelatedByCreatedByQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUserRelatedByCreatedBy($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserRelatedByCreatedBy', 'UserQuery');
    }

    /**
     * Filter the query by a related User object
     *
     * @param     User|PropelCollection $user The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return    RightQuery The current query, for fluid interface
     */
    public function filterByUserRelatedByUpdatedBy($user, $comparison = null)
    {
        if ($user instanceof User) {
            return $this
                ->addUsingAlias(RightPeer::UPDATED_BY, $user->getId(), $comparison);
        } elseif ($user instanceof PropelCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
            return $this
                ->addUsingAlias(RightPeer::UPDATED_BY, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUserRelatedByUpdatedBy() only accepts arguments of type User or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserRelatedByUpdatedBy relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return    RightQuery The current query, for fluid interface
     */
    public function joinUserRelatedByUpdatedBy($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function useUserRelatedByUpdatedByQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUserRelatedByUpdatedBy($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserRelatedByUpdatedBy', 'UserQuery');
    }

    /**
     * Exclude object from result
     *
     * @param     Right $right Object to remove from the list of results
     *
     * @return    RightQuery The current query, for fluid interface
     */
    public function prune($right = null)
    {
        if ($right) {
            $this->addUsingAlias(RightPeer::ID, $right->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

	// extended_timestampable behavior
	
	/**
	 * Filter by the latest updated
	 *
	 * @param      int $nbDays Maximum age of the latest update in days
	 *
	 * @return     RightQuery The current query, for fluid interface
	 */
	public function recentlyUpdated($nbDays = 7)
	{
		return $this->addUsingAlias(RightPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
	}
	
	/**
	 * Filter by the latest created
	 *
	 * @param      int $nbDays Maximum age of in days
	 *
	 * @return     RightQuery The current query, for fluid interface
	 */
	public function recentlyCreated($nbDays = 7)
	{
		return $this->addUsingAlias(RightPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
	}
	
	/**
	 * Order by update date desc
	 *
	 * @return     RightQuery The current query, for fluid interface
	 */
	public function lastUpdatedFirst()
	{
		return $this->addDescendingOrderByColumn(RightPeer::UPDATED_AT);
	}
	
	/**
	 * Order by update date asc
	 *
	 * @return     RightQuery The current query, for fluid interface
	 */
	public function firstUpdatedFirst()
	{
		return $this->addAscendingOrderByColumn(RightPeer::UPDATED_AT);
	}
	
	/**
	 * Order by create date desc
	 *
	 * @return     RightQuery The current query, for fluid interface
	 */
	public function lastCreatedFirst()
	{
		return $this->addDescendingOrderByColumn(RightPeer::CREATED_AT);
	}
	
	/**
	 * Order by create date asc
	 *
	 * @return     RightQuery The current query, for fluid interface
	 */
	public function firstCreatedFirst()
	{
		return $this->addAscendingOrderByColumn(RightPeer::CREATED_AT);
	}

} // BaseRightQuery
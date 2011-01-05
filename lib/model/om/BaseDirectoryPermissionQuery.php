<?php


/**
 * Base class that represents a query for the 'directory_permissions' table.
 *
 * 
 *
 * @method     DirectoryPermissionQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     DirectoryPermissionQuery orderByFilename($order = Criteria::ASC) Order by the filename column
 * @method     DirectoryPermissionQuery orderByGroupId($order = Criteria::ASC) Order by the group_id column
 * @method     DirectoryPermissionQuery orderByCreatedBy($order = Criteria::ASC) Order by the created_by column
 * @method     DirectoryPermissionQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     DirectoryPermissionQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     DirectoryPermissionQuery groupById() Group by the id column
 * @method     DirectoryPermissionQuery groupByFilename() Group by the filename column
 * @method     DirectoryPermissionQuery groupByGroupId() Group by the group_id column
 * @method     DirectoryPermissionQuery groupByCreatedBy() Group by the created_by column
 * @method     DirectoryPermissionQuery groupByCreatedAt() Group by the created_at column
 * @method     DirectoryPermissionQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     DirectoryPermissionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     DirectoryPermissionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     DirectoryPermissionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     DirectoryPermissionQuery leftJoinGroup($relationAlias = '') Adds a LEFT JOIN clause to the query using the Group relation
 * @method     DirectoryPermissionQuery rightJoinGroup($relationAlias = '') Adds a RIGHT JOIN clause to the query using the Group relation
 * @method     DirectoryPermissionQuery innerJoinGroup($relationAlias = '') Adds a INNER JOIN clause to the query using the Group relation
 *
 * @method     DirectoryPermissionQuery leftJoinUser($relationAlias = '') Adds a LEFT JOIN clause to the query using the User relation
 * @method     DirectoryPermissionQuery rightJoinUser($relationAlias = '') Adds a RIGHT JOIN clause to the query using the User relation
 * @method     DirectoryPermissionQuery innerJoinUser($relationAlias = '') Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     DirectoryPermission findOne(PropelPDO $con = null) Return the first DirectoryPermission matching the query
 * @method     DirectoryPermission findOneById(int $id) Return the first DirectoryPermission filtered by the id column
 * @method     DirectoryPermission findOneByFilename(string $filename) Return the first DirectoryPermission filtered by the filename column
 * @method     DirectoryPermission findOneByGroupId(int $group_id) Return the first DirectoryPermission filtered by the group_id column
 * @method     DirectoryPermission findOneByCreatedBy(int $created_by) Return the first DirectoryPermission filtered by the created_by column
 * @method     DirectoryPermission findOneByCreatedAt(string $created_at) Return the first DirectoryPermission filtered by the created_at column
 * @method     DirectoryPermission findOneByUpdatedAt(string $updated_at) Return the first DirectoryPermission filtered by the updated_at column
 *
 * @method     array findById(int $id) Return DirectoryPermission objects filtered by the id column
 * @method     array findByFilename(string $filename) Return DirectoryPermission objects filtered by the filename column
 * @method     array findByGroupId(int $group_id) Return DirectoryPermission objects filtered by the group_id column
 * @method     array findByCreatedBy(int $created_by) Return DirectoryPermission objects filtered by the created_by column
 * @method     array findByCreatedAt(string $created_at) Return DirectoryPermission objects filtered by the created_at column
 * @method     array findByUpdatedAt(string $updated_at) Return DirectoryPermission objects filtered by the updated_at column
 *
 * @package    propel.generator.model.om
 */
abstract class BaseDirectoryPermissionQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseDirectoryPermissionQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'mini_cms', $modelName = 'DirectoryPermission', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new DirectoryPermissionQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    DirectoryPermissionQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof DirectoryPermissionQuery) {
			return $criteria;
		}
		$query = new DirectoryPermissionQuery();
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
	 * <code>
	 * $obj = $c->findPk(array(12, 34), $con);
	 * </code>
	 * @param     array[$id, $group_id] $key Primary key to use for the query
	 * @param     PropelPDO $con an optional connection object
	 *
	 * @return    DirectoryPermission|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = DirectoryPermissionPeer::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
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
	 * @return    DirectoryPermissionQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		$this->addUsingAlias(DirectoryPermissionPeer::ID, $key[0], Criteria::EQUAL);
		$this->addUsingAlias(DirectoryPermissionPeer::GROUP_ID, $key[1], Criteria::EQUAL);
		
		return $this;
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    DirectoryPermissionQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		foreach ($keys as $key) {
			$cton0 = $this->getNewCriterion(DirectoryPermissionPeer::ID, $key[0], Criteria::EQUAL);
			$cton1 = $this->getNewCriterion(DirectoryPermissionPeer::GROUP_ID, $key[1], Criteria::EQUAL);
			$cton0->addAnd($cton1);
			$this->addOr($cton0);
		}
		
		return $this;
	}

	/**
	 * Filter the query on the id column
	 * 
	 * @param     int|array $id The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DirectoryPermissionQuery The current query, for fluid interface
	 */
	public function filterById($id = null, $comparison = null)
	{
		if (is_array($id) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(DirectoryPermissionPeer::ID, $id, $comparison);
	}

	/**
	 * Filter the query on the filename column
	 * 
	 * @param     string $filename The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DirectoryPermissionQuery The current query, for fluid interface
	 */
	public function filterByFilename($filename = null, $comparison = null)
	{
		if (is_array($filename)) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		} elseif (preg_match('/[\%\*]/', $filename)) {
			$filename = str_replace('*', '%', $filename);
			if (null === $comparison) {
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(DirectoryPermissionPeer::FILENAME, $filename, $comparison);
	}

	/**
	 * Filter the query on the group_id column
	 * 
	 * @param     int|array $groupId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DirectoryPermissionQuery The current query, for fluid interface
	 */
	public function filterByGroupId($groupId = null, $comparison = null)
	{
		if (is_array($groupId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(DirectoryPermissionPeer::GROUP_ID, $groupId, $comparison);
	}

	/**
	 * Filter the query on the created_by column
	 * 
	 * @param     int|array $createdBy The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DirectoryPermissionQuery The current query, for fluid interface
	 */
	public function filterByCreatedBy($createdBy = null, $comparison = null)
	{
		if (is_array($createdBy)) {
			$useMinMax = false;
			if (isset($createdBy['min'])) {
				$this->addUsingAlias(DirectoryPermissionPeer::CREATED_BY, $createdBy['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($createdBy['max'])) {
				$this->addUsingAlias(DirectoryPermissionPeer::CREATED_BY, $createdBy['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(DirectoryPermissionPeer::CREATED_BY, $createdBy, $comparison);
	}

	/**
	 * Filter the query on the created_at column
	 * 
	 * @param     string|array $createdAt The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DirectoryPermissionQuery The current query, for fluid interface
	 */
	public function filterByCreatedAt($createdAt = null, $comparison = null)
	{
		if (is_array($createdAt)) {
			$useMinMax = false;
			if (isset($createdAt['min'])) {
				$this->addUsingAlias(DirectoryPermissionPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($createdAt['max'])) {
				$this->addUsingAlias(DirectoryPermissionPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(DirectoryPermissionPeer::CREATED_AT, $createdAt, $comparison);
	}

	/**
	 * Filter the query on the updated_at column
	 * 
	 * @param     string|array $updatedAt The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DirectoryPermissionQuery The current query, for fluid interface
	 */
	public function filterByUpdatedAt($updatedAt = null, $comparison = null)
	{
		if (is_array($updatedAt)) {
			$useMinMax = false;
			if (isset($updatedAt['min'])) {
				$this->addUsingAlias(DirectoryPermissionPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($updatedAt['max'])) {
				$this->addUsingAlias(DirectoryPermissionPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(DirectoryPermissionPeer::UPDATED_AT, $updatedAt, $comparison);
	}

	/**
	 * Filter the query by a related Group object
	 *
	 * @param     Group $group  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DirectoryPermissionQuery The current query, for fluid interface
	 */
	public function filterByGroup($group, $comparison = null)
	{
		return $this
			->addUsingAlias(DirectoryPermissionPeer::GROUP_ID, $group->getId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the Group relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    DirectoryPermissionQuery The current query, for fluid interface
	 */
	public function joinGroup($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('Group');
		
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
			$this->addJoinObject($join, 'Group');
		}
		
		return $this;
	}

	/**
	 * Use the Group relation Group object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    GroupQuery A secondary query class using the current class as primary query
	 */
	public function useGroupQuery($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinGroup($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'Group', 'GroupQuery');
	}

	/**
	 * Filter the query by a related User object
	 *
	 * @param     User $user  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DirectoryPermissionQuery The current query, for fluid interface
	 */
	public function filterByUser($user, $comparison = null)
	{
		return $this
			->addUsingAlias(DirectoryPermissionPeer::CREATED_BY, $user->getId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the User relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    DirectoryPermissionQuery The current query, for fluid interface
	 */
	public function joinUser($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('User');
		
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
			$this->addJoinObject($join, 'User');
		}
		
		return $this;
	}

	/**
	 * Use the User relation User object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    UserQuery A secondary query class using the current class as primary query
	 */
	public function useUserQuery($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinUser($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'User', 'UserQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     DirectoryPermission $directoryPermission Object to remove from the list of results
	 *
	 * @return    DirectoryPermissionQuery The current query, for fluid interface
	 */
	public function prune($directoryPermission = null)
	{
		if ($directoryPermission) {
			$this->addCond('pruneCond0', $this->getAliasedColName(DirectoryPermissionPeer::ID), $directoryPermission->getId(), Criteria::NOT_EQUAL);
			$this->addCond('pruneCond1', $this->getAliasedColName(DirectoryPermissionPeer::GROUP_ID), $directoryPermission->getGroupId(), Criteria::NOT_EQUAL);
			$this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
	  }
	  
		return $this;
	}

} // BaseDirectoryPermissionQuery

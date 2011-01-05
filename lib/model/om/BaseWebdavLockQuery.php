<?php


/**
 * Base class that represents a query for the 'webdav_locks' table.
 *
 * 
 *
 * @method     WebdavLockQuery orderByToken($order = Criteria::ASC) Order by the token column
 * @method     WebdavLockQuery orderByPath($order = Criteria::ASC) Order by the path column
 * @method     WebdavLockQuery orderByOwner($order = Criteria::ASC) Order by the owner column
 * @method     WebdavLockQuery orderByIsRecursive($order = Criteria::ASC) Order by the is_recursive column
 * @method     WebdavLockQuery orderByIsExclusive($order = Criteria::ASC) Order by the is_exclusive column
 * @method     WebdavLockQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     WebdavLockQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method     WebdavLockQuery orderByExpiresAt($order = Criteria::ASC) Order by the expires_at column
 *
 * @method     WebdavLockQuery groupByToken() Group by the token column
 * @method     WebdavLockQuery groupByPath() Group by the path column
 * @method     WebdavLockQuery groupByOwner() Group by the owner column
 * @method     WebdavLockQuery groupByIsRecursive() Group by the is_recursive column
 * @method     WebdavLockQuery groupByIsExclusive() Group by the is_exclusive column
 * @method     WebdavLockQuery groupByCreatedAt() Group by the created_at column
 * @method     WebdavLockQuery groupByUpdatedAt() Group by the updated_at column
 * @method     WebdavLockQuery groupByExpiresAt() Group by the expires_at column
 *
 * @method     WebdavLockQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     WebdavLockQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     WebdavLockQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     WebdavLockQuery leftJoinUser($relationAlias = '') Adds a LEFT JOIN clause to the query using the User relation
 * @method     WebdavLockQuery rightJoinUser($relationAlias = '') Adds a RIGHT JOIN clause to the query using the User relation
 * @method     WebdavLockQuery innerJoinUser($relationAlias = '') Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     WebdavLock findOne(PropelPDO $con = null) Return the first WebdavLock matching the query
 * @method     WebdavLock findOneByToken(string $token) Return the first WebdavLock filtered by the token column
 * @method     WebdavLock findOneByPath(string $path) Return the first WebdavLock filtered by the path column
 * @method     WebdavLock findOneByOwner(int $owner) Return the first WebdavLock filtered by the owner column
 * @method     WebdavLock findOneByIsRecursive(boolean $is_recursive) Return the first WebdavLock filtered by the is_recursive column
 * @method     WebdavLock findOneByIsExclusive(boolean $is_exclusive) Return the first WebdavLock filtered by the is_exclusive column
 * @method     WebdavLock findOneByCreatedAt(string $created_at) Return the first WebdavLock filtered by the created_at column
 * @method     WebdavLock findOneByUpdatedAt(string $updated_at) Return the first WebdavLock filtered by the updated_at column
 * @method     WebdavLock findOneByExpiresAt(string $expires_at) Return the first WebdavLock filtered by the expires_at column
 *
 * @method     array findByToken(string $token) Return WebdavLock objects filtered by the token column
 * @method     array findByPath(string $path) Return WebdavLock objects filtered by the path column
 * @method     array findByOwner(int $owner) Return WebdavLock objects filtered by the owner column
 * @method     array findByIsRecursive(boolean $is_recursive) Return WebdavLock objects filtered by the is_recursive column
 * @method     array findByIsExclusive(boolean $is_exclusive) Return WebdavLock objects filtered by the is_exclusive column
 * @method     array findByCreatedAt(string $created_at) Return WebdavLock objects filtered by the created_at column
 * @method     array findByUpdatedAt(string $updated_at) Return WebdavLock objects filtered by the updated_at column
 * @method     array findByExpiresAt(string $expires_at) Return WebdavLock objects filtered by the expires_at column
 *
 * @package    propel.generator.model.om
 */
abstract class BaseWebdavLockQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseWebdavLockQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'mini_cms', $modelName = 'WebdavLock', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new WebdavLockQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    WebdavLockQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof WebdavLockQuery) {
			return $criteria;
		}
		$query = new WebdavLockQuery();
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
	 * @return    WebdavLock|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = WebdavLockPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    WebdavLockQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(WebdavLockPeer::TOKEN, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    WebdavLockQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(WebdavLockPeer::TOKEN, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the token column
	 * 
	 * @param     string $token The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebdavLockQuery The current query, for fluid interface
	 */
	public function filterByToken($token = null, $comparison = null)
	{
		if (is_array($token)) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		} elseif (preg_match('/[\%\*]/', $token)) {
			$token = str_replace('*', '%', $token);
			if (null === $comparison) {
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebdavLockPeer::TOKEN, $token, $comparison);
	}

	/**
	 * Filter the query on the path column
	 * 
	 * @param     string $path The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebdavLockQuery The current query, for fluid interface
	 */
	public function filterByPath($path = null, $comparison = null)
	{
		if (is_array($path)) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		} elseif (preg_match('/[\%\*]/', $path)) {
			$path = str_replace('*', '%', $path);
			if (null === $comparison) {
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebdavLockPeer::PATH, $path, $comparison);
	}

	/**
	 * Filter the query on the owner column
	 * 
	 * @param     int|array $owner The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebdavLockQuery The current query, for fluid interface
	 */
	public function filterByOwner($owner = null, $comparison = null)
	{
		if (is_array($owner)) {
			$useMinMax = false;
			if (isset($owner['min'])) {
				$this->addUsingAlias(WebdavLockPeer::OWNER, $owner['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($owner['max'])) {
				$this->addUsingAlias(WebdavLockPeer::OWNER, $owner['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(WebdavLockPeer::OWNER, $owner, $comparison);
	}

	/**
	 * Filter the query on the is_recursive column
	 * 
	 * @param     boolean|string $isRecursive The value to use as filter.
	 *            Accepts strings ('false', 'off', '-', 'no', 'n', and '0' are false, the rest is true)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebdavLockQuery The current query, for fluid interface
	 */
	public function filterByIsRecursive($isRecursive = null, $comparison = null)
	{
		if (is_string($isRecursive)) {
			$is_recursive = in_array(strtolower($isRecursive), array('false', 'off', '-', 'no', 'n', '0')) ? false : true;
		}
		return $this->addUsingAlias(WebdavLockPeer::IS_RECURSIVE, $isRecursive, $comparison);
	}

	/**
	 * Filter the query on the is_exclusive column
	 * 
	 * @param     boolean|string $isExclusive The value to use as filter.
	 *            Accepts strings ('false', 'off', '-', 'no', 'n', and '0' are false, the rest is true)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebdavLockQuery The current query, for fluid interface
	 */
	public function filterByIsExclusive($isExclusive = null, $comparison = null)
	{
		if (is_string($isExclusive)) {
			$is_exclusive = in_array(strtolower($isExclusive), array('false', 'off', '-', 'no', 'n', '0')) ? false : true;
		}
		return $this->addUsingAlias(WebdavLockPeer::IS_EXCLUSIVE, $isExclusive, $comparison);
	}

	/**
	 * Filter the query on the created_at column
	 * 
	 * @param     string|array $createdAt The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebdavLockQuery The current query, for fluid interface
	 */
	public function filterByCreatedAt($createdAt = null, $comparison = null)
	{
		if (is_array($createdAt)) {
			$useMinMax = false;
			if (isset($createdAt['min'])) {
				$this->addUsingAlias(WebdavLockPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($createdAt['max'])) {
				$this->addUsingAlias(WebdavLockPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(WebdavLockPeer::CREATED_AT, $createdAt, $comparison);
	}

	/**
	 * Filter the query on the updated_at column
	 * 
	 * @param     string|array $updatedAt The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebdavLockQuery The current query, for fluid interface
	 */
	public function filterByUpdatedAt($updatedAt = null, $comparison = null)
	{
		if (is_array($updatedAt)) {
			$useMinMax = false;
			if (isset($updatedAt['min'])) {
				$this->addUsingAlias(WebdavLockPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($updatedAt['max'])) {
				$this->addUsingAlias(WebdavLockPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(WebdavLockPeer::UPDATED_AT, $updatedAt, $comparison);
	}

	/**
	 * Filter the query on the expires_at column
	 * 
	 * @param     string|array $expiresAt The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebdavLockQuery The current query, for fluid interface
	 */
	public function filterByExpiresAt($expiresAt = null, $comparison = null)
	{
		if (is_array($expiresAt)) {
			$useMinMax = false;
			if (isset($expiresAt['min'])) {
				$this->addUsingAlias(WebdavLockPeer::EXPIRES_AT, $expiresAt['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($expiresAt['max'])) {
				$this->addUsingAlias(WebdavLockPeer::EXPIRES_AT, $expiresAt['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(WebdavLockPeer::EXPIRES_AT, $expiresAt, $comparison);
	}

	/**
	 * Filter the query by a related User object
	 *
	 * @param     User $user  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebdavLockQuery The current query, for fluid interface
	 */
	public function filterByUser($user, $comparison = null)
	{
		return $this
			->addUsingAlias(WebdavLockPeer::OWNER, $user->getId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the User relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    WebdavLockQuery The current query, for fluid interface
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
	 * @param     WebdavLock $webdavLock Object to remove from the list of results
	 *
	 * @return    WebdavLockQuery The current query, for fluid interface
	 */
	public function prune($webdavLock = null)
	{
		if ($webdavLock) {
			$this->addUsingAlias(WebdavLockPeer::TOKEN, $webdavLock->getToken(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseWebdavLockQuery

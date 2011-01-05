<?php


/**
 * Base class that represents a query for the 'webdav_properties' table.
 *
 * 
 *
 * @method     WebdavProperyQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     WebdavProperyQuery orderByPath($order = Criteria::ASC) Order by the path column
 * @method     WebdavProperyQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     WebdavProperyQuery orderByNs($order = Criteria::ASC) Order by the ns column
 * @method     WebdavProperyQuery orderByValue($order = Criteria::ASC) Order by the value column
 *
 * @method     WebdavProperyQuery groupById() Group by the id column
 * @method     WebdavProperyQuery groupByPath() Group by the path column
 * @method     WebdavProperyQuery groupByName() Group by the name column
 * @method     WebdavProperyQuery groupByNs() Group by the ns column
 * @method     WebdavProperyQuery groupByValue() Group by the value column
 *
 * @method     WebdavProperyQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     WebdavProperyQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     WebdavProperyQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     WebdavPropery findOne(PropelPDO $con = null) Return the first WebdavPropery matching the query
 * @method     WebdavPropery findOneById(int $id) Return the first WebdavPropery filtered by the id column
 * @method     WebdavPropery findOneByPath(string $path) Return the first WebdavPropery filtered by the path column
 * @method     WebdavPropery findOneByName(string $name) Return the first WebdavPropery filtered by the name column
 * @method     WebdavPropery findOneByNs(string $ns) Return the first WebdavPropery filtered by the ns column
 * @method     WebdavPropery findOneByValue(string $value) Return the first WebdavPropery filtered by the value column
 *
 * @method     array findById(int $id) Return WebdavPropery objects filtered by the id column
 * @method     array findByPath(string $path) Return WebdavPropery objects filtered by the path column
 * @method     array findByName(string $name) Return WebdavPropery objects filtered by the name column
 * @method     array findByNs(string $ns) Return WebdavPropery objects filtered by the ns column
 * @method     array findByValue(string $value) Return WebdavPropery objects filtered by the value column
 *
 * @package    propel.generator.model.om
 */
abstract class BaseWebdavProperyQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseWebdavProperyQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'mini_cms', $modelName = 'WebdavPropery', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new WebdavProperyQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    WebdavProperyQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof WebdavProperyQuery) {
			return $criteria;
		}
		$query = new WebdavProperyQuery();
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
	 * @return    WebdavPropery|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = WebdavProperyPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    WebdavProperyQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(WebdavProperyPeer::ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    WebdavProperyQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(WebdavProperyPeer::ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the id column
	 * 
	 * @param     int|array $id The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebdavProperyQuery The current query, for fluid interface
	 */
	public function filterById($id = null, $comparison = null)
	{
		if (is_array($id) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(WebdavProperyPeer::ID, $id, $comparison);
	}

	/**
	 * Filter the query on the path column
	 * 
	 * @param     string $path The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebdavProperyQuery The current query, for fluid interface
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
		return $this->addUsingAlias(WebdavProperyPeer::PATH, $path, $comparison);
	}

	/**
	 * Filter the query on the name column
	 * 
	 * @param     string $name The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebdavProperyQuery The current query, for fluid interface
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
		return $this->addUsingAlias(WebdavProperyPeer::NAME, $name, $comparison);
	}

	/**
	 * Filter the query on the ns column
	 * 
	 * @param     string $ns The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebdavProperyQuery The current query, for fluid interface
	 */
	public function filterByNs($ns = null, $comparison = null)
	{
		if (is_array($ns)) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		} elseif (preg_match('/[\%\*]/', $ns)) {
			$ns = str_replace('*', '%', $ns);
			if (null === $comparison) {
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebdavProperyPeer::NS, $ns, $comparison);
	}

	/**
	 * Filter the query on the value column
	 * 
	 * @param     string $value The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebdavProperyQuery The current query, for fluid interface
	 */
	public function filterByValue($value = null, $comparison = null)
	{
		if (is_array($value)) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		} elseif (preg_match('/[\%\*]/', $value)) {
			$value = str_replace('*', '%', $value);
			if (null === $comparison) {
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebdavProperyPeer::VALUE, $value, $comparison);
	}

	/**
	 * Exclude object from result
	 *
	 * @param     WebdavPropery $webdavPropery Object to remove from the list of results
	 *
	 * @return    WebdavProperyQuery The current query, for fluid interface
	 */
	public function prune($webdavPropery = null)
	{
		if ($webdavPropery) {
			$this->addUsingAlias(WebdavProperyPeer::ID, $webdavPropery->getId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseWebdavProperyQuery

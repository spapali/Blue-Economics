<?php

namespace Base;

use \ExpertGroup as ChildExpertGroup;
use \ExpertGroupQuery as ChildExpertGroupQuery;
use \Exception;
use \PDO;
use Map\ExpertGroupTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'expert_group' table.
 *
 *
 *
 * @method     ChildExpertGroupQuery orderByGroupName($order = Criteria::ASC) Order by the group_name column
 * @method     ChildExpertGroupQuery orderBySocCode($order = Criteria::ASC) Order by the soc_code column
 *
 * @method     ChildExpertGroupQuery groupByGroupName() Group by the group_name column
 * @method     ChildExpertGroupQuery groupBySocCode() Group by the soc_code column
 *
 * @method     ChildExpertGroupQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildExpertGroupQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildExpertGroupQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildExpertGroupQuery leftJoinExpertGroupMembers($relationAlias = null) Adds a LEFT JOIN clause to the query using the ExpertGroupMembers relation
 * @method     ChildExpertGroupQuery rightJoinExpertGroupMembers($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ExpertGroupMembers relation
 * @method     ChildExpertGroupQuery innerJoinExpertGroupMembers($relationAlias = null) Adds a INNER JOIN clause to the query using the ExpertGroupMembers relation
 *
 * @method     \ExpertGroupMembersQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildExpertGroup findOne(ConnectionInterface $con = null) Return the first ChildExpertGroup matching the query
 * @method     ChildExpertGroup findOneOrCreate(ConnectionInterface $con = null) Return the first ChildExpertGroup matching the query, or a new ChildExpertGroup object populated from the query conditions when no match is found
 *
 * @method     ChildExpertGroup findOneByGroupName(string $group_name) Return the first ChildExpertGroup filtered by the group_name column
 * @method     ChildExpertGroup findOneBySocCode(string $soc_code) Return the first ChildExpertGroup filtered by the soc_code column
 *
 * @method     ChildExpertGroup[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildExpertGroup objects based on current ModelCriteria
 * @method     ChildExpertGroup[]|ObjectCollection findByGroupName(string $group_name) Return ChildExpertGroup objects filtered by the group_name column
 * @method     ChildExpertGroup[]|ObjectCollection findBySocCode(string $soc_code) Return ChildExpertGroup objects filtered by the soc_code column
 * @method     ChildExpertGroup[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ExpertGroupQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \Base\ExpertGroupQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'blueecon_faq', $modelName = '\\ExpertGroup', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildExpertGroupQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildExpertGroupQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildExpertGroupQuery) {
            return $criteria;
        }
        $query = new ChildExpertGroupQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildExpertGroup|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ExpertGroupTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ExpertGroupTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildExpertGroup A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT GROUP_NAME, SOC_CODE FROM expert_group WHERE GROUP_NAME = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_STR);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildExpertGroup $obj */
            $obj = new ChildExpertGroup();
            $obj->hydrate($row);
            ExpertGroupTableMap::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildExpertGroup|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildExpertGroupQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ExpertGroupTableMap::COL_GROUP_NAME, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildExpertGroupQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ExpertGroupTableMap::COL_GROUP_NAME, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the group_name column
     *
     * Example usage:
     * <code>
     * $query->filterByGroupName('fooValue');   // WHERE group_name = 'fooValue'
     * $query->filterByGroupName('%fooValue%'); // WHERE group_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $groupName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildExpertGroupQuery The current query, for fluid interface
     */
    public function filterByGroupName($groupName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($groupName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $groupName)) {
                $groupName = str_replace('*', '%', $groupName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ExpertGroupTableMap::COL_GROUP_NAME, $groupName, $comparison);
    }

    /**
     * Filter the query on the soc_code column
     *
     * Example usage:
     * <code>
     * $query->filterBySocCode('fooValue');   // WHERE soc_code = 'fooValue'
     * $query->filterBySocCode('%fooValue%'); // WHERE soc_code LIKE '%fooValue%'
     * </code>
     *
     * @param     string $socCode The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildExpertGroupQuery The current query, for fluid interface
     */
    public function filterBySocCode($socCode = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($socCode)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $socCode)) {
                $socCode = str_replace('*', '%', $socCode);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ExpertGroupTableMap::COL_SOC_CODE, $socCode, $comparison);
    }

    /**
     * Filter the query by a related \ExpertGroupMembers object
     *
     * @param \ExpertGroupMembers|ObjectCollection $expertGroupMembers  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildExpertGroupQuery The current query, for fluid interface
     */
    public function filterByExpertGroupMembers($expertGroupMembers, $comparison = null)
    {
        if ($expertGroupMembers instanceof \ExpertGroupMembers) {
            return $this
                ->addUsingAlias(ExpertGroupTableMap::COL_GROUP_NAME, $expertGroupMembers->getGroupName(), $comparison);
        } elseif ($expertGroupMembers instanceof ObjectCollection) {
            return $this
                ->useExpertGroupMembersQuery()
                ->filterByPrimaryKeys($expertGroupMembers->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByExpertGroupMembers() only accepts arguments of type \ExpertGroupMembers or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ExpertGroupMembers relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildExpertGroupQuery The current query, for fluid interface
     */
    public function joinExpertGroupMembers($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ExpertGroupMembers');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'ExpertGroupMembers');
        }

        return $this;
    }

    /**
     * Use the ExpertGroupMembers relation ExpertGroupMembers object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \ExpertGroupMembersQuery A secondary query class using the current class as primary query
     */
    public function useExpertGroupMembersQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinExpertGroupMembers($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ExpertGroupMembers', '\ExpertGroupMembersQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildExpertGroup $expertGroup Object to remove from the list of results
     *
     * @return $this|ChildExpertGroupQuery The current query, for fluid interface
     */
    public function prune($expertGroup = null)
    {
        if ($expertGroup) {
            $this->addUsingAlias(ExpertGroupTableMap::COL_GROUP_NAME, $expertGroup->getGroupName(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the expert_group table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ExpertGroupTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ExpertGroupTableMap::clearInstancePool();
            ExpertGroupTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ExpertGroupTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ExpertGroupTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ExpertGroupTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ExpertGroupTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // ExpertGroupQuery

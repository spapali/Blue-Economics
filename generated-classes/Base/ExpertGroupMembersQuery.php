<?php

namespace Base;

use \ExpertGroupMembers as ChildExpertGroupMembers;
use \ExpertGroupMembersQuery as ChildExpertGroupMembersQuery;
use \Exception;
use \PDO;
use Map\ExpertGroupMembersTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'expert_group_members' table.
 *
 *
 *
 * @method     ChildExpertGroupMembersQuery orderByExpert($order = Criteria::ASC) Order by the expert column
 * @method     ChildExpertGroupMembersQuery orderByGroupName($order = Criteria::ASC) Order by the group_name column
 *
 * @method     ChildExpertGroupMembersQuery groupByExpert() Group by the expert column
 * @method     ChildExpertGroupMembersQuery groupByGroupName() Group by the group_name column
 *
 * @method     ChildExpertGroupMembersQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildExpertGroupMembersQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildExpertGroupMembersQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildExpertGroupMembersQuery leftJoinExpertGroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the ExpertGroup relation
 * @method     ChildExpertGroupMembersQuery rightJoinExpertGroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ExpertGroup relation
 * @method     ChildExpertGroupMembersQuery innerJoinExpertGroup($relationAlias = null) Adds a INNER JOIN clause to the query using the ExpertGroup relation
 *
 * @method     ChildExpertGroupMembersQuery leftJoinExperts($relationAlias = null) Adds a LEFT JOIN clause to the query using the Experts relation
 * @method     ChildExpertGroupMembersQuery rightJoinExperts($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Experts relation
 * @method     ChildExpertGroupMembersQuery innerJoinExperts($relationAlias = null) Adds a INNER JOIN clause to the query using the Experts relation
 *
 * @method     \ExpertGroupQuery|\ExpertsQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildExpertGroupMembers findOne(ConnectionInterface $con = null) Return the first ChildExpertGroupMembers matching the query
 * @method     ChildExpertGroupMembers findOneOrCreate(ConnectionInterface $con = null) Return the first ChildExpertGroupMembers matching the query, or a new ChildExpertGroupMembers object populated from the query conditions when no match is found
 *
 * @method     ChildExpertGroupMembers findOneByExpert(string $expert) Return the first ChildExpertGroupMembers filtered by the expert column
 * @method     ChildExpertGroupMembers findOneByGroupName(string $group_name) Return the first ChildExpertGroupMembers filtered by the group_name column
 *
 * @method     ChildExpertGroupMembers[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildExpertGroupMembers objects based on current ModelCriteria
 * @method     ChildExpertGroupMembers[]|ObjectCollection findByExpert(string $expert) Return ChildExpertGroupMembers objects filtered by the expert column
 * @method     ChildExpertGroupMembers[]|ObjectCollection findByGroupName(string $group_name) Return ChildExpertGroupMembers objects filtered by the group_name column
 * @method     ChildExpertGroupMembers[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ExpertGroupMembersQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \Base\ExpertGroupMembersQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'blueecon_faq', $modelName = '\\ExpertGroupMembers', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildExpertGroupMembersQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildExpertGroupMembersQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildExpertGroupMembersQuery) {
            return $criteria;
        }
        $query = new ChildExpertGroupMembersQuery();
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
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array[$expert, $group_name] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildExpertGroupMembers|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ExpertGroupMembersTableMap::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ExpertGroupMembersTableMap::DATABASE_NAME);
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
     * @return ChildExpertGroupMembers A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT EXPERT, GROUP_NAME FROM expert_group_members WHERE EXPERT = :p0 AND GROUP_NAME = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_STR);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_STR);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildExpertGroupMembers $obj */
            $obj = new ChildExpertGroupMembers();
            $obj->hydrate($row);
            ExpertGroupMembersTableMap::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return ChildExpertGroupMembers|array|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
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
     * @return $this|ChildExpertGroupMembersQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(ExpertGroupMembersTableMap::COL_EXPERT, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(ExpertGroupMembersTableMap::COL_GROUP_NAME, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildExpertGroupMembersQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(ExpertGroupMembersTableMap::COL_EXPERT, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(ExpertGroupMembersTableMap::COL_GROUP_NAME, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the expert column
     *
     * Example usage:
     * <code>
     * $query->filterByExpert('fooValue');   // WHERE expert = 'fooValue'
     * $query->filterByExpert('%fooValue%'); // WHERE expert LIKE '%fooValue%'
     * </code>
     *
     * @param     string $expert The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildExpertGroupMembersQuery The current query, for fluid interface
     */
    public function filterByExpert($expert = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($expert)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $expert)) {
                $expert = str_replace('*', '%', $expert);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ExpertGroupMembersTableMap::COL_EXPERT, $expert, $comparison);
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
     * @return $this|ChildExpertGroupMembersQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ExpertGroupMembersTableMap::COL_GROUP_NAME, $groupName, $comparison);
    }

    /**
     * Filter the query by a related \ExpertGroup object
     *
     * @param \ExpertGroup|ObjectCollection $expertGroup The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildExpertGroupMembersQuery The current query, for fluid interface
     */
    public function filterByExpertGroup($expertGroup, $comparison = null)
    {
        if ($expertGroup instanceof \ExpertGroup) {
            return $this
                ->addUsingAlias(ExpertGroupMembersTableMap::COL_GROUP_NAME, $expertGroup->getGroupName(), $comparison);
        } elseif ($expertGroup instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ExpertGroupMembersTableMap::COL_GROUP_NAME, $expertGroup->toKeyValue('PrimaryKey', 'GroupName'), $comparison);
        } else {
            throw new PropelException('filterByExpertGroup() only accepts arguments of type \ExpertGroup or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ExpertGroup relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildExpertGroupMembersQuery The current query, for fluid interface
     */
    public function joinExpertGroup($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ExpertGroup');

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
            $this->addJoinObject($join, 'ExpertGroup');
        }

        return $this;
    }

    /**
     * Use the ExpertGroup relation ExpertGroup object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \ExpertGroupQuery A secondary query class using the current class as primary query
     */
    public function useExpertGroupQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinExpertGroup($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ExpertGroup', '\ExpertGroupQuery');
    }

    /**
     * Filter the query by a related \Experts object
     *
     * @param \Experts|ObjectCollection $experts The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildExpertGroupMembersQuery The current query, for fluid interface
     */
    public function filterByExperts($experts, $comparison = null)
    {
        if ($experts instanceof \Experts) {
            return $this
                ->addUsingAlias(ExpertGroupMembersTableMap::COL_EXPERT, $experts->getUsername(), $comparison);
        } elseif ($experts instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ExpertGroupMembersTableMap::COL_EXPERT, $experts->toKeyValue('PrimaryKey', 'Username'), $comparison);
        } else {
            throw new PropelException('filterByExperts() only accepts arguments of type \Experts or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Experts relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildExpertGroupMembersQuery The current query, for fluid interface
     */
    public function joinExperts($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Experts');

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
            $this->addJoinObject($join, 'Experts');
        }

        return $this;
    }

    /**
     * Use the Experts relation Experts object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \ExpertsQuery A secondary query class using the current class as primary query
     */
    public function useExpertsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinExperts($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Experts', '\ExpertsQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildExpertGroupMembers $expertGroupMembers Object to remove from the list of results
     *
     * @return $this|ChildExpertGroupMembersQuery The current query, for fluid interface
     */
    public function prune($expertGroupMembers = null)
    {
        if ($expertGroupMembers) {
            $this->addCond('pruneCond0', $this->getAliasedColName(ExpertGroupMembersTableMap::COL_EXPERT), $expertGroupMembers->getExpert(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(ExpertGroupMembersTableMap::COL_GROUP_NAME), $expertGroupMembers->getGroupName(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the expert_group_members table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ExpertGroupMembersTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ExpertGroupMembersTableMap::clearInstancePool();
            ExpertGroupMembersTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ExpertGroupMembersTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ExpertGroupMembersTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ExpertGroupMembersTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ExpertGroupMembersTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // ExpertGroupMembersQuery

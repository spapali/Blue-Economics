<?php

namespace Base;

use \Experts as ChildExperts;
use \ExpertsQuery as ChildExpertsQuery;
use \Exception;
use \PDO;
use Map\ExpertsTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'experts' table.
 *
 *
 *
 * @method     ChildExpertsQuery orderByUsername($order = Criteria::ASC) Order by the username column
 * @method     ChildExpertsQuery orderByFirstName($order = Criteria::ASC) Order by the first_name column
 * @method     ChildExpertsQuery orderByLastName($order = Criteria::ASC) Order by the last_name column
 * @method     ChildExpertsQuery orderByBio($order = Criteria::ASC) Order by the bio column
 * @method     ChildExpertsQuery orderByOrganization($order = Criteria::ASC) Order by the organization column
 * @method     ChildExpertsQuery orderByPassword($order = Criteria::ASC) Order by the password column
 *
 * @method     ChildExpertsQuery groupByUsername() Group by the username column
 * @method     ChildExpertsQuery groupByFirstName() Group by the first_name column
 * @method     ChildExpertsQuery groupByLastName() Group by the last_name column
 * @method     ChildExpertsQuery groupByBio() Group by the bio column
 * @method     ChildExpertsQuery groupByOrganization() Group by the organization column
 * @method     ChildExpertsQuery groupByPassword() Group by the password column
 *
 * @method     ChildExpertsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildExpertsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildExpertsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildExpertsQuery leftJoinExpertGroupMembers($relationAlias = null) Adds a LEFT JOIN clause to the query using the ExpertGroupMembers relation
 * @method     ChildExpertsQuery rightJoinExpertGroupMembers($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ExpertGroupMembers relation
 * @method     ChildExpertsQuery innerJoinExpertGroupMembers($relationAlias = null) Adds a INNER JOIN clause to the query using the ExpertGroupMembers relation
 *
 * @method     ChildExpertsQuery leftJoinExpertQuestionState($relationAlias = null) Adds a LEFT JOIN clause to the query using the ExpertQuestionState relation
 * @method     ChildExpertsQuery rightJoinExpertQuestionState($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ExpertQuestionState relation
 * @method     ChildExpertsQuery innerJoinExpertQuestionState($relationAlias = null) Adds a INNER JOIN clause to the query using the ExpertQuestionState relation
 *
 * @method     ChildExpertsQuery leftJoinResponses($relationAlias = null) Adds a LEFT JOIN clause to the query using the Responses relation
 * @method     ChildExpertsQuery rightJoinResponses($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Responses relation
 * @method     ChildExpertsQuery innerJoinResponses($relationAlias = null) Adds a INNER JOIN clause to the query using the Responses relation
 *
 * @method     \ExpertGroupMembersQuery|\ExpertQuestionStateQuery|\ResponsesQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildExperts findOne(ConnectionInterface $con = null) Return the first ChildExperts matching the query
 * @method     ChildExperts findOneOrCreate(ConnectionInterface $con = null) Return the first ChildExperts matching the query, or a new ChildExperts object populated from the query conditions when no match is found
 *
 * @method     ChildExperts findOneByUsername(string $username) Return the first ChildExperts filtered by the username column
 * @method     ChildExperts findOneByFirstName(string $first_name) Return the first ChildExperts filtered by the first_name column
 * @method     ChildExperts findOneByLastName(string $last_name) Return the first ChildExperts filtered by the last_name column
 * @method     ChildExperts findOneByBio(string $bio) Return the first ChildExperts filtered by the bio column
 * @method     ChildExperts findOneByOrganization(string $organization) Return the first ChildExperts filtered by the organization column
 * @method     ChildExperts findOneByPassword(string $password) Return the first ChildExperts filtered by the password column
 *
 * @method     ChildExperts[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildExperts objects based on current ModelCriteria
 * @method     ChildExperts[]|ObjectCollection findByUsername(string $username) Return ChildExperts objects filtered by the username column
 * @method     ChildExperts[]|ObjectCollection findByFirstName(string $first_name) Return ChildExperts objects filtered by the first_name column
 * @method     ChildExperts[]|ObjectCollection findByLastName(string $last_name) Return ChildExperts objects filtered by the last_name column
 * @method     ChildExperts[]|ObjectCollection findByBio(string $bio) Return ChildExperts objects filtered by the bio column
 * @method     ChildExperts[]|ObjectCollection findByOrganization(string $organization) Return ChildExperts objects filtered by the organization column
 * @method     ChildExperts[]|ObjectCollection findByPassword(string $password) Return ChildExperts objects filtered by the password column
 * @method     ChildExperts[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ExpertsQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \Base\ExpertsQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'blueecon_faq', $modelName = '\\Experts', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildExpertsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildExpertsQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildExpertsQuery) {
            return $criteria;
        }
        $query = new ChildExpertsQuery();
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
     * @return ChildExperts|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ExpertsTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ExpertsTableMap::DATABASE_NAME);
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
     * @return ChildExperts A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT USERNAME, FIRST_NAME, LAST_NAME, BIO, ORGANIZATION, PASSWORD FROM experts WHERE USERNAME = :p0';
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
            /** @var ChildExperts $obj */
            $obj = new ChildExperts();
            $obj->hydrate($row);
            ExpertsTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildExperts|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildExpertsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ExpertsTableMap::COL_USERNAME, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildExpertsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ExpertsTableMap::COL_USERNAME, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the username column
     *
     * Example usage:
     * <code>
     * $query->filterByUsername('fooValue');   // WHERE username = 'fooValue'
     * $query->filterByUsername('%fooValue%'); // WHERE username LIKE '%fooValue%'
     * </code>
     *
     * @param     string $username The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildExpertsQuery The current query, for fluid interface
     */
    public function filterByUsername($username = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($username)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $username)) {
                $username = str_replace('*', '%', $username);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ExpertsTableMap::COL_USERNAME, $username, $comparison);
    }

    /**
     * Filter the query on the first_name column
     *
     * Example usage:
     * <code>
     * $query->filterByFirstName('fooValue');   // WHERE first_name = 'fooValue'
     * $query->filterByFirstName('%fooValue%'); // WHERE first_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $firstName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildExpertsQuery The current query, for fluid interface
     */
    public function filterByFirstName($firstName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($firstName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $firstName)) {
                $firstName = str_replace('*', '%', $firstName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ExpertsTableMap::COL_FIRST_NAME, $firstName, $comparison);
    }

    /**
     * Filter the query on the last_name column
     *
     * Example usage:
     * <code>
     * $query->filterByLastName('fooValue');   // WHERE last_name = 'fooValue'
     * $query->filterByLastName('%fooValue%'); // WHERE last_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $lastName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildExpertsQuery The current query, for fluid interface
     */
    public function filterByLastName($lastName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($lastName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $lastName)) {
                $lastName = str_replace('*', '%', $lastName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ExpertsTableMap::COL_LAST_NAME, $lastName, $comparison);
    }

    /**
     * Filter the query on the bio column
     *
     * Example usage:
     * <code>
     * $query->filterByBio('fooValue');   // WHERE bio = 'fooValue'
     * $query->filterByBio('%fooValue%'); // WHERE bio LIKE '%fooValue%'
     * </code>
     *
     * @param     string $bio The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildExpertsQuery The current query, for fluid interface
     */
    public function filterByBio($bio = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($bio)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $bio)) {
                $bio = str_replace('*', '%', $bio);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ExpertsTableMap::COL_BIO, $bio, $comparison);
    }

    /**
     * Filter the query on the organization column
     *
     * Example usage:
     * <code>
     * $query->filterByOrganization('fooValue');   // WHERE organization = 'fooValue'
     * $query->filterByOrganization('%fooValue%'); // WHERE organization LIKE '%fooValue%'
     * </code>
     *
     * @param     string $organization The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildExpertsQuery The current query, for fluid interface
     */
    public function filterByOrganization($organization = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($organization)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $organization)) {
                $organization = str_replace('*', '%', $organization);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ExpertsTableMap::COL_ORGANIZATION, $organization, $comparison);
    }

    /**
     * Filter the query on the password column
     *
     * Example usage:
     * <code>
     * $query->filterByPassword('fooValue');   // WHERE password = 'fooValue'
     * $query->filterByPassword('%fooValue%'); // WHERE password LIKE '%fooValue%'
     * </code>
     *
     * @param     string $password The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildExpertsQuery The current query, for fluid interface
     */
    public function filterByPassword($password = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($password)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $password)) {
                $password = str_replace('*', '%', $password);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ExpertsTableMap::COL_PASSWORD, $password, $comparison);
    }

    /**
     * Filter the query by a related \ExpertGroupMembers object
     *
     * @param \ExpertGroupMembers|ObjectCollection $expertGroupMembers  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildExpertsQuery The current query, for fluid interface
     */
    public function filterByExpertGroupMembers($expertGroupMembers, $comparison = null)
    {
        if ($expertGroupMembers instanceof \ExpertGroupMembers) {
            return $this
                ->addUsingAlias(ExpertsTableMap::COL_USERNAME, $expertGroupMembers->getExpert(), $comparison);
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
     * @return $this|ChildExpertsQuery The current query, for fluid interface
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
     * Filter the query by a related \ExpertQuestionState object
     *
     * @param \ExpertQuestionState|ObjectCollection $expertQuestionState  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildExpertsQuery The current query, for fluid interface
     */
    public function filterByExpertQuestionState($expertQuestionState, $comparison = null)
    {
        if ($expertQuestionState instanceof \ExpertQuestionState) {
            return $this
                ->addUsingAlias(ExpertsTableMap::COL_USERNAME, $expertQuestionState->getUsername(), $comparison);
        } elseif ($expertQuestionState instanceof ObjectCollection) {
            return $this
                ->useExpertQuestionStateQuery()
                ->filterByPrimaryKeys($expertQuestionState->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByExpertQuestionState() only accepts arguments of type \ExpertQuestionState or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ExpertQuestionState relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildExpertsQuery The current query, for fluid interface
     */
    public function joinExpertQuestionState($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ExpertQuestionState');

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
            $this->addJoinObject($join, 'ExpertQuestionState');
        }

        return $this;
    }

    /**
     * Use the ExpertQuestionState relation ExpertQuestionState object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \ExpertQuestionStateQuery A secondary query class using the current class as primary query
     */
    public function useExpertQuestionStateQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinExpertQuestionState($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ExpertQuestionState', '\ExpertQuestionStateQuery');
    }

    /**
     * Filter the query by a related \Responses object
     *
     * @param \Responses|ObjectCollection $responses  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildExpertsQuery The current query, for fluid interface
     */
    public function filterByResponses($responses, $comparison = null)
    {
        if ($responses instanceof \Responses) {
            return $this
                ->addUsingAlias(ExpertsTableMap::COL_USERNAME, $responses->getExpert(), $comparison);
        } elseif ($responses instanceof ObjectCollection) {
            return $this
                ->useResponsesQuery()
                ->filterByPrimaryKeys($responses->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByResponses() only accepts arguments of type \Responses or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Responses relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildExpertsQuery The current query, for fluid interface
     */
    public function joinResponses($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Responses');

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
            $this->addJoinObject($join, 'Responses');
        }

        return $this;
    }

    /**
     * Use the Responses relation Responses object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \ResponsesQuery A secondary query class using the current class as primary query
     */
    public function useResponsesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinResponses($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Responses', '\ResponsesQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildExperts $experts Object to remove from the list of results
     *
     * @return $this|ChildExpertsQuery The current query, for fluid interface
     */
    public function prune($experts = null)
    {
        if ($experts) {
            $this->addUsingAlias(ExpertsTableMap::COL_USERNAME, $experts->getUsername(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the experts table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ExpertsTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ExpertsTableMap::clearInstancePool();
            ExpertsTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ExpertsTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ExpertsTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ExpertsTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ExpertsTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // ExpertsQuery

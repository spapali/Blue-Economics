<?php

namespace Base;

use \Responses as ChildResponses;
use \ResponsesQuery as ChildResponsesQuery;
use \Exception;
use \PDO;
use Map\ResponsesTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'responses' table.
 *
 *
 *
 * @method     ChildResponsesQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildResponsesQuery orderByExpert($order = Criteria::ASC) Order by the expert column
 * @method     ChildResponsesQuery orderByQuestionId($order = Criteria::ASC) Order by the question_id column
 * @method     ChildResponsesQuery orderByCreated($order = Criteria::ASC) Order by the created column
 * @method     ChildResponsesQuery orderByResponse($order = Criteria::ASC) Order by the response column
 * @method     ChildResponsesQuery orderByVotes($order = Criteria::ASC) Order by the votes column
 *
 * @method     ChildResponsesQuery groupById() Group by the id column
 * @method     ChildResponsesQuery groupByExpert() Group by the expert column
 * @method     ChildResponsesQuery groupByQuestionId() Group by the question_id column
 * @method     ChildResponsesQuery groupByCreated() Group by the created column
 * @method     ChildResponsesQuery groupByResponse() Group by the response column
 * @method     ChildResponsesQuery groupByVotes() Group by the votes column
 *
 * @method     ChildResponsesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildResponsesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildResponsesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildResponsesQuery leftJoinExperts($relationAlias = null) Adds a LEFT JOIN clause to the query using the Experts relation
 * @method     ChildResponsesQuery rightJoinExperts($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Experts relation
 * @method     ChildResponsesQuery innerJoinExperts($relationAlias = null) Adds a INNER JOIN clause to the query using the Experts relation
 *
 * @method     ChildResponsesQuery leftJoinQuestions($relationAlias = null) Adds a LEFT JOIN clause to the query using the Questions relation
 * @method     ChildResponsesQuery rightJoinQuestions($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Questions relation
 * @method     ChildResponsesQuery innerJoinQuestions($relationAlias = null) Adds a INNER JOIN clause to the query using the Questions relation
 *
 * @method     \ExpertsQuery|\QuestionsQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildResponses findOne(ConnectionInterface $con = null) Return the first ChildResponses matching the query
 * @method     ChildResponses findOneOrCreate(ConnectionInterface $con = null) Return the first ChildResponses matching the query, or a new ChildResponses object populated from the query conditions when no match is found
 *
 * @method     ChildResponses findOneById(string $id) Return the first ChildResponses filtered by the id column
 * @method     ChildResponses findOneByExpert(string $expert) Return the first ChildResponses filtered by the expert column
 * @method     ChildResponses findOneByQuestionId(string $question_id) Return the first ChildResponses filtered by the question_id column
 * @method     ChildResponses findOneByCreated(string $created) Return the first ChildResponses filtered by the created column
 * @method     ChildResponses findOneByResponse(string $response) Return the first ChildResponses filtered by the response column
 * @method     ChildResponses findOneByVotes(int $votes) Return the first ChildResponses filtered by the votes column
 *
 * @method     ChildResponses[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildResponses objects based on current ModelCriteria
 * @method     ChildResponses[]|ObjectCollection findById(string $id) Return ChildResponses objects filtered by the id column
 * @method     ChildResponses[]|ObjectCollection findByExpert(string $expert) Return ChildResponses objects filtered by the expert column
 * @method     ChildResponses[]|ObjectCollection findByQuestionId(string $question_id) Return ChildResponses objects filtered by the question_id column
 * @method     ChildResponses[]|ObjectCollection findByCreated(string $created) Return ChildResponses objects filtered by the created column
 * @method     ChildResponses[]|ObjectCollection findByResponse(string $response) Return ChildResponses objects filtered by the response column
 * @method     ChildResponses[]|ObjectCollection findByVotes(int $votes) Return ChildResponses objects filtered by the votes column
 * @method     ChildResponses[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ResponsesQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \Base\ResponsesQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'blueecon_faq', $modelName = '\\Responses', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildResponsesQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildResponsesQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildResponsesQuery) {
            return $criteria;
        }
        $query = new ChildResponsesQuery();
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
     * @return ChildResponses|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ResponsesTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ResponsesTableMap::DATABASE_NAME);
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
     * @return ChildResponses A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT ID, EXPERT, QUESTION_ID, CREATED, RESPONSE, VOTES FROM responses WHERE ID = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildResponses $obj */
            $obj = new ChildResponses();
            $obj->hydrate($row);
            ResponsesTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildResponses|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildResponsesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ResponsesTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildResponsesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ResponsesTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildResponsesQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ResponsesTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ResponsesTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ResponsesTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildResponsesQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ResponsesTableMap::COL_EXPERT, $expert, $comparison);
    }

    /**
     * Filter the query on the question_id column
     *
     * Example usage:
     * <code>
     * $query->filterByQuestionId(1234); // WHERE question_id = 1234
     * $query->filterByQuestionId(array(12, 34)); // WHERE question_id IN (12, 34)
     * $query->filterByQuestionId(array('min' => 12)); // WHERE question_id > 12
     * </code>
     *
     * @see       filterByQuestions()
     *
     * @param     mixed $questionId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildResponsesQuery The current query, for fluid interface
     */
    public function filterByQuestionId($questionId = null, $comparison = null)
    {
        if (is_array($questionId)) {
            $useMinMax = false;
            if (isset($questionId['min'])) {
                $this->addUsingAlias(ResponsesTableMap::COL_QUESTION_ID, $questionId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($questionId['max'])) {
                $this->addUsingAlias(ResponsesTableMap::COL_QUESTION_ID, $questionId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ResponsesTableMap::COL_QUESTION_ID, $questionId, $comparison);
    }

    /**
     * Filter the query on the created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreated('2011-03-14'); // WHERE created = '2011-03-14'
     * $query->filterByCreated('now'); // WHERE created = '2011-03-14'
     * $query->filterByCreated(array('max' => 'yesterday')); // WHERE created > '2011-03-13'
     * </code>
     *
     * @param     mixed $created The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildResponsesQuery The current query, for fluid interface
     */
    public function filterByCreated($created = null, $comparison = null)
    {
        if (is_array($created)) {
            $useMinMax = false;
            if (isset($created['min'])) {
                $this->addUsingAlias(ResponsesTableMap::COL_CREATED, $created['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($created['max'])) {
                $this->addUsingAlias(ResponsesTableMap::COL_CREATED, $created['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ResponsesTableMap::COL_CREATED, $created, $comparison);
    }

    /**
     * Filter the query on the response column
     *
     * Example usage:
     * <code>
     * $query->filterByResponse('fooValue');   // WHERE response = 'fooValue'
     * $query->filterByResponse('%fooValue%'); // WHERE response LIKE '%fooValue%'
     * </code>
     *
     * @param     string $response The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildResponsesQuery The current query, for fluid interface
     */
    public function filterByResponse($response = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($response)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $response)) {
                $response = str_replace('*', '%', $response);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ResponsesTableMap::COL_RESPONSE, $response, $comparison);
    }

    /**
     * Filter the query on the votes column
     *
     * Example usage:
     * <code>
     * $query->filterByVotes(1234); // WHERE votes = 1234
     * $query->filterByVotes(array(12, 34)); // WHERE votes IN (12, 34)
     * $query->filterByVotes(array('min' => 12)); // WHERE votes > 12
     * </code>
     *
     * @param     mixed $votes The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildResponsesQuery The current query, for fluid interface
     */
    public function filterByVotes($votes = null, $comparison = null)
    {
        if (is_array($votes)) {
            $useMinMax = false;
            if (isset($votes['min'])) {
                $this->addUsingAlias(ResponsesTableMap::COL_VOTES, $votes['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($votes['max'])) {
                $this->addUsingAlias(ResponsesTableMap::COL_VOTES, $votes['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ResponsesTableMap::COL_VOTES, $votes, $comparison);
    }

    /**
     * Filter the query by a related \Experts object
     *
     * @param \Experts|ObjectCollection $experts The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildResponsesQuery The current query, for fluid interface
     */
    public function filterByExperts($experts, $comparison = null)
    {
        if ($experts instanceof \Experts) {
            return $this
                ->addUsingAlias(ResponsesTableMap::COL_EXPERT, $experts->getUsername(), $comparison);
        } elseif ($experts instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ResponsesTableMap::COL_EXPERT, $experts->toKeyValue('PrimaryKey', 'Username'), $comparison);
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
     * @return $this|ChildResponsesQuery The current query, for fluid interface
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
     * Filter the query by a related \Questions object
     *
     * @param \Questions|ObjectCollection $questions The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildResponsesQuery The current query, for fluid interface
     */
    public function filterByQuestions($questions, $comparison = null)
    {
        if ($questions instanceof \Questions) {
            return $this
                ->addUsingAlias(ResponsesTableMap::COL_QUESTION_ID, $questions->getId(), $comparison);
        } elseif ($questions instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ResponsesTableMap::COL_QUESTION_ID, $questions->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByQuestions() only accepts arguments of type \Questions or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Questions relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildResponsesQuery The current query, for fluid interface
     */
    public function joinQuestions($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Questions');

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
            $this->addJoinObject($join, 'Questions');
        }

        return $this;
    }

    /**
     * Use the Questions relation Questions object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \QuestionsQuery A secondary query class using the current class as primary query
     */
    public function useQuestionsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinQuestions($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Questions', '\QuestionsQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildResponses $responses Object to remove from the list of results
     *
     * @return $this|ChildResponsesQuery The current query, for fluid interface
     */
    public function prune($responses = null)
    {
        if ($responses) {
            $this->addUsingAlias(ResponsesTableMap::COL_ID, $responses->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the responses table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ResponsesTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ResponsesTableMap::clearInstancePool();
            ResponsesTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ResponsesTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ResponsesTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ResponsesTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ResponsesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // ResponsesQuery

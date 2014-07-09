<?php

namespace Base;

use \Questions as ChildQuestions;
use \QuestionsQuery as ChildQuestionsQuery;
use \Exception;
use \PDO;
use Map\QuestionsTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'questions' table.
 *
 *
 *
 * @method     ChildQuestionsQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildQuestionsQuery orderBySubmitter($order = Criteria::ASC) Order by the submitter column
 * @method     ChildQuestionsQuery orderByQuestion($order = Criteria::ASC) Order by the question column
 * @method     ChildQuestionsQuery orderByCreated($order = Criteria::ASC) Order by the created column
 * @method     ChildQuestionsQuery orderBySocCode($order = Criteria::ASC) Order by the soc_code column
 *
 * @method     ChildQuestionsQuery groupById() Group by the id column
 * @method     ChildQuestionsQuery groupBySubmitter() Group by the submitter column
 * @method     ChildQuestionsQuery groupByQuestion() Group by the question column
 * @method     ChildQuestionsQuery groupByCreated() Group by the created column
 * @method     ChildQuestionsQuery groupBySocCode() Group by the soc_code column
 *
 * @method     ChildQuestionsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildQuestionsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildQuestionsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildQuestionsQuery leftJoinExpertQuestionState($relationAlias = null) Adds a LEFT JOIN clause to the query using the ExpertQuestionState relation
 * @method     ChildQuestionsQuery rightJoinExpertQuestionState($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ExpertQuestionState relation
 * @method     ChildQuestionsQuery innerJoinExpertQuestionState($relationAlias = null) Adds a INNER JOIN clause to the query using the ExpertQuestionState relation
 *
 * @method     ChildQuestionsQuery leftJoinResponses($relationAlias = null) Adds a LEFT JOIN clause to the query using the Responses relation
 * @method     ChildQuestionsQuery rightJoinResponses($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Responses relation
 * @method     ChildQuestionsQuery innerJoinResponses($relationAlias = null) Adds a INNER JOIN clause to the query using the Responses relation
 *
 * @method     \ExpertQuestionStateQuery|\ResponsesQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildQuestions findOne(ConnectionInterface $con = null) Return the first ChildQuestions matching the query
 * @method     ChildQuestions findOneOrCreate(ConnectionInterface $con = null) Return the first ChildQuestions matching the query, or a new ChildQuestions object populated from the query conditions when no match is found
 *
 * @method     ChildQuestions findOneById(string $id) Return the first ChildQuestions filtered by the id column
 * @method     ChildQuestions findOneBySubmitter(string $submitter) Return the first ChildQuestions filtered by the submitter column
 * @method     ChildQuestions findOneByQuestion(string $question) Return the first ChildQuestions filtered by the question column
 * @method     ChildQuestions findOneByCreated(string $created) Return the first ChildQuestions filtered by the created column
 * @method     ChildQuestions findOneBySocCode(string $soc_code) Return the first ChildQuestions filtered by the soc_code column
 *
 * @method     ChildQuestions[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildQuestions objects based on current ModelCriteria
 * @method     ChildQuestions[]|ObjectCollection findById(string $id) Return ChildQuestions objects filtered by the id column
 * @method     ChildQuestions[]|ObjectCollection findBySubmitter(string $submitter) Return ChildQuestions objects filtered by the submitter column
 * @method     ChildQuestions[]|ObjectCollection findByQuestion(string $question) Return ChildQuestions objects filtered by the question column
 * @method     ChildQuestions[]|ObjectCollection findByCreated(string $created) Return ChildQuestions objects filtered by the created column
 * @method     ChildQuestions[]|ObjectCollection findBySocCode(string $soc_code) Return ChildQuestions objects filtered by the soc_code column
 * @method     ChildQuestions[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class QuestionsQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \Base\QuestionsQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'blueecon_faq', $modelName = '\\Questions', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildQuestionsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildQuestionsQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildQuestionsQuery) {
            return $criteria;
        }
        $query = new ChildQuestionsQuery();
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
     * @return ChildQuestions|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = QuestionsTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(QuestionsTableMap::DATABASE_NAME);
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
     * @return ChildQuestions A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT ID, SUBMITTER, QUESTION, CREATED, SOC_CODE FROM questions WHERE ID = :p0';
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
            /** @var ChildQuestions $obj */
            $obj = new ChildQuestions();
            $obj->hydrate($row);
            QuestionsTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildQuestions|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildQuestionsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(QuestionsTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildQuestionsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(QuestionsTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildQuestionsQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(QuestionsTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(QuestionsTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(QuestionsTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the submitter column
     *
     * Example usage:
     * <code>
     * $query->filterBySubmitter('fooValue');   // WHERE submitter = 'fooValue'
     * $query->filterBySubmitter('%fooValue%'); // WHERE submitter LIKE '%fooValue%'
     * </code>
     *
     * @param     string $submitter The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildQuestionsQuery The current query, for fluid interface
     */
    public function filterBySubmitter($submitter = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($submitter)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $submitter)) {
                $submitter = str_replace('*', '%', $submitter);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(QuestionsTableMap::COL_SUBMITTER, $submitter, $comparison);
    }

    /**
     * Filter the query on the question column
     *
     * Example usage:
     * <code>
     * $query->filterByQuestion('fooValue');   // WHERE question = 'fooValue'
     * $query->filterByQuestion('%fooValue%'); // WHERE question LIKE '%fooValue%'
     * </code>
     *
     * @param     string $question The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildQuestionsQuery The current query, for fluid interface
     */
    public function filterByQuestion($question = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($question)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $question)) {
                $question = str_replace('*', '%', $question);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(QuestionsTableMap::COL_QUESTION, $question, $comparison);
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
     * @return $this|ChildQuestionsQuery The current query, for fluid interface
     */
    public function filterByCreated($created = null, $comparison = null)
    {
        if (is_array($created)) {
            $useMinMax = false;
            if (isset($created['min'])) {
                $this->addUsingAlias(QuestionsTableMap::COL_CREATED, $created['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($created['max'])) {
                $this->addUsingAlias(QuestionsTableMap::COL_CREATED, $created['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(QuestionsTableMap::COL_CREATED, $created, $comparison);
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
     * @return $this|ChildQuestionsQuery The current query, for fluid interface
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

        return $this->addUsingAlias(QuestionsTableMap::COL_SOC_CODE, $socCode, $comparison);
    }

    /**
     * Filter the query by a related \ExpertQuestionState object
     *
     * @param \ExpertQuestionState|ObjectCollection $expertQuestionState  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildQuestionsQuery The current query, for fluid interface
     */
    public function filterByExpertQuestionState($expertQuestionState, $comparison = null)
    {
        if ($expertQuestionState instanceof \ExpertQuestionState) {
            return $this
                ->addUsingAlias(QuestionsTableMap::COL_ID, $expertQuestionState->getQuestionId(), $comparison);
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
     * @return $this|ChildQuestionsQuery The current query, for fluid interface
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
     * @return ChildQuestionsQuery The current query, for fluid interface
     */
    public function filterByResponses($responses, $comparison = null)
    {
        if ($responses instanceof \Responses) {
            return $this
                ->addUsingAlias(QuestionsTableMap::COL_ID, $responses->getQuestionId(), $comparison);
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
     * @return $this|ChildQuestionsQuery The current query, for fluid interface
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
     * @param   ChildQuestions $questions Object to remove from the list of results
     *
     * @return $this|ChildQuestionsQuery The current query, for fluid interface
     */
    public function prune($questions = null)
    {
        if ($questions) {
            $this->addUsingAlias(QuestionsTableMap::COL_ID, $questions->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the questions table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(QuestionsTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            QuestionsTableMap::clearInstancePool();
            QuestionsTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(QuestionsTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(QuestionsTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            QuestionsTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            QuestionsTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // QuestionsQuery

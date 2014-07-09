<?php

namespace Base;

use \ExpertQuestionState as ChildExpertQuestionState;
use \ExpertQuestionStateQuery as ChildExpertQuestionStateQuery;
use \Exception;
use Map\ExpertQuestionStateTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'expert_question_state' table.
 *
 *
 *
 * @method     ChildExpertQuestionStateQuery orderByUsername($order = Criteria::ASC) Order by the username column
 * @method     ChildExpertQuestionStateQuery orderByQuestionId($order = Criteria::ASC) Order by the question_id column
 * @method     ChildExpertQuestionStateQuery orderByIsRead($order = Criteria::ASC) Order by the is_read column
 * @method     ChildExpertQuestionStateQuery orderByIsResponded($order = Criteria::ASC) Order by the is_responded column
 * @method     ChildExpertQuestionStateQuery orderByIsExpunged($order = Criteria::ASC) Order by the is_expunged column
 * @method     ChildExpertQuestionStateQuery orderByIsMuted($order = Criteria::ASC) Order by the is_muted column
 *
 * @method     ChildExpertQuestionStateQuery groupByUsername() Group by the username column
 * @method     ChildExpertQuestionStateQuery groupByQuestionId() Group by the question_id column
 * @method     ChildExpertQuestionStateQuery groupByIsRead() Group by the is_read column
 * @method     ChildExpertQuestionStateQuery groupByIsResponded() Group by the is_responded column
 * @method     ChildExpertQuestionStateQuery groupByIsExpunged() Group by the is_expunged column
 * @method     ChildExpertQuestionStateQuery groupByIsMuted() Group by the is_muted column
 *
 * @method     ChildExpertQuestionStateQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildExpertQuestionStateQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildExpertQuestionStateQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildExpertQuestionStateQuery leftJoinExperts($relationAlias = null) Adds a LEFT JOIN clause to the query using the Experts relation
 * @method     ChildExpertQuestionStateQuery rightJoinExperts($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Experts relation
 * @method     ChildExpertQuestionStateQuery innerJoinExperts($relationAlias = null) Adds a INNER JOIN clause to the query using the Experts relation
 *
 * @method     ChildExpertQuestionStateQuery leftJoinQuestions($relationAlias = null) Adds a LEFT JOIN clause to the query using the Questions relation
 * @method     ChildExpertQuestionStateQuery rightJoinQuestions($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Questions relation
 * @method     ChildExpertQuestionStateQuery innerJoinQuestions($relationAlias = null) Adds a INNER JOIN clause to the query using the Questions relation
 *
 * @method     \ExpertsQuery|\QuestionsQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildExpertQuestionState findOne(ConnectionInterface $con = null) Return the first ChildExpertQuestionState matching the query
 * @method     ChildExpertQuestionState findOneOrCreate(ConnectionInterface $con = null) Return the first ChildExpertQuestionState matching the query, or a new ChildExpertQuestionState object populated from the query conditions when no match is found
 *
 * @method     ChildExpertQuestionState findOneByUsername(string $username) Return the first ChildExpertQuestionState filtered by the username column
 * @method     ChildExpertQuestionState findOneByQuestionId(string $question_id) Return the first ChildExpertQuestionState filtered by the question_id column
 * @method     ChildExpertQuestionState findOneByIsRead(boolean $is_read) Return the first ChildExpertQuestionState filtered by the is_read column
 * @method     ChildExpertQuestionState findOneByIsResponded(boolean $is_responded) Return the first ChildExpertQuestionState filtered by the is_responded column
 * @method     ChildExpertQuestionState findOneByIsExpunged(boolean $is_expunged) Return the first ChildExpertQuestionState filtered by the is_expunged column
 * @method     ChildExpertQuestionState findOneByIsMuted(boolean $is_muted) Return the first ChildExpertQuestionState filtered by the is_muted column
 *
 * @method     ChildExpertQuestionState[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildExpertQuestionState objects based on current ModelCriteria
 * @method     ChildExpertQuestionState[]|ObjectCollection findByUsername(string $username) Return ChildExpertQuestionState objects filtered by the username column
 * @method     ChildExpertQuestionState[]|ObjectCollection findByQuestionId(string $question_id) Return ChildExpertQuestionState objects filtered by the question_id column
 * @method     ChildExpertQuestionState[]|ObjectCollection findByIsRead(boolean $is_read) Return ChildExpertQuestionState objects filtered by the is_read column
 * @method     ChildExpertQuestionState[]|ObjectCollection findByIsResponded(boolean $is_responded) Return ChildExpertQuestionState objects filtered by the is_responded column
 * @method     ChildExpertQuestionState[]|ObjectCollection findByIsExpunged(boolean $is_expunged) Return ChildExpertQuestionState objects filtered by the is_expunged column
 * @method     ChildExpertQuestionState[]|ObjectCollection findByIsMuted(boolean $is_muted) Return ChildExpertQuestionState objects filtered by the is_muted column
 * @method     ChildExpertQuestionState[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ExpertQuestionStateQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \Base\ExpertQuestionStateQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'blueecon_faq', $modelName = '\\ExpertQuestionState', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildExpertQuestionStateQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildExpertQuestionStateQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildExpertQuestionStateQuery) {
            return $criteria;
        }
        $query = new ChildExpertQuestionStateQuery();
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
     * @return ChildExpertQuestionState|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        throw new LogicException('The ExpertQuestionState object has no primary key');
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
        throw new LogicException('The ExpertQuestionState object has no primary key');
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildExpertQuestionStateQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        throw new LogicException('The ExpertQuestionState object has no primary key');
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildExpertQuestionStateQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        throw new LogicException('The ExpertQuestionState object has no primary key');
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
     * @return $this|ChildExpertQuestionStateQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ExpertQuestionStateTableMap::COL_USERNAME, $username, $comparison);
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
     * @return $this|ChildExpertQuestionStateQuery The current query, for fluid interface
     */
    public function filterByQuestionId($questionId = null, $comparison = null)
    {
        if (is_array($questionId)) {
            $useMinMax = false;
            if (isset($questionId['min'])) {
                $this->addUsingAlias(ExpertQuestionStateTableMap::COL_QUESTION_ID, $questionId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($questionId['max'])) {
                $this->addUsingAlias(ExpertQuestionStateTableMap::COL_QUESTION_ID, $questionId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ExpertQuestionStateTableMap::COL_QUESTION_ID, $questionId, $comparison);
    }

    /**
     * Filter the query on the is_read column
     *
     * Example usage:
     * <code>
     * $query->filterByIsRead(true); // WHERE is_read = true
     * $query->filterByIsRead('yes'); // WHERE is_read = true
     * </code>
     *
     * @param     boolean|string $isRead The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildExpertQuestionStateQuery The current query, for fluid interface
     */
    public function filterByIsRead($isRead = null, $comparison = null)
    {
        if (is_string($isRead)) {
            $isRead = in_array(strtolower($isRead), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(ExpertQuestionStateTableMap::COL_IS_READ, $isRead, $comparison);
    }

    /**
     * Filter the query on the is_responded column
     *
     * Example usage:
     * <code>
     * $query->filterByIsResponded(true); // WHERE is_responded = true
     * $query->filterByIsResponded('yes'); // WHERE is_responded = true
     * </code>
     *
     * @param     boolean|string $isResponded The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildExpertQuestionStateQuery The current query, for fluid interface
     */
    public function filterByIsResponded($isResponded = null, $comparison = null)
    {
        if (is_string($isResponded)) {
            $isResponded = in_array(strtolower($isResponded), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(ExpertQuestionStateTableMap::COL_IS_RESPONDED, $isResponded, $comparison);
    }

    /**
     * Filter the query on the is_expunged column
     *
     * Example usage:
     * <code>
     * $query->filterByIsExpunged(true); // WHERE is_expunged = true
     * $query->filterByIsExpunged('yes'); // WHERE is_expunged = true
     * </code>
     *
     * @param     boolean|string $isExpunged The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildExpertQuestionStateQuery The current query, for fluid interface
     */
    public function filterByIsExpunged($isExpunged = null, $comparison = null)
    {
        if (is_string($isExpunged)) {
            $isExpunged = in_array(strtolower($isExpunged), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(ExpertQuestionStateTableMap::COL_IS_EXPUNGED, $isExpunged, $comparison);
    }

    /**
     * Filter the query on the is_muted column
     *
     * Example usage:
     * <code>
     * $query->filterByIsMuted(true); // WHERE is_muted = true
     * $query->filterByIsMuted('yes'); // WHERE is_muted = true
     * </code>
     *
     * @param     boolean|string $isMuted The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildExpertQuestionStateQuery The current query, for fluid interface
     */
    public function filterByIsMuted($isMuted = null, $comparison = null)
    {
        if (is_string($isMuted)) {
            $isMuted = in_array(strtolower($isMuted), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(ExpertQuestionStateTableMap::COL_IS_MUTED, $isMuted, $comparison);
    }

    /**
     * Filter the query by a related \Experts object
     *
     * @param \Experts|ObjectCollection $experts The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildExpertQuestionStateQuery The current query, for fluid interface
     */
    public function filterByExperts($experts, $comparison = null)
    {
        if ($experts instanceof \Experts) {
            return $this
                ->addUsingAlias(ExpertQuestionStateTableMap::COL_USERNAME, $experts->getUsername(), $comparison);
        } elseif ($experts instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ExpertQuestionStateTableMap::COL_USERNAME, $experts->toKeyValue('PrimaryKey', 'Username'), $comparison);
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
     * @return $this|ChildExpertQuestionStateQuery The current query, for fluid interface
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
     * @return ChildExpertQuestionStateQuery The current query, for fluid interface
     */
    public function filterByQuestions($questions, $comparison = null)
    {
        if ($questions instanceof \Questions) {
            return $this
                ->addUsingAlias(ExpertQuestionStateTableMap::COL_QUESTION_ID, $questions->getId(), $comparison);
        } elseif ($questions instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ExpertQuestionStateTableMap::COL_QUESTION_ID, $questions->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildExpertQuestionStateQuery The current query, for fluid interface
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
     * @param   ChildExpertQuestionState $expertQuestionState Object to remove from the list of results
     *
     * @return $this|ChildExpertQuestionStateQuery The current query, for fluid interface
     */
    public function prune($expertQuestionState = null)
    {
        if ($expertQuestionState) {
            throw new LogicException('ExpertQuestionState object has no primary key');

        }

        return $this;
    }

    /**
     * Deletes all rows from the expert_question_state table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ExpertQuestionStateTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ExpertQuestionStateTableMap::clearInstancePool();
            ExpertQuestionStateTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ExpertQuestionStateTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ExpertQuestionStateTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ExpertQuestionStateTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ExpertQuestionStateTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // ExpertQuestionStateQuery

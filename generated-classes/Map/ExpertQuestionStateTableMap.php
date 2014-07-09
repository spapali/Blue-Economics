<?php

namespace Map;

use \ExpertQuestionState;
use \ExpertQuestionStateQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'expert_question_state' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class ExpertQuestionStateTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.ExpertQuestionStateTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'blueecon_faq';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'expert_question_state';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\ExpertQuestionState';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'ExpertQuestionState';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 6;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 6;

    /**
     * the column name for the USERNAME field
     */
    const COL_USERNAME = 'expert_question_state.USERNAME';

    /**
     * the column name for the QUESTION_ID field
     */
    const COL_QUESTION_ID = 'expert_question_state.QUESTION_ID';

    /**
     * the column name for the IS_READ field
     */
    const COL_IS_READ = 'expert_question_state.IS_READ';

    /**
     * the column name for the IS_RESPONDED field
     */
    const COL_IS_RESPONDED = 'expert_question_state.IS_RESPONDED';

    /**
     * the column name for the IS_EXPUNGED field
     */
    const COL_IS_EXPUNGED = 'expert_question_state.IS_EXPUNGED';

    /**
     * the column name for the IS_MUTED field
     */
    const COL_IS_MUTED = 'expert_question_state.IS_MUTED';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Username', 'QuestionId', 'IsRead', 'IsResponded', 'IsExpunged', 'IsMuted', ),
        self::TYPE_STUDLYPHPNAME => array('username', 'questionId', 'isRead', 'isResponded', 'isExpunged', 'isMuted', ),
        self::TYPE_COLNAME       => array(ExpertQuestionStateTableMap::COL_USERNAME, ExpertQuestionStateTableMap::COL_QUESTION_ID, ExpertQuestionStateTableMap::COL_IS_READ, ExpertQuestionStateTableMap::COL_IS_RESPONDED, ExpertQuestionStateTableMap::COL_IS_EXPUNGED, ExpertQuestionStateTableMap::COL_IS_MUTED, ),
        self::TYPE_RAW_COLNAME   => array('COL_USERNAME', 'COL_QUESTION_ID', 'COL_IS_READ', 'COL_IS_RESPONDED', 'COL_IS_EXPUNGED', 'COL_IS_MUTED', ),
        self::TYPE_FIELDNAME     => array('username', 'question_id', 'is_read', 'is_responded', 'is_expunged', 'is_muted', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Username' => 0, 'QuestionId' => 1, 'IsRead' => 2, 'IsResponded' => 3, 'IsExpunged' => 4, 'IsMuted' => 5, ),
        self::TYPE_STUDLYPHPNAME => array('username' => 0, 'questionId' => 1, 'isRead' => 2, 'isResponded' => 3, 'isExpunged' => 4, 'isMuted' => 5, ),
        self::TYPE_COLNAME       => array(ExpertQuestionStateTableMap::COL_USERNAME => 0, ExpertQuestionStateTableMap::COL_QUESTION_ID => 1, ExpertQuestionStateTableMap::COL_IS_READ => 2, ExpertQuestionStateTableMap::COL_IS_RESPONDED => 3, ExpertQuestionStateTableMap::COL_IS_EXPUNGED => 4, ExpertQuestionStateTableMap::COL_IS_MUTED => 5, ),
        self::TYPE_RAW_COLNAME   => array('COL_USERNAME' => 0, 'COL_QUESTION_ID' => 1, 'COL_IS_READ' => 2, 'COL_IS_RESPONDED' => 3, 'COL_IS_EXPUNGED' => 4, 'COL_IS_MUTED' => 5, ),
        self::TYPE_FIELDNAME     => array('username' => 0, 'question_id' => 1, 'is_read' => 2, 'is_responded' => 3, 'is_expunged' => 4, 'is_muted' => 5, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('expert_question_state');
        $this->setPhpName('ExpertQuestionState');
        $this->setClassName('\\ExpertQuestionState');
        $this->setPackage('');
        $this->setUseIdGenerator(false);
        // columns
        $this->addForeignKey('USERNAME', 'Username', 'VARCHAR', 'experts', 'USERNAME', true, 45, null);
        $this->addForeignKey('QUESTION_ID', 'QuestionId', 'BIGINT', 'questions', 'ID', true, null, null);
        $this->addColumn('IS_READ', 'IsRead', 'BOOLEAN', false, 1, false);
        $this->addColumn('IS_RESPONDED', 'IsResponded', 'BOOLEAN', false, 1, false);
        $this->addColumn('IS_EXPUNGED', 'IsExpunged', 'BOOLEAN', false, 1, false);
        $this->addColumn('IS_MUTED', 'IsMuted', 'BOOLEAN', false, 1, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Experts', '\\Experts', RelationMap::MANY_TO_ONE, array('username' => 'username', ), null, null);
        $this->addRelation('Questions', '\\Questions', RelationMap::MANY_TO_ONE, array('question_id' => 'id', ), null, null);
    } // buildRelations()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        return null;
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        return '';
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? ExpertQuestionStateTableMap::CLASS_DEFAULT : ExpertQuestionStateTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (ExpertQuestionState object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = ExpertQuestionStateTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = ExpertQuestionStateTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + ExpertQuestionStateTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = ExpertQuestionStateTableMap::OM_CLASS;
            /** @var ExpertQuestionState $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            ExpertQuestionStateTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = ExpertQuestionStateTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = ExpertQuestionStateTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var ExpertQuestionState $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                ExpertQuestionStateTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(ExpertQuestionStateTableMap::COL_USERNAME);
            $criteria->addSelectColumn(ExpertQuestionStateTableMap::COL_QUESTION_ID);
            $criteria->addSelectColumn(ExpertQuestionStateTableMap::COL_IS_READ);
            $criteria->addSelectColumn(ExpertQuestionStateTableMap::COL_IS_RESPONDED);
            $criteria->addSelectColumn(ExpertQuestionStateTableMap::COL_IS_EXPUNGED);
            $criteria->addSelectColumn(ExpertQuestionStateTableMap::COL_IS_MUTED);
        } else {
            $criteria->addSelectColumn($alias . '.USERNAME');
            $criteria->addSelectColumn($alias . '.QUESTION_ID');
            $criteria->addSelectColumn($alias . '.IS_READ');
            $criteria->addSelectColumn($alias . '.IS_RESPONDED');
            $criteria->addSelectColumn($alias . '.IS_EXPUNGED');
            $criteria->addSelectColumn($alias . '.IS_MUTED');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(ExpertQuestionStateTableMap::DATABASE_NAME)->getTable(ExpertQuestionStateTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(ExpertQuestionStateTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(ExpertQuestionStateTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new ExpertQuestionStateTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a ExpertQuestionState or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ExpertQuestionState object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ExpertQuestionStateTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \ExpertQuestionState) { // it's a model object
            // create criteria based on pk value
            $criteria = $values->buildCriteria();
        } else { // it's a primary key, or an array of pks
            throw new LogicException('The ExpertQuestionState object has no primary key');
        }

        $query = ExpertQuestionStateQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            ExpertQuestionStateTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                ExpertQuestionStateTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the expert_question_state table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return ExpertQuestionStateQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a ExpertQuestionState or Criteria object.
     *
     * @param mixed               $criteria Criteria or ExpertQuestionState object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ExpertQuestionStateTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from ExpertQuestionState object
        }


        // Set the correct dbName
        $query = ExpertQuestionStateQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // ExpertQuestionStateTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
ExpertQuestionStateTableMap::buildTableMap();

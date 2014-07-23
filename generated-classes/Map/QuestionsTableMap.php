<?php

namespace Map;

use \Questions;
use \QuestionsQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'questions' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class QuestionsTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.QuestionsTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'blueecon_faq';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'questions';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Questions';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Questions';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 5;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 5;

    /**
     * the column name for the ID field
     */
    const COL_ID = 'questions.ID';

    /**
     * the column name for the SUBMITTER field
     */
    const COL_SUBMITTER = 'questions.SUBMITTER';

    /**
     * the column name for the QUESTION field
     */
    const COL_QUESTION = 'questions.QUESTION';

    /**
     * the column name for the CREATED field
     */
    const COL_CREATED = 'questions.CREATED';

    /**
     * the column name for the SOC_CODE field
     */
    const COL_SOC_CODE = 'questions.SOC_CODE';

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
        self::TYPE_PHPNAME       => array('Id', 'Submitter', 'Question', 'Created', 'SocCode', ),
        self::TYPE_STUDLYPHPNAME => array('id', 'submitter', 'question', 'created', 'socCode', ),
        self::TYPE_COLNAME       => array(QuestionsTableMap::COL_ID, QuestionsTableMap::COL_SUBMITTER, QuestionsTableMap::COL_QUESTION, QuestionsTableMap::COL_CREATED, QuestionsTableMap::COL_SOC_CODE, ),
        self::TYPE_RAW_COLNAME   => array('COL_ID', 'COL_SUBMITTER', 'COL_QUESTION', 'COL_CREATED', 'COL_SOC_CODE', ),
        self::TYPE_FIELDNAME     => array('id', 'submitter', 'question', 'created', 'soc_code', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Submitter' => 1, 'Question' => 2, 'Created' => 3, 'SocCode' => 4, ),
        self::TYPE_STUDLYPHPNAME => array('id' => 0, 'submitter' => 1, 'question' => 2, 'created' => 3, 'socCode' => 4, ),
        self::TYPE_COLNAME       => array(QuestionsTableMap::COL_ID => 0, QuestionsTableMap::COL_SUBMITTER => 1, QuestionsTableMap::COL_QUESTION => 2, QuestionsTableMap::COL_CREATED => 3, QuestionsTableMap::COL_SOC_CODE => 4, ),
        self::TYPE_RAW_COLNAME   => array('COL_ID' => 0, 'COL_SUBMITTER' => 1, 'COL_QUESTION' => 2, 'COL_CREATED' => 3, 'COL_SOC_CODE' => 4, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'submitter' => 1, 'question' => 2, 'created' => 3, 'soc_code' => 4, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, )
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
        $this->setName('questions');
        $this->setPhpName('Questions');
        $this->setClassName('\\Questions');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'BIGINT', true, null, null);
        $this->addColumn('SUBMITTER', 'Submitter', 'VARCHAR', true, 45, null);
        $this->addColumn('QUESTION', 'Question', 'VARCHAR', true, 255, null);
        $this->addColumn('CREATED', 'Created', 'TIMESTAMP', true, null, 'CURRENT_TIMESTAMP');
        $this->addColumn('SOC_CODE', 'SocCode', 'VARCHAR', false, 7, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('ExpertQuestionState', '\\ExpertQuestionState', RelationMap::ONE_TO_MANY, array('id' => 'question_id', ), null, null, 'ExpertQuestionStates');
        $this->addRelation('Responses', '\\Responses', RelationMap::ONE_TO_MANY, array('id' => 'question_id', ), null, null, 'Responsess');
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
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
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
        return (string) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
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
        return $withPrefix ? QuestionsTableMap::CLASS_DEFAULT : QuestionsTableMap::OM_CLASS;
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
     * @return array           (Questions object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = QuestionsTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = QuestionsTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + QuestionsTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = QuestionsTableMap::OM_CLASS;
            /** @var Questions $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            QuestionsTableMap::addInstanceToPool($obj, $key);
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
            $key = QuestionsTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = QuestionsTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Questions $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                QuestionsTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(QuestionsTableMap::COL_ID);
            $criteria->addSelectColumn(QuestionsTableMap::COL_SUBMITTER);
            $criteria->addSelectColumn(QuestionsTableMap::COL_QUESTION);
            $criteria->addSelectColumn(QuestionsTableMap::COL_CREATED);
            $criteria->addSelectColumn(QuestionsTableMap::COL_SOC_CODE);
        } else {
            $criteria->addSelectColumn($alias . '.ID');
            $criteria->addSelectColumn($alias . '.SUBMITTER');
            $criteria->addSelectColumn($alias . '.QUESTION');
            $criteria->addSelectColumn($alias . '.CREATED');
            $criteria->addSelectColumn($alias . '.SOC_CODE');
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
        return Propel::getServiceContainer()->getDatabaseMap(QuestionsTableMap::DATABASE_NAME)->getTable(QuestionsTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(QuestionsTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(QuestionsTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new QuestionsTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Questions or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Questions object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(QuestionsTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Questions) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(QuestionsTableMap::DATABASE_NAME);
            $criteria->add(QuestionsTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = QuestionsQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            QuestionsTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                QuestionsTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the questions table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return QuestionsQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Questions or Criteria object.
     *
     * @param mixed               $criteria Criteria or Questions object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(QuestionsTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Questions object
        }

        if ($criteria->containsKey(QuestionsTableMap::COL_ID) && $criteria->keyContainsValue(QuestionsTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.QuestionsTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = QuestionsQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // QuestionsTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
QuestionsTableMap::buildTableMap();

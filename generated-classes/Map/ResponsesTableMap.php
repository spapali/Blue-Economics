<?php

namespace Map;

use \Responses;
use \ResponsesQuery;
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
 * This class defines the structure of the 'responses' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class ResponsesTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.ResponsesTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'blueecon_faq';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'responses';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Responses';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Responses';

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
     * the column name for the ID field
     */
    const COL_ID = 'responses.ID';

    /**
     * the column name for the EXPERT field
     */
    const COL_EXPERT = 'responses.EXPERT';

    /**
     * the column name for the QUESTION_ID field
     */
    const COL_QUESTION_ID = 'responses.QUESTION_ID';

    /**
     * the column name for the CREATED field
     */
    const COL_CREATED = 'responses.CREATED';

    /**
     * the column name for the RESPONSE field
     */
    const COL_RESPONSE = 'responses.RESPONSE';

    /**
     * the column name for the VOTES field
     */
    const COL_VOTES = 'responses.VOTES';

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
        self::TYPE_PHPNAME       => array('Id', 'Expert', 'QuestionId', 'Created', 'Response', 'Votes', ),
        self::TYPE_STUDLYPHPNAME => array('id', 'expert', 'questionId', 'created', 'response', 'votes', ),
        self::TYPE_COLNAME       => array(ResponsesTableMap::COL_ID, ResponsesTableMap::COL_EXPERT, ResponsesTableMap::COL_QUESTION_ID, ResponsesTableMap::COL_CREATED, ResponsesTableMap::COL_RESPONSE, ResponsesTableMap::COL_VOTES, ),
        self::TYPE_RAW_COLNAME   => array('COL_ID', 'COL_EXPERT', 'COL_QUESTION_ID', 'COL_CREATED', 'COL_RESPONSE', 'COL_VOTES', ),
        self::TYPE_FIELDNAME     => array('id', 'expert', 'question_id', 'created', 'response', 'votes', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Expert' => 1, 'QuestionId' => 2, 'Created' => 3, 'Response' => 4, 'Votes' => 5, ),
        self::TYPE_STUDLYPHPNAME => array('id' => 0, 'expert' => 1, 'questionId' => 2, 'created' => 3, 'response' => 4, 'votes' => 5, ),
        self::TYPE_COLNAME       => array(ResponsesTableMap::COL_ID => 0, ResponsesTableMap::COL_EXPERT => 1, ResponsesTableMap::COL_QUESTION_ID => 2, ResponsesTableMap::COL_CREATED => 3, ResponsesTableMap::COL_RESPONSE => 4, ResponsesTableMap::COL_VOTES => 5, ),
        self::TYPE_RAW_COLNAME   => array('COL_ID' => 0, 'COL_EXPERT' => 1, 'COL_QUESTION_ID' => 2, 'COL_CREATED' => 3, 'COL_RESPONSE' => 4, 'COL_VOTES' => 5, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'expert' => 1, 'question_id' => 2, 'created' => 3, 'response' => 4, 'votes' => 5, ),
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
        $this->setName('responses');
        $this->setPhpName('Responses');
        $this->setClassName('\\Responses');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'BIGINT', true, null, null);
        $this->addForeignKey('EXPERT', 'Expert', 'VARCHAR', 'experts', 'USERNAME', true, 45, null);
        $this->addForeignKey('QUESTION_ID', 'QuestionId', 'BIGINT', 'questions', 'ID', true, null, null);
        $this->addColumn('CREATED', 'Created', 'TIMESTAMP', true, null, 'CURRENT_TIMESTAMP');
        $this->addColumn('RESPONSE', 'Response', 'VARCHAR', true, 255, null);
        $this->addColumn('VOTES', 'Votes', 'INTEGER', false, null, 0);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Experts', '\\Experts', RelationMap::MANY_TO_ONE, array('expert' => 'username', ), null, null);
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
        return $withPrefix ? ResponsesTableMap::CLASS_DEFAULT : ResponsesTableMap::OM_CLASS;
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
     * @return array           (Responses object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = ResponsesTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = ResponsesTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + ResponsesTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = ResponsesTableMap::OM_CLASS;
            /** @var Responses $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            ResponsesTableMap::addInstanceToPool($obj, $key);
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
            $key = ResponsesTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = ResponsesTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Responses $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                ResponsesTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(ResponsesTableMap::COL_ID);
            $criteria->addSelectColumn(ResponsesTableMap::COL_EXPERT);
            $criteria->addSelectColumn(ResponsesTableMap::COL_QUESTION_ID);
            $criteria->addSelectColumn(ResponsesTableMap::COL_CREATED);
            $criteria->addSelectColumn(ResponsesTableMap::COL_RESPONSE);
            $criteria->addSelectColumn(ResponsesTableMap::COL_VOTES);
        } else {
            $criteria->addSelectColumn($alias . '.ID');
            $criteria->addSelectColumn($alias . '.EXPERT');
            $criteria->addSelectColumn($alias . '.QUESTION_ID');
            $criteria->addSelectColumn($alias . '.CREATED');
            $criteria->addSelectColumn($alias . '.RESPONSE');
            $criteria->addSelectColumn($alias . '.VOTES');
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
        return Propel::getServiceContainer()->getDatabaseMap(ResponsesTableMap::DATABASE_NAME)->getTable(ResponsesTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(ResponsesTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(ResponsesTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new ResponsesTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Responses or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Responses object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(ResponsesTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Responses) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(ResponsesTableMap::DATABASE_NAME);
            $criteria->add(ResponsesTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = ResponsesQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            ResponsesTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                ResponsesTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the responses table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return ResponsesQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Responses or Criteria object.
     *
     * @param mixed               $criteria Criteria or Responses object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ResponsesTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Responses object
        }

        if ($criteria->containsKey(ResponsesTableMap::COL_ID) && $criteria->keyContainsValue(ResponsesTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.ResponsesTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = ResponsesQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // ResponsesTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
ResponsesTableMap::buildTableMap();

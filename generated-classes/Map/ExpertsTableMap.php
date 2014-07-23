<?php

namespace Map;

use \Experts;
use \ExpertsQuery;
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
 * This class defines the structure of the 'experts' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class ExpertsTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.ExpertsTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'blueecon_faq';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'experts';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Experts';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Experts';

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
    const COL_USERNAME = 'experts.USERNAME';

    /**
     * the column name for the FIRST_NAME field
     */
    const COL_FIRST_NAME = 'experts.FIRST_NAME';

    /**
     * the column name for the LAST_NAME field
     */
    const COL_LAST_NAME = 'experts.LAST_NAME';

    /**
     * the column name for the BIO field
     */
    const COL_BIO = 'experts.BIO';

    /**
     * the column name for the ORGANIZATION field
     */
    const COL_ORGANIZATION = 'experts.ORGANIZATION';

    /**
     * the column name for the PASSWORD field
     */
    const COL_PASSWORD = 'experts.PASSWORD';

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
        self::TYPE_PHPNAME       => array('Username', 'FirstName', 'LastName', 'Bio', 'Organization', 'Password', ),
        self::TYPE_STUDLYPHPNAME => array('username', 'firstName', 'lastName', 'bio', 'organization', 'password', ),
        self::TYPE_COLNAME       => array(ExpertsTableMap::COL_USERNAME, ExpertsTableMap::COL_FIRST_NAME, ExpertsTableMap::COL_LAST_NAME, ExpertsTableMap::COL_BIO, ExpertsTableMap::COL_ORGANIZATION, ExpertsTableMap::COL_PASSWORD, ),
        self::TYPE_RAW_COLNAME   => array('COL_USERNAME', 'COL_FIRST_NAME', 'COL_LAST_NAME', 'COL_BIO', 'COL_ORGANIZATION', 'COL_PASSWORD', ),
        self::TYPE_FIELDNAME     => array('username', 'first_name', 'last_name', 'bio', 'organization', 'password', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Username' => 0, 'FirstName' => 1, 'LastName' => 2, 'Bio' => 3, 'Organization' => 4, 'Password' => 5, ),
        self::TYPE_STUDLYPHPNAME => array('username' => 0, 'firstName' => 1, 'lastName' => 2, 'bio' => 3, 'organization' => 4, 'password' => 5, ),
        self::TYPE_COLNAME       => array(ExpertsTableMap::COL_USERNAME => 0, ExpertsTableMap::COL_FIRST_NAME => 1, ExpertsTableMap::COL_LAST_NAME => 2, ExpertsTableMap::COL_BIO => 3, ExpertsTableMap::COL_ORGANIZATION => 4, ExpertsTableMap::COL_PASSWORD => 5, ),
        self::TYPE_RAW_COLNAME   => array('COL_USERNAME' => 0, 'COL_FIRST_NAME' => 1, 'COL_LAST_NAME' => 2, 'COL_BIO' => 3, 'COL_ORGANIZATION' => 4, 'COL_PASSWORD' => 5, ),
        self::TYPE_FIELDNAME     => array('username' => 0, 'first_name' => 1, 'last_name' => 2, 'bio' => 3, 'organization' => 4, 'password' => 5, ),
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
        $this->setName('experts');
        $this->setPhpName('Experts');
        $this->setClassName('\\Experts');
        $this->setPackage('');
        $this->setUseIdGenerator(false);
        // columns
        $this->addPrimaryKey('USERNAME', 'Username', 'VARCHAR', true, 45, null);
        $this->addColumn('FIRST_NAME', 'FirstName', 'VARCHAR', true, 45, null);
        $this->addColumn('LAST_NAME', 'LastName', 'VARCHAR', false, 45, null);
        $this->addColumn('BIO', 'Bio', 'VARCHAR', false, 255, null);
        $this->addColumn('ORGANIZATION', 'Organization', 'VARCHAR', false, 45, null);
        $this->addColumn('PASSWORD', 'Password', 'VARCHAR', true, 255, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('ExpertGroupMembers', '\\ExpertGroupMembers', RelationMap::ONE_TO_MANY, array('username' => 'expert', ), null, null, 'ExpertGroupMemberss');
        $this->addRelation('ExpertQuestionState', '\\ExpertQuestionState', RelationMap::ONE_TO_MANY, array('username' => 'username', ), null, null, 'ExpertQuestionStates');
        $this->addRelation('Responses', '\\Responses', RelationMap::ONE_TO_MANY, array('username' => 'expert', ), null, null, 'Responsess');
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Username', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Username', TableMap::TYPE_PHPNAME, $indexType)];
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
                : self::translateFieldName('Username', TableMap::TYPE_PHPNAME, $indexType)
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
        return $withPrefix ? ExpertsTableMap::CLASS_DEFAULT : ExpertsTableMap::OM_CLASS;
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
     * @return array           (Experts object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = ExpertsTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = ExpertsTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + ExpertsTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = ExpertsTableMap::OM_CLASS;
            /** @var Experts $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            ExpertsTableMap::addInstanceToPool($obj, $key);
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
            $key = ExpertsTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = ExpertsTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Experts $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                ExpertsTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(ExpertsTableMap::COL_USERNAME);
            $criteria->addSelectColumn(ExpertsTableMap::COL_FIRST_NAME);
            $criteria->addSelectColumn(ExpertsTableMap::COL_LAST_NAME);
            $criteria->addSelectColumn(ExpertsTableMap::COL_BIO);
            $criteria->addSelectColumn(ExpertsTableMap::COL_ORGANIZATION);
            $criteria->addSelectColumn(ExpertsTableMap::COL_PASSWORD);
        } else {
            $criteria->addSelectColumn($alias . '.USERNAME');
            $criteria->addSelectColumn($alias . '.FIRST_NAME');
            $criteria->addSelectColumn($alias . '.LAST_NAME');
            $criteria->addSelectColumn($alias . '.BIO');
            $criteria->addSelectColumn($alias . '.ORGANIZATION');
            $criteria->addSelectColumn($alias . '.PASSWORD');
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
        return Propel::getServiceContainer()->getDatabaseMap(ExpertsTableMap::DATABASE_NAME)->getTable(ExpertsTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(ExpertsTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(ExpertsTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new ExpertsTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Experts or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Experts object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(ExpertsTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Experts) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(ExpertsTableMap::DATABASE_NAME);
            $criteria->add(ExpertsTableMap::COL_USERNAME, (array) $values, Criteria::IN);
        }

        $query = ExpertsQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            ExpertsTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                ExpertsTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the experts table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return ExpertsQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Experts or Criteria object.
     *
     * @param mixed               $criteria Criteria or Experts object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ExpertsTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Experts object
        }


        // Set the correct dbName
        $query = ExpertsQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // ExpertsTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
ExpertsTableMap::buildTableMap();

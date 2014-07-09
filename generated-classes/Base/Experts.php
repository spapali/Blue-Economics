<?php

namespace Base;

use \ExpertGroupMembers as ChildExpertGroupMembers;
use \ExpertGroupMembersQuery as ChildExpertGroupMembersQuery;
use \ExpertQuestionState as ChildExpertQuestionState;
use \ExpertQuestionStateQuery as ChildExpertQuestionStateQuery;
use \Experts as ChildExperts;
use \ExpertsQuery as ChildExpertsQuery;
use \Responses as ChildResponses;
use \ResponsesQuery as ChildResponsesQuery;
use \Exception;
use \PDO;
use Map\ExpertsTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;

abstract class Experts implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\ExpertsTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the username field.
     * @var        string
     */
    protected $username;

    /**
     * The value for the first_name field.
     * @var        string
     */
    protected $first_name;

    /**
     * The value for the last_name field.
     * @var        string
     */
    protected $last_name;

    /**
     * The value for the bio field.
     * @var        string
     */
    protected $bio;

    /**
     * The value for the organization field.
     * @var        string
     */
    protected $organization;

    /**
     * The value for the password field.
     * @var        string
     */
    protected $password;

    /**
     * @var        ObjectCollection|ChildExpertGroupMembers[] Collection to store aggregation of ChildExpertGroupMembers objects.
     */
    protected $collExpertGroupMemberss;
    protected $collExpertGroupMemberssPartial;

    /**
     * @var        ObjectCollection|ChildExpertQuestionState[] Collection to store aggregation of ChildExpertQuestionState objects.
     */
    protected $collExpertQuestionStates;
    protected $collExpertQuestionStatesPartial;

    /**
     * @var        ObjectCollection|ChildResponses[] Collection to store aggregation of ChildResponses objects.
     */
    protected $collResponsess;
    protected $collResponsessPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildExpertGroupMembers[]
     */
    protected $expertGroupMemberssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildExpertQuestionState[]
     */
    protected $expertQuestionStatesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildResponses[]
     */
    protected $responsessScheduledForDeletion = null;

    /**
     * Initializes internal state of Base\Experts object.
     */
    public function __construct()
    {
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>Experts</code> instance.  If
     * <code>obj</code> is an instance of <code>Experts</code>, delegates to
     * <code>equals(Experts)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this|Experts The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        return array_keys(get_object_vars($this));
    }

    /**
     * Get the [username] column value.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get the [first_name] column value.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Get the [last_name] column value.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Get the [bio] column value.
     *
     * @return string
     */
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * Get the [organization] column value.
     *
     * @return string
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * Get the [password] column value.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : ExpertsTableMap::translateFieldName('Username', TableMap::TYPE_PHPNAME, $indexType)];
            $this->username = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : ExpertsTableMap::translateFieldName('FirstName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->first_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : ExpertsTableMap::translateFieldName('LastName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->last_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : ExpertsTableMap::translateFieldName('Bio', TableMap::TYPE_PHPNAME, $indexType)];
            $this->bio = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : ExpertsTableMap::translateFieldName('Organization', TableMap::TYPE_PHPNAME, $indexType)];
            $this->organization = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : ExpertsTableMap::translateFieldName('Password', TableMap::TYPE_PHPNAME, $indexType)];
            $this->password = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 6; // 6 = ExpertsTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Experts'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
    } // ensureConsistency

    /**
     * Set the value of [username] column.
     *
     * @param  string $v new value
     * @return $this|\Experts The current object (for fluent API support)
     */
    public function setUsername($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->username !== $v) {
            $this->username = $v;
            $this->modifiedColumns[ExpertsTableMap::COL_USERNAME] = true;
        }

        return $this;
    } // setUsername()

    /**
     * Set the value of [first_name] column.
     *
     * @param  string $v new value
     * @return $this|\Experts The current object (for fluent API support)
     */
    public function setFirstName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->first_name !== $v) {
            $this->first_name = $v;
            $this->modifiedColumns[ExpertsTableMap::COL_FIRST_NAME] = true;
        }

        return $this;
    } // setFirstName()

    /**
     * Set the value of [last_name] column.
     *
     * @param  string $v new value
     * @return $this|\Experts The current object (for fluent API support)
     */
    public function setLastName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->last_name !== $v) {
            $this->last_name = $v;
            $this->modifiedColumns[ExpertsTableMap::COL_LAST_NAME] = true;
        }

        return $this;
    } // setLastName()

    /**
     * Set the value of [bio] column.
     *
     * @param  string $v new value
     * @return $this|\Experts The current object (for fluent API support)
     */
    public function setBio($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->bio !== $v) {
            $this->bio = $v;
            $this->modifiedColumns[ExpertsTableMap::COL_BIO] = true;
        }

        return $this;
    } // setBio()

    /**
     * Set the value of [organization] column.
     *
     * @param  string $v new value
     * @return $this|\Experts The current object (for fluent API support)
     */
    public function setOrganization($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->organization !== $v) {
            $this->organization = $v;
            $this->modifiedColumns[ExpertsTableMap::COL_ORGANIZATION] = true;
        }

        return $this;
    } // setOrganization()

    /**
     * Set the value of [password] column.
     *
     * @param  string $v new value
     * @return $this|\Experts The current object (for fluent API support)
     */
    public function setPassword($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->password !== $v) {
            $this->password = $v;
            $this->modifiedColumns[ExpertsTableMap::COL_PASSWORD] = true;
        }

        return $this;
    } // setPassword()

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ExpertsTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildExpertsQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collExpertGroupMemberss = null;

            $this->collExpertQuestionStates = null;

            $this->collResponsess = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Experts::setDeleted()
     * @see Experts::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(ExpertsTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildExpertsQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(ExpertsTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $isInsert = $this->isNew();
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                ExpertsTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->expertGroupMemberssScheduledForDeletion !== null) {
                if (!$this->expertGroupMemberssScheduledForDeletion->isEmpty()) {
                    \ExpertGroupMembersQuery::create()
                        ->filterByPrimaryKeys($this->expertGroupMemberssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->expertGroupMemberssScheduledForDeletion = null;
                }
            }

            if ($this->collExpertGroupMemberss !== null) {
                foreach ($this->collExpertGroupMemberss as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->expertQuestionStatesScheduledForDeletion !== null) {
                if (!$this->expertQuestionStatesScheduledForDeletion->isEmpty()) {
                    \ExpertQuestionStateQuery::create()
                        ->filterByPrimaryKeys($this->expertQuestionStatesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->expertQuestionStatesScheduledForDeletion = null;
                }
            }

            if ($this->collExpertQuestionStates !== null) {
                foreach ($this->collExpertQuestionStates as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->responsessScheduledForDeletion !== null) {
                if (!$this->responsessScheduledForDeletion->isEmpty()) {
                    \ResponsesQuery::create()
                        ->filterByPrimaryKeys($this->responsessScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->responsessScheduledForDeletion = null;
                }
            }

            if ($this->collResponsess !== null) {
                foreach ($this->collResponsess as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(ExpertsTableMap::COL_USERNAME)) {
            $modifiedColumns[':p' . $index++]  = 'USERNAME';
        }
        if ($this->isColumnModified(ExpertsTableMap::COL_FIRST_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'FIRST_NAME';
        }
        if ($this->isColumnModified(ExpertsTableMap::COL_LAST_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'LAST_NAME';
        }
        if ($this->isColumnModified(ExpertsTableMap::COL_BIO)) {
            $modifiedColumns[':p' . $index++]  = 'BIO';
        }
        if ($this->isColumnModified(ExpertsTableMap::COL_ORGANIZATION)) {
            $modifiedColumns[':p' . $index++]  = 'ORGANIZATION';
        }
        if ($this->isColumnModified(ExpertsTableMap::COL_PASSWORD)) {
            $modifiedColumns[':p' . $index++]  = 'PASSWORD';
        }

        $sql = sprintf(
            'INSERT INTO experts (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'USERNAME':
                        $stmt->bindValue($identifier, $this->username, PDO::PARAM_STR);
                        break;
                    case 'FIRST_NAME':
                        $stmt->bindValue($identifier, $this->first_name, PDO::PARAM_STR);
                        break;
                    case 'LAST_NAME':
                        $stmt->bindValue($identifier, $this->last_name, PDO::PARAM_STR);
                        break;
                    case 'BIO':
                        $stmt->bindValue($identifier, $this->bio, PDO::PARAM_STR);
                        break;
                    case 'ORGANIZATION':
                        $stmt->bindValue($identifier, $this->organization, PDO::PARAM_STR);
                        break;
                    case 'PASSWORD':
                        $stmt->bindValue($identifier, $this->password, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = ExpertsTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getUsername();
                break;
            case 1:
                return $this->getFirstName();
                break;
            case 2:
                return $this->getLastName();
                break;
            case 3:
                return $this->getBio();
                break;
            case 4:
                return $this->getOrganization();
                break;
            case 5:
                return $this->getPassword();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['Experts'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Experts'][$this->getPrimaryKey()] = true;
        $keys = ExpertsTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getUsername(),
            $keys[1] => $this->getFirstName(),
            $keys[2] => $this->getLastName(),
            $keys[3] => $this->getBio(),
            $keys[4] => $this->getOrganization(),
            $keys[5] => $this->getPassword(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collExpertGroupMemberss) {
                $result['ExpertGroupMemberss'] = $this->collExpertGroupMemberss->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collExpertQuestionStates) {
                $result['ExpertQuestionStates'] = $this->collExpertQuestionStates->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collResponsess) {
                $result['Responsess'] = $this->collResponsess->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\Experts
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = ExpertsTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Experts
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setUsername($value);
                break;
            case 1:
                $this->setFirstName($value);
                break;
            case 2:
                $this->setLastName($value);
                break;
            case 3:
                $this->setBio($value);
                break;
            case 4:
                $this->setOrganization($value);
                break;
            case 5:
                $this->setPassword($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = ExpertsTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setUsername($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setFirstName($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setLastName($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setBio($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setOrganization($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setPassword($arr[$keys[5]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     *
     * @return $this|\Experts The current object, for fluid interface
     */
    public function importFrom($parser, $data)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), TableMap::TYPE_PHPNAME);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(ExpertsTableMap::DATABASE_NAME);

        if ($this->isColumnModified(ExpertsTableMap::COL_USERNAME)) {
            $criteria->add(ExpertsTableMap::COL_USERNAME, $this->username);
        }
        if ($this->isColumnModified(ExpertsTableMap::COL_FIRST_NAME)) {
            $criteria->add(ExpertsTableMap::COL_FIRST_NAME, $this->first_name);
        }
        if ($this->isColumnModified(ExpertsTableMap::COL_LAST_NAME)) {
            $criteria->add(ExpertsTableMap::COL_LAST_NAME, $this->last_name);
        }
        if ($this->isColumnModified(ExpertsTableMap::COL_BIO)) {
            $criteria->add(ExpertsTableMap::COL_BIO, $this->bio);
        }
        if ($this->isColumnModified(ExpertsTableMap::COL_ORGANIZATION)) {
            $criteria->add(ExpertsTableMap::COL_ORGANIZATION, $this->organization);
        }
        if ($this->isColumnModified(ExpertsTableMap::COL_PASSWORD)) {
            $criteria->add(ExpertsTableMap::COL_PASSWORD, $this->password);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(ExpertsTableMap::DATABASE_NAME);
        $criteria->add(ExpertsTableMap::COL_USERNAME, $this->username);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getUsername();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     * @return string
     */
    public function getPrimaryKey()
    {
        return $this->getUsername();
    }

    /**
     * Generic method to set the primary key (username column).
     *
     * @param       string $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setUsername($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getUsername();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Experts (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUsername($this->getUsername());
        $copyObj->setFirstName($this->getFirstName());
        $copyObj->setLastName($this->getLastName());
        $copyObj->setBio($this->getBio());
        $copyObj->setOrganization($this->getOrganization());
        $copyObj->setPassword($this->getPassword());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getExpertGroupMemberss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addExpertGroupMembers($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getExpertQuestionStates() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addExpertQuestionState($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getResponsess() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addResponses($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \Experts Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('ExpertGroupMembers' == $relationName) {
            return $this->initExpertGroupMemberss();
        }
        if ('ExpertQuestionState' == $relationName) {
            return $this->initExpertQuestionStates();
        }
        if ('Responses' == $relationName) {
            return $this->initResponsess();
        }
    }

    /**
     * Clears out the collExpertGroupMemberss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addExpertGroupMemberss()
     */
    public function clearExpertGroupMemberss()
    {
        $this->collExpertGroupMemberss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collExpertGroupMemberss collection loaded partially.
     */
    public function resetPartialExpertGroupMemberss($v = true)
    {
        $this->collExpertGroupMemberssPartial = $v;
    }

    /**
     * Initializes the collExpertGroupMemberss collection.
     *
     * By default this just sets the collExpertGroupMemberss collection to an empty array (like clearcollExpertGroupMemberss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initExpertGroupMemberss($overrideExisting = true)
    {
        if (null !== $this->collExpertGroupMemberss && !$overrideExisting) {
            return;
        }
        $this->collExpertGroupMemberss = new ObjectCollection();
        $this->collExpertGroupMemberss->setModel('\ExpertGroupMembers');
    }

    /**
     * Gets an array of ChildExpertGroupMembers objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildExperts is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildExpertGroupMembers[] List of ChildExpertGroupMembers objects
     * @throws PropelException
     */
    public function getExpertGroupMemberss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collExpertGroupMemberssPartial && !$this->isNew();
        if (null === $this->collExpertGroupMemberss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collExpertGroupMemberss) {
                // return empty collection
                $this->initExpertGroupMemberss();
            } else {
                $collExpertGroupMemberss = ChildExpertGroupMembersQuery::create(null, $criteria)
                    ->filterByExperts($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collExpertGroupMemberssPartial && count($collExpertGroupMemberss)) {
                        $this->initExpertGroupMemberss(false);

                        foreach ($collExpertGroupMemberss as $obj) {
                            if (false == $this->collExpertGroupMemberss->contains($obj)) {
                                $this->collExpertGroupMemberss->append($obj);
                            }
                        }

                        $this->collExpertGroupMemberssPartial = true;
                    }

                    return $collExpertGroupMemberss;
                }

                if ($partial && $this->collExpertGroupMemberss) {
                    foreach ($this->collExpertGroupMemberss as $obj) {
                        if ($obj->isNew()) {
                            $collExpertGroupMemberss[] = $obj;
                        }
                    }
                }

                $this->collExpertGroupMemberss = $collExpertGroupMemberss;
                $this->collExpertGroupMemberssPartial = false;
            }
        }

        return $this->collExpertGroupMemberss;
    }

    /**
     * Sets a collection of ChildExpertGroupMembers objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $expertGroupMemberss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildExperts The current object (for fluent API support)
     */
    public function setExpertGroupMemberss(Collection $expertGroupMemberss, ConnectionInterface $con = null)
    {
        /** @var ChildExpertGroupMembers[] $expertGroupMemberssToDelete */
        $expertGroupMemberssToDelete = $this->getExpertGroupMemberss(new Criteria(), $con)->diff($expertGroupMemberss);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->expertGroupMemberssScheduledForDeletion = clone $expertGroupMemberssToDelete;

        foreach ($expertGroupMemberssToDelete as $expertGroupMembersRemoved) {
            $expertGroupMembersRemoved->setExperts(null);
        }

        $this->collExpertGroupMemberss = null;
        foreach ($expertGroupMemberss as $expertGroupMembers) {
            $this->addExpertGroupMembers($expertGroupMembers);
        }

        $this->collExpertGroupMemberss = $expertGroupMemberss;
        $this->collExpertGroupMemberssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ExpertGroupMembers objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related ExpertGroupMembers objects.
     * @throws PropelException
     */
    public function countExpertGroupMemberss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collExpertGroupMemberssPartial && !$this->isNew();
        if (null === $this->collExpertGroupMemberss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collExpertGroupMemberss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getExpertGroupMemberss());
            }

            $query = ChildExpertGroupMembersQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByExperts($this)
                ->count($con);
        }

        return count($this->collExpertGroupMemberss);
    }

    /**
     * Method called to associate a ChildExpertGroupMembers object to this object
     * through the ChildExpertGroupMembers foreign key attribute.
     *
     * @param  ChildExpertGroupMembers $l ChildExpertGroupMembers
     * @return $this|\Experts The current object (for fluent API support)
     */
    public function addExpertGroupMembers(ChildExpertGroupMembers $l)
    {
        if ($this->collExpertGroupMemberss === null) {
            $this->initExpertGroupMemberss();
            $this->collExpertGroupMemberssPartial = true;
        }

        if (!$this->collExpertGroupMemberss->contains($l)) {
            $this->doAddExpertGroupMembers($l);
        }

        return $this;
    }

    /**
     * @param ChildExpertGroupMembers $expertGroupMembers The ChildExpertGroupMembers object to add.
     */
    protected function doAddExpertGroupMembers(ChildExpertGroupMembers $expertGroupMembers)
    {
        $this->collExpertGroupMemberss[]= $expertGroupMembers;
        $expertGroupMembers->setExperts($this);
    }

    /**
     * @param  ChildExpertGroupMembers $expertGroupMembers The ChildExpertGroupMembers object to remove.
     * @return $this|ChildExperts The current object (for fluent API support)
     */
    public function removeExpertGroupMembers(ChildExpertGroupMembers $expertGroupMembers)
    {
        if ($this->getExpertGroupMemberss()->contains($expertGroupMembers)) {
            $pos = $this->collExpertGroupMemberss->search($expertGroupMembers);
            $this->collExpertGroupMemberss->remove($pos);
            if (null === $this->expertGroupMemberssScheduledForDeletion) {
                $this->expertGroupMemberssScheduledForDeletion = clone $this->collExpertGroupMemberss;
                $this->expertGroupMemberssScheduledForDeletion->clear();
            }
            $this->expertGroupMemberssScheduledForDeletion[]= clone $expertGroupMembers;
            $expertGroupMembers->setExperts(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Experts is new, it will return
     * an empty collection; or if this Experts has previously
     * been saved, it will retrieve related ExpertGroupMemberss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Experts.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildExpertGroupMembers[] List of ChildExpertGroupMembers objects
     */
    public function getExpertGroupMemberssJoinExpertGroup(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildExpertGroupMembersQuery::create(null, $criteria);
        $query->joinWith('ExpertGroup', $joinBehavior);

        return $this->getExpertGroupMemberss($query, $con);
    }

    /**
     * Clears out the collExpertQuestionStates collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addExpertQuestionStates()
     */
    public function clearExpertQuestionStates()
    {
        $this->collExpertQuestionStates = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collExpertQuestionStates collection loaded partially.
     */
    public function resetPartialExpertQuestionStates($v = true)
    {
        $this->collExpertQuestionStatesPartial = $v;
    }

    /**
     * Initializes the collExpertQuestionStates collection.
     *
     * By default this just sets the collExpertQuestionStates collection to an empty array (like clearcollExpertQuestionStates());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initExpertQuestionStates($overrideExisting = true)
    {
        if (null !== $this->collExpertQuestionStates && !$overrideExisting) {
            return;
        }
        $this->collExpertQuestionStates = new ObjectCollection();
        $this->collExpertQuestionStates->setModel('\ExpertQuestionState');
    }

    /**
     * Gets an array of ChildExpertQuestionState objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildExperts is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildExpertQuestionState[] List of ChildExpertQuestionState objects
     * @throws PropelException
     */
    public function getExpertQuestionStates(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collExpertQuestionStatesPartial && !$this->isNew();
        if (null === $this->collExpertQuestionStates || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collExpertQuestionStates) {
                // return empty collection
                $this->initExpertQuestionStates();
            } else {
                $collExpertQuestionStates = ChildExpertQuestionStateQuery::create(null, $criteria)
                    ->filterByExperts($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collExpertQuestionStatesPartial && count($collExpertQuestionStates)) {
                        $this->initExpertQuestionStates(false);

                        foreach ($collExpertQuestionStates as $obj) {
                            if (false == $this->collExpertQuestionStates->contains($obj)) {
                                $this->collExpertQuestionStates->append($obj);
                            }
                        }

                        $this->collExpertQuestionStatesPartial = true;
                    }

                    return $collExpertQuestionStates;
                }

                if ($partial && $this->collExpertQuestionStates) {
                    foreach ($this->collExpertQuestionStates as $obj) {
                        if ($obj->isNew()) {
                            $collExpertQuestionStates[] = $obj;
                        }
                    }
                }

                $this->collExpertQuestionStates = $collExpertQuestionStates;
                $this->collExpertQuestionStatesPartial = false;
            }
        }

        return $this->collExpertQuestionStates;
    }

    /**
     * Sets a collection of ChildExpertQuestionState objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $expertQuestionStates A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildExperts The current object (for fluent API support)
     */
    public function setExpertQuestionStates(Collection $expertQuestionStates, ConnectionInterface $con = null)
    {
        /** @var ChildExpertQuestionState[] $expertQuestionStatesToDelete */
        $expertQuestionStatesToDelete = $this->getExpertQuestionStates(new Criteria(), $con)->diff($expertQuestionStates);


        $this->expertQuestionStatesScheduledForDeletion = $expertQuestionStatesToDelete;

        foreach ($expertQuestionStatesToDelete as $expertQuestionStateRemoved) {
            $expertQuestionStateRemoved->setExperts(null);
        }

        $this->collExpertQuestionStates = null;
        foreach ($expertQuestionStates as $expertQuestionState) {
            $this->addExpertQuestionState($expertQuestionState);
        }

        $this->collExpertQuestionStates = $expertQuestionStates;
        $this->collExpertQuestionStatesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ExpertQuestionState objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related ExpertQuestionState objects.
     * @throws PropelException
     */
    public function countExpertQuestionStates(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collExpertQuestionStatesPartial && !$this->isNew();
        if (null === $this->collExpertQuestionStates || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collExpertQuestionStates) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getExpertQuestionStates());
            }

            $query = ChildExpertQuestionStateQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByExperts($this)
                ->count($con);
        }

        return count($this->collExpertQuestionStates);
    }

    /**
     * Method called to associate a ChildExpertQuestionState object to this object
     * through the ChildExpertQuestionState foreign key attribute.
     *
     * @param  ChildExpertQuestionState $l ChildExpertQuestionState
     * @return $this|\Experts The current object (for fluent API support)
     */
    public function addExpertQuestionState(ChildExpertQuestionState $l)
    {
        if ($this->collExpertQuestionStates === null) {
            $this->initExpertQuestionStates();
            $this->collExpertQuestionStatesPartial = true;
        }

        if (!$this->collExpertQuestionStates->contains($l)) {
            $this->doAddExpertQuestionState($l);
        }

        return $this;
    }

    /**
     * @param ChildExpertQuestionState $expertQuestionState The ChildExpertQuestionState object to add.
     */
    protected function doAddExpertQuestionState(ChildExpertQuestionState $expertQuestionState)
    {
        $this->collExpertQuestionStates[]= $expertQuestionState;
        $expertQuestionState->setExperts($this);
    }

    /**
     * @param  ChildExpertQuestionState $expertQuestionState The ChildExpertQuestionState object to remove.
     * @return $this|ChildExperts The current object (for fluent API support)
     */
    public function removeExpertQuestionState(ChildExpertQuestionState $expertQuestionState)
    {
        if ($this->getExpertQuestionStates()->contains($expertQuestionState)) {
            $pos = $this->collExpertQuestionStates->search($expertQuestionState);
            $this->collExpertQuestionStates->remove($pos);
            if (null === $this->expertQuestionStatesScheduledForDeletion) {
                $this->expertQuestionStatesScheduledForDeletion = clone $this->collExpertQuestionStates;
                $this->expertQuestionStatesScheduledForDeletion->clear();
            }
            $this->expertQuestionStatesScheduledForDeletion[]= clone $expertQuestionState;
            $expertQuestionState->setExperts(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Experts is new, it will return
     * an empty collection; or if this Experts has previously
     * been saved, it will retrieve related ExpertQuestionStates from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Experts.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildExpertQuestionState[] List of ChildExpertQuestionState objects
     */
    public function getExpertQuestionStatesJoinQuestions(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildExpertQuestionStateQuery::create(null, $criteria);
        $query->joinWith('Questions', $joinBehavior);

        return $this->getExpertQuestionStates($query, $con);
    }

    /**
     * Clears out the collResponsess collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addResponsess()
     */
    public function clearResponsess()
    {
        $this->collResponsess = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collResponsess collection loaded partially.
     */
    public function resetPartialResponsess($v = true)
    {
        $this->collResponsessPartial = $v;
    }

    /**
     * Initializes the collResponsess collection.
     *
     * By default this just sets the collResponsess collection to an empty array (like clearcollResponsess());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initResponsess($overrideExisting = true)
    {
        if (null !== $this->collResponsess && !$overrideExisting) {
            return;
        }
        $this->collResponsess = new ObjectCollection();
        $this->collResponsess->setModel('\Responses');
    }

    /**
     * Gets an array of ChildResponses objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildExperts is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildResponses[] List of ChildResponses objects
     * @throws PropelException
     */
    public function getResponsess(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collResponsessPartial && !$this->isNew();
        if (null === $this->collResponsess || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collResponsess) {
                // return empty collection
                $this->initResponsess();
            } else {
                $collResponsess = ChildResponsesQuery::create(null, $criteria)
                    ->filterByExperts($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collResponsessPartial && count($collResponsess)) {
                        $this->initResponsess(false);

                        foreach ($collResponsess as $obj) {
                            if (false == $this->collResponsess->contains($obj)) {
                                $this->collResponsess->append($obj);
                            }
                        }

                        $this->collResponsessPartial = true;
                    }

                    return $collResponsess;
                }

                if ($partial && $this->collResponsess) {
                    foreach ($this->collResponsess as $obj) {
                        if ($obj->isNew()) {
                            $collResponsess[] = $obj;
                        }
                    }
                }

                $this->collResponsess = $collResponsess;
                $this->collResponsessPartial = false;
            }
        }

        return $this->collResponsess;
    }

    /**
     * Sets a collection of ChildResponses objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $responsess A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildExperts The current object (for fluent API support)
     */
    public function setResponsess(Collection $responsess, ConnectionInterface $con = null)
    {
        /** @var ChildResponses[] $responsessToDelete */
        $responsessToDelete = $this->getResponsess(new Criteria(), $con)->diff($responsess);


        $this->responsessScheduledForDeletion = $responsessToDelete;

        foreach ($responsessToDelete as $responsesRemoved) {
            $responsesRemoved->setExperts(null);
        }

        $this->collResponsess = null;
        foreach ($responsess as $responses) {
            $this->addResponses($responses);
        }

        $this->collResponsess = $responsess;
        $this->collResponsessPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Responses objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Responses objects.
     * @throws PropelException
     */
    public function countResponsess(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collResponsessPartial && !$this->isNew();
        if (null === $this->collResponsess || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collResponsess) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getResponsess());
            }

            $query = ChildResponsesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByExperts($this)
                ->count($con);
        }

        return count($this->collResponsess);
    }

    /**
     * Method called to associate a ChildResponses object to this object
     * through the ChildResponses foreign key attribute.
     *
     * @param  ChildResponses $l ChildResponses
     * @return $this|\Experts The current object (for fluent API support)
     */
    public function addResponses(ChildResponses $l)
    {
        if ($this->collResponsess === null) {
            $this->initResponsess();
            $this->collResponsessPartial = true;
        }

        if (!$this->collResponsess->contains($l)) {
            $this->doAddResponses($l);
        }

        return $this;
    }

    /**
     * @param ChildResponses $responses The ChildResponses object to add.
     */
    protected function doAddResponses(ChildResponses $responses)
    {
        $this->collResponsess[]= $responses;
        $responses->setExperts($this);
    }

    /**
     * @param  ChildResponses $responses The ChildResponses object to remove.
     * @return $this|ChildExperts The current object (for fluent API support)
     */
    public function removeResponses(ChildResponses $responses)
    {
        if ($this->getResponsess()->contains($responses)) {
            $pos = $this->collResponsess->search($responses);
            $this->collResponsess->remove($pos);
            if (null === $this->responsessScheduledForDeletion) {
                $this->responsessScheduledForDeletion = clone $this->collResponsess;
                $this->responsessScheduledForDeletion->clear();
            }
            $this->responsessScheduledForDeletion[]= clone $responses;
            $responses->setExperts(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Experts is new, it will return
     * an empty collection; or if this Experts has previously
     * been saved, it will retrieve related Responsess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Experts.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildResponses[] List of ChildResponses objects
     */
    public function getResponsessJoinQuestions(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildResponsesQuery::create(null, $criteria);
        $query->joinWith('Questions', $joinBehavior);

        return $this->getResponsess($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->username = null;
        $this->first_name = null;
        $this->last_name = null;
        $this->bio = null;
        $this->organization = null;
        $this->password = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collExpertGroupMemberss) {
                foreach ($this->collExpertGroupMemberss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collExpertQuestionStates) {
                foreach ($this->collExpertQuestionStates as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collResponsess) {
                foreach ($this->collResponsess as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collExpertGroupMemberss = null;
        $this->collExpertQuestionStates = null;
        $this->collResponsess = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(ExpertsTableMap::DEFAULT_STRING_FORMAT);
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {

    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}

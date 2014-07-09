<?php

namespace Base;

use \ExpertQuestionState as ChildExpertQuestionState;
use \ExpertQuestionStateQuery as ChildExpertQuestionStateQuery;
use \Questions as ChildQuestions;
use \QuestionsQuery as ChildQuestionsQuery;
use \Responses as ChildResponses;
use \ResponsesQuery as ChildResponsesQuery;
use \DateTime;
use \Exception;
use \PDO;
use Map\QuestionsTableMap;
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
use Propel\Runtime\Util\PropelDateTime;

abstract class Questions implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\QuestionsTableMap';


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
     * The value for the id field.
     * @var        string
     */
    protected $id;

    /**
     * The value for the submitter field.
     * @var        string
     */
    protected $submitter;

    /**
     * The value for the question field.
     * @var        string
     */
    protected $question;

    /**
     * The value for the created field.
     * Note: this column has a database default value of: (expression) CURRENT_TIMESTAMP
     * @var        \DateTime
     */
    protected $created;

    /**
     * The value for the soc_code field.
     * @var        string
     */
    protected $soc_code;

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
     * @var ObjectCollection|ChildExpertQuestionState[]
     */
    protected $expertQuestionStatesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildResponses[]
     */
    protected $responsessScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
    }

    /**
     * Initializes internal state of Base\Questions object.
     * @see applyDefaults()
     */
    public function __construct()
    {
        $this->applyDefaultValues();
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
     * Compares this with another <code>Questions</code> instance.  If
     * <code>obj</code> is an instance of <code>Questions</code>, delegates to
     * <code>equals(Questions)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Questions The current object, for fluid interface
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
     * Get the [id] column value.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [submitter] column value.
     *
     * @return string
     */
    public function getSubmitter()
    {
        return $this->submitter;
    }

    /**
     * Get the [question] column value.
     *
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Get the [optionally formatted] temporal [created] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw \DateTime object will be returned.
     *
     * @return string|\DateTime Formatted date/time value as string or \DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreated($format = NULL)
    {
        if ($format === null) {
            return $this->created;
        } else {
            return $this->created instanceof \DateTime ? $this->created->format($format) : null;
        }
    }

    /**
     * Get the [soc_code] column value.
     *
     * @return string
     */
    public function getSocCode()
    {
        return $this->soc_code;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : QuestionsTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : QuestionsTableMap::translateFieldName('Submitter', TableMap::TYPE_PHPNAME, $indexType)];
            $this->submitter = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : QuestionsTableMap::translateFieldName('Question', TableMap::TYPE_PHPNAME, $indexType)];
            $this->question = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : QuestionsTableMap::translateFieldName('Created', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created = (null !== $col) ? PropelDateTime::newInstance($col, null, '\DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : QuestionsTableMap::translateFieldName('SocCode', TableMap::TYPE_PHPNAME, $indexType)];
            $this->soc_code = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 5; // 5 = QuestionsTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Questions'), 0, $e);
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
     * Set the value of [id] column.
     *
     * @param  string $v new value
     * @return $this|\Questions The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[QuestionsTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [submitter] column.
     *
     * @param  string $v new value
     * @return $this|\Questions The current object (for fluent API support)
     */
    public function setSubmitter($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->submitter !== $v) {
            $this->submitter = $v;
            $this->modifiedColumns[QuestionsTableMap::COL_SUBMITTER] = true;
        }

        return $this;
    } // setSubmitter()

    /**
     * Set the value of [question] column.
     *
     * @param  string $v new value
     * @return $this|\Questions The current object (for fluent API support)
     */
    public function setQuestion($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->question !== $v) {
            $this->question = $v;
            $this->modifiedColumns[QuestionsTableMap::COL_QUESTION] = true;
        }

        return $this;
    } // setQuestion()

    /**
     * Sets the value of [created] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\Questions The current object (for fluent API support)
     */
    public function setCreated($v)
    {
        $dt = PropelDateTime::newInstance($v, null, '\DateTime');
        if ($this->created !== null || $dt !== null) {
            if ($dt !== $this->created) {
                $this->created = $dt;
                $this->modifiedColumns[QuestionsTableMap::COL_CREATED] = true;
            }
        } // if either are not null

        return $this;
    } // setCreated()

    /**
     * Set the value of [soc_code] column.
     *
     * @param  string $v new value
     * @return $this|\Questions The current object (for fluent API support)
     */
    public function setSocCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->soc_code !== $v) {
            $this->soc_code = $v;
            $this->modifiedColumns[QuestionsTableMap::COL_SOC_CODE] = true;
        }

        return $this;
    } // setSocCode()

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
            $con = Propel::getServiceContainer()->getReadConnection(QuestionsTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildQuestionsQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

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
     * @see Questions::setDeleted()
     * @see Questions::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(QuestionsTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildQuestionsQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(QuestionsTableMap::DATABASE_NAME);
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
                QuestionsTableMap::addInstanceToPool($this);
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

        $this->modifiedColumns[QuestionsTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . QuestionsTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(QuestionsTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'ID';
        }
        if ($this->isColumnModified(QuestionsTableMap::COL_SUBMITTER)) {
            $modifiedColumns[':p' . $index++]  = 'SUBMITTER';
        }
        if ($this->isColumnModified(QuestionsTableMap::COL_QUESTION)) {
            $modifiedColumns[':p' . $index++]  = 'QUESTION';
        }
        if ($this->isColumnModified(QuestionsTableMap::COL_CREATED)) {
            $modifiedColumns[':p' . $index++]  = 'CREATED';
        }
        if ($this->isColumnModified(QuestionsTableMap::COL_SOC_CODE)) {
            $modifiedColumns[':p' . $index++]  = 'SOC_CODE';
        }

        $sql = sprintf(
            'INSERT INTO questions (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'ID':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case 'SUBMITTER':
                        $stmt->bindValue($identifier, $this->submitter, PDO::PARAM_STR);
                        break;
                    case 'QUESTION':
                        $stmt->bindValue($identifier, $this->question, PDO::PARAM_STR);
                        break;
                    case 'CREATED':
                        $stmt->bindValue($identifier, $this->created ? $this->created->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'SOC_CODE':
                        $stmt->bindValue($identifier, $this->soc_code, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

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
        $pos = QuestionsTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getId();
                break;
            case 1:
                return $this->getSubmitter();
                break;
            case 2:
                return $this->getQuestion();
                break;
            case 3:
                return $this->getCreated();
                break;
            case 4:
                return $this->getSocCode();
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
        if (isset($alreadyDumpedObjects['Questions'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Questions'][$this->getPrimaryKey()] = true;
        $keys = QuestionsTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getSubmitter(),
            $keys[2] => $this->getQuestion(),
            $keys[3] => $this->getCreated(),
            $keys[4] => $this->getSocCode(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
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
     * @return $this|\Questions
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = QuestionsTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Questions
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setSubmitter($value);
                break;
            case 2:
                $this->setQuestion($value);
                break;
            case 3:
                $this->setCreated($value);
                break;
            case 4:
                $this->setSocCode($value);
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
        $keys = QuestionsTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setSubmitter($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setQuestion($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setCreated($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setSocCode($arr[$keys[4]]);
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
     * @return $this|\Questions The current object, for fluid interface
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
        $criteria = new Criteria(QuestionsTableMap::DATABASE_NAME);

        if ($this->isColumnModified(QuestionsTableMap::COL_ID)) {
            $criteria->add(QuestionsTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(QuestionsTableMap::COL_SUBMITTER)) {
            $criteria->add(QuestionsTableMap::COL_SUBMITTER, $this->submitter);
        }
        if ($this->isColumnModified(QuestionsTableMap::COL_QUESTION)) {
            $criteria->add(QuestionsTableMap::COL_QUESTION, $this->question);
        }
        if ($this->isColumnModified(QuestionsTableMap::COL_CREATED)) {
            $criteria->add(QuestionsTableMap::COL_CREATED, $this->created);
        }
        if ($this->isColumnModified(QuestionsTableMap::COL_SOC_CODE)) {
            $criteria->add(QuestionsTableMap::COL_SOC_CODE, $this->soc_code);
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
        $criteria = new Criteria(QuestionsTableMap::DATABASE_NAME);
        $criteria->add(QuestionsTableMap::COL_ID, $this->id);

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
        $validPk = null !== $this->getId();

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
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       string $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Questions (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setSubmitter($this->getSubmitter());
        $copyObj->setQuestion($this->getQuestion());
        $copyObj->setCreated($this->getCreated());
        $copyObj->setSocCode($this->getSocCode());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

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
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
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
     * @return \Questions Clone of current object.
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
        if ('ExpertQuestionState' == $relationName) {
            return $this->initExpertQuestionStates();
        }
        if ('Responses' == $relationName) {
            return $this->initResponsess();
        }
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
     * If this ChildQuestions is new, it will return
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
                    ->filterByQuestions($this)
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
     * @return $this|ChildQuestions The current object (for fluent API support)
     */
    public function setExpertQuestionStates(Collection $expertQuestionStates, ConnectionInterface $con = null)
    {
        /** @var ChildExpertQuestionState[] $expertQuestionStatesToDelete */
        $expertQuestionStatesToDelete = $this->getExpertQuestionStates(new Criteria(), $con)->diff($expertQuestionStates);


        $this->expertQuestionStatesScheduledForDeletion = $expertQuestionStatesToDelete;

        foreach ($expertQuestionStatesToDelete as $expertQuestionStateRemoved) {
            $expertQuestionStateRemoved->setQuestions(null);
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
                ->filterByQuestions($this)
                ->count($con);
        }

        return count($this->collExpertQuestionStates);
    }

    /**
     * Method called to associate a ChildExpertQuestionState object to this object
     * through the ChildExpertQuestionState foreign key attribute.
     *
     * @param  ChildExpertQuestionState $l ChildExpertQuestionState
     * @return $this|\Questions The current object (for fluent API support)
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
        $expertQuestionState->setQuestions($this);
    }

    /**
     * @param  ChildExpertQuestionState $expertQuestionState The ChildExpertQuestionState object to remove.
     * @return $this|ChildQuestions The current object (for fluent API support)
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
            $expertQuestionState->setQuestions(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Questions is new, it will return
     * an empty collection; or if this Questions has previously
     * been saved, it will retrieve related ExpertQuestionStates from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Questions.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildExpertQuestionState[] List of ChildExpertQuestionState objects
     */
    public function getExpertQuestionStatesJoinExperts(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildExpertQuestionStateQuery::create(null, $criteria);
        $query->joinWith('Experts', $joinBehavior);

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
     * If this ChildQuestions is new, it will return
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
                    ->filterByQuestions($this)
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
     * @return $this|ChildQuestions The current object (for fluent API support)
     */
    public function setResponsess(Collection $responsess, ConnectionInterface $con = null)
    {
        /** @var ChildResponses[] $responsessToDelete */
        $responsessToDelete = $this->getResponsess(new Criteria(), $con)->diff($responsess);


        $this->responsessScheduledForDeletion = $responsessToDelete;

        foreach ($responsessToDelete as $responsesRemoved) {
            $responsesRemoved->setQuestions(null);
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
                ->filterByQuestions($this)
                ->count($con);
        }

        return count($this->collResponsess);
    }

    /**
     * Method called to associate a ChildResponses object to this object
     * through the ChildResponses foreign key attribute.
     *
     * @param  ChildResponses $l ChildResponses
     * @return $this|\Questions The current object (for fluent API support)
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
        $responses->setQuestions($this);
    }

    /**
     * @param  ChildResponses $responses The ChildResponses object to remove.
     * @return $this|ChildQuestions The current object (for fluent API support)
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
            $responses->setQuestions(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Questions is new, it will return
     * an empty collection; or if this Questions has previously
     * been saved, it will retrieve related Responsess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Questions.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildResponses[] List of ChildResponses objects
     */
    public function getResponsessJoinExperts(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildResponsesQuery::create(null, $criteria);
        $query->joinWith('Experts', $joinBehavior);

        return $this->getResponsess($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->id = null;
        $this->submitter = null;
        $this->question = null;
        $this->created = null;
        $this->soc_code = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
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
        return (string) $this->exportTo(QuestionsTableMap::DEFAULT_STRING_FORMAT);
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

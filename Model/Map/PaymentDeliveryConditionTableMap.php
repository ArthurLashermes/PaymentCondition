<?php

namespace PaymentCondition\Model\Map;

use PaymentCondition\Model\PaymentDeliveryCondition;
use PaymentCondition\Model\PaymentDeliveryConditionQuery;
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
 * This class defines the structure of the 'payment_delivery_condition' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class PaymentDeliveryConditionTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;
    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'PaymentCondition.Model.Map.PaymentDeliveryConditionTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'thelia';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'payment_delivery_condition';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\PaymentCondition\\Model\\PaymentDeliveryCondition';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'PaymentCondition.Model.PaymentDeliveryCondition';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 4;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 4;

    /**
     * the column name for the ID field
     */
    const ID = 'payment_delivery_condition.ID';

    /**
     * the column name for the PAYMENT_MODULE_ID field
     */
    const PAYMENT_MODULE_ID = 'payment_delivery_condition.PAYMENT_MODULE_ID';

    /**
     * the column name for the DELIVERY_MODULE_ID field
     */
    const DELIVERY_MODULE_ID = 'payment_delivery_condition.DELIVERY_MODULE_ID';

    /**
     * the column name for the IS_VALID field
     */
    const IS_VALID = 'payment_delivery_condition.IS_VALID';

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
        self::TYPE_PHPNAME       => array('Id', 'PaymentModuleId', 'DeliveryModuleId', 'IsValid', ),
        self::TYPE_STUDLYPHPNAME => array('id', 'paymentModuleId', 'deliveryModuleId', 'isValid', ),
        self::TYPE_COLNAME       => array(PaymentDeliveryConditionTableMap::ID, PaymentDeliveryConditionTableMap::PAYMENT_MODULE_ID, PaymentDeliveryConditionTableMap::DELIVERY_MODULE_ID, PaymentDeliveryConditionTableMap::IS_VALID, ),
        self::TYPE_RAW_COLNAME   => array('ID', 'PAYMENT_MODULE_ID', 'DELIVERY_MODULE_ID', 'IS_VALID', ),
        self::TYPE_FIELDNAME     => array('id', 'payment_module_id', 'delivery_module_id', 'is_valid', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'PaymentModuleId' => 1, 'DeliveryModuleId' => 2, 'IsValid' => 3, ),
        self::TYPE_STUDLYPHPNAME => array('id' => 0, 'paymentModuleId' => 1, 'deliveryModuleId' => 2, 'isValid' => 3, ),
        self::TYPE_COLNAME       => array(PaymentDeliveryConditionTableMap::ID => 0, PaymentDeliveryConditionTableMap::PAYMENT_MODULE_ID => 1, PaymentDeliveryConditionTableMap::DELIVERY_MODULE_ID => 2, PaymentDeliveryConditionTableMap::IS_VALID => 3, ),
        self::TYPE_RAW_COLNAME   => array('ID' => 0, 'PAYMENT_MODULE_ID' => 1, 'DELIVERY_MODULE_ID' => 2, 'IS_VALID' => 3, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'payment_module_id' => 1, 'delivery_module_id' => 2, 'is_valid' => 3, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, )
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
        $this->setName('payment_delivery_condition');
        $this->setPhpName('PaymentDeliveryCondition');
        $this->setClassName('\\PaymentCondition\\Model\\PaymentDeliveryCondition');
        $this->setPackage('PaymentCondition.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('PAYMENT_MODULE_ID', 'PaymentModuleId', 'INTEGER', 'module', 'ID', true, null, null);
        $this->addForeignKey('DELIVERY_MODULE_ID', 'DeliveryModuleId', 'INTEGER', 'module', 'ID', true, null, null);
        $this->addColumn('IS_VALID', 'IsValid', 'TINYINT', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('ModuleRelatedByPaymentModuleId', '\\Thelia\\Model\\Module', RelationMap::MANY_TO_ONE, array('payment_module_id' => 'id', ), 'CASCADE', 'RESTRICT');
        $this->addRelation('ModuleRelatedByDeliveryModuleId', '\\Thelia\\Model\\Module', RelationMap::MANY_TO_ONE, array('delivery_module_id' => 'id', ), 'CASCADE', 'RESTRICT');
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

            return (int) $row[
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
        return $withPrefix ? PaymentDeliveryConditionTableMap::CLASS_DEFAULT : PaymentDeliveryConditionTableMap::OM_CLASS;
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
     *         rethrown wrapped into a PropelException.
     * @return array (PaymentDeliveryCondition object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = PaymentDeliveryConditionTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = PaymentDeliveryConditionTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + PaymentDeliveryConditionTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = PaymentDeliveryConditionTableMap::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            PaymentDeliveryConditionTableMap::addInstanceToPool($obj, $key);
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
     *         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = PaymentDeliveryConditionTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = PaymentDeliveryConditionTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                PaymentDeliveryConditionTableMap::addInstanceToPool($obj, $key);
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
     *         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(PaymentDeliveryConditionTableMap::ID);
            $criteria->addSelectColumn(PaymentDeliveryConditionTableMap::PAYMENT_MODULE_ID);
            $criteria->addSelectColumn(PaymentDeliveryConditionTableMap::DELIVERY_MODULE_ID);
            $criteria->addSelectColumn(PaymentDeliveryConditionTableMap::IS_VALID);
        } else {
            $criteria->addSelectColumn($alias . '.ID');
            $criteria->addSelectColumn($alias . '.PAYMENT_MODULE_ID');
            $criteria->addSelectColumn($alias . '.DELIVERY_MODULE_ID');
            $criteria->addSelectColumn($alias . '.IS_VALID');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(PaymentDeliveryConditionTableMap::DATABASE_NAME)->getTable(PaymentDeliveryConditionTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getServiceContainer()->getDatabaseMap(PaymentDeliveryConditionTableMap::DATABASE_NAME);
      if (!$dbMap->hasTable(PaymentDeliveryConditionTableMap::TABLE_NAME)) {
        $dbMap->addTableObject(new PaymentDeliveryConditionTableMap());
      }
    }

    /**
     * Performs a DELETE on the database, given a PaymentDeliveryCondition or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or PaymentDeliveryCondition object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PaymentDeliveryConditionTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \PaymentCondition\Model\PaymentDeliveryCondition) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(PaymentDeliveryConditionTableMap::DATABASE_NAME);
            $criteria->add(PaymentDeliveryConditionTableMap::ID, (array) $values, Criteria::IN);
        }

        $query = PaymentDeliveryConditionQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) { PaymentDeliveryConditionTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) { PaymentDeliveryConditionTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the payment_delivery_condition table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return PaymentDeliveryConditionQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a PaymentDeliveryCondition or Criteria object.
     *
     * @param mixed               $criteria Criteria or PaymentDeliveryCondition object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PaymentDeliveryConditionTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from PaymentDeliveryCondition object
        }

        if ($criteria->containsKey(PaymentDeliveryConditionTableMap::ID) && $criteria->keyContainsValue(PaymentDeliveryConditionTableMap::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.PaymentDeliveryConditionTableMap::ID.')');
        }


        // Set the correct dbName
        $query = PaymentDeliveryConditionQuery::create()->mergeWith($criteria);

        try {
            // use transaction because $criteria could contain info
            // for more than one table (I guess, conceivably)
            $con->beginTransaction();
            $pk = $query->doInsert($con);
            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $pk;
    }

} // PaymentDeliveryConditionTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
PaymentDeliveryConditionTableMap::buildTableMap();

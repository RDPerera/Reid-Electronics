<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 * Generated from xml/schema/CRM/Queue/QueueItem.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:2a3cbbf88c75557fad0418a96b051160)
 */

/**
 * Database access object for the QueueItem entity.
 */
class CRM_Queue_DAO_QueueItem extends CRM_Core_DAO {

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_queue_item';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  public static $_log = FALSE;

  /**
   * @var int
   */
  public $id;

  /**
   * Name of the queue which includes this item
   *
   * @var string
   */
  public $queue_name;

  /**
   * @var int
   */
  public $weight;

  /**
   * date on which this item was submitted to the queue
   *
   * @var datetime
   */
  public $submit_time;

  /**
   * date on which this job becomes available; null if ASAP
   *
   * @var datetime
   */
  public $release_time;

  /**
   * Serialized queue data
   *
   * @var text
   */
  public $data;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'civicrm_queue_item';
    parent::__construct();
  }

  /**
   * Returns all the column names of this table
   *
   * @return array
   */
  public static function &fields() {
    if (!isset(Civi::$statics[__CLASS__]['fields'])) {
      Civi::$statics[__CLASS__]['fields'] = [
        'id' => [
          'name' => 'id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('Queue ID'),
          'required' => TRUE,
          'where' => 'civicrm_queue_item.id',
          'table_name' => 'civicrm_queue_item',
          'entity' => 'QueueItem',
          'bao' => 'CRM_Queue_BAO_QueueItem',
          'localizable' => 0,
        ],
        'queue_name' => [
          'name' => 'queue_name',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Queue Name'),
          'description' => ts('Name of the queue which includes this item'),
          'required' => TRUE,
          'maxlength' => 64,
          'size' => CRM_Utils_Type::BIG,
          'where' => 'civicrm_queue_item.queue_name',
          'table_name' => 'civicrm_queue_item',
          'entity' => 'QueueItem',
          'bao' => 'CRM_Queue_BAO_QueueItem',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
        ],
        'weight' => [
          'name' => 'weight',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('Order'),
          'required' => TRUE,
          'where' => 'civicrm_queue_item.weight',
          'table_name' => 'civicrm_queue_item',
          'entity' => 'QueueItem',
          'bao' => 'CRM_Queue_BAO_QueueItem',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
        ],
        'submit_time' => [
          'name' => 'submit_time',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => ts('Submit Time'),
          'description' => ts('date on which this item was submitted to the queue'),
          'required' => TRUE,
          'where' => 'civicrm_queue_item.submit_time',
          'table_name' => 'civicrm_queue_item',
          'entity' => 'QueueItem',
          'bao' => 'CRM_Queue_BAO_QueueItem',
          'localizable' => 0,
          'html' => [
            'type' => 'Select Date',
          ],
        ],
        'release_time' => [
          'name' => 'release_time',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => ts('Release Time'),
          'description' => ts('date on which this job becomes available; null if ASAP'),
          'where' => 'civicrm_queue_item.release_time',
          'table_name' => 'civicrm_queue_item',
          'entity' => 'QueueItem',
          'bao' => 'CRM_Queue_BAO_QueueItem',
          'localizable' => 0,
          'html' => [
            'type' => 'Select Date',
          ],
        ],
        'data' => [
          'name' => 'data',
          'type' => CRM_Utils_Type::T_TEXT,
          'title' => ts('Queue item data'),
          'description' => ts('Serialized queue data'),
          'where' => 'civicrm_queue_item.data',
          'table_name' => 'civicrm_queue_item',
          'entity' => 'QueueItem',
          'bao' => 'CRM_Queue_BAO_QueueItem',
          'localizable' => 0,
          'serialize' => self::SERIALIZE_PHP,
        ],
      ];
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'fields_callback', Civi::$statics[__CLASS__]['fields']);
    }
    return Civi::$statics[__CLASS__]['fields'];
  }

  /**
   * Return a mapping from field-name to the corresponding key (as used in fields()).
   *
   * @return array
   *   Array(string $name => string $uniqueName).
   */
  public static function &fieldKeys() {
    if (!isset(Civi::$statics[__CLASS__]['fieldKeys'])) {
      Civi::$statics[__CLASS__]['fieldKeys'] = array_flip(CRM_Utils_Array::collect('name', self::fields()));
    }
    return Civi::$statics[__CLASS__]['fieldKeys'];
  }

  /**
   * Returns the names of this table
   *
   * @return string
   */
  public static function getTableName() {
    return self::$_tableName;
  }

  /**
   * Returns if this table needs to be logged
   *
   * @return bool
   */
  public function getLog() {
    return self::$_log;
  }

  /**
   * Returns the list of fields that can be imported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &import($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'queue_item', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of fields that can be exported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &export($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'queue_item', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of indices
   *
   * @param bool $localize
   *
   * @return array
   */
  public static function indices($localize = TRUE) {
    $indices = [
      'index_queueids' => [
        'name' => 'index_queueids',
        'field' => [
          0 => 'queue_name',
          1 => 'weight',
          2 => 'id',
        ],
        'localizable' => FALSE,
        'sig' => 'civicrm_queue_item::0::queue_name::weight::id',
      ],
    ];
    return ($localize && !empty($indices)) ? CRM_Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }

}

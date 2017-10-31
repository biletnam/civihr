<?php
/*
+--------------------------------------------------------------------+
| CiviCRM version 4.7                                                |
+--------------------------------------------------------------------+
| Copyright CiviCRM LLC (c) 2004-2017                                |
+--------------------------------------------------------------------+
| This file is a part of CiviCRM.                                    |
|                                                                    |
| CiviCRM is free software; you can copy, modify, and distribute it  |
| under the terms of the GNU Affero General Public License           |
| Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
|                                                                    |
| CiviCRM is distributed in the hope that it will be useful, but     |
| WITHOUT ANY WARRANTY; without even the implied warranty of         |
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
| See the GNU Affero General Public License for more details.        |
|                                                                    |
| You should have received a copy of the GNU Affero General Public   |
| License and the CiviCRM Licensing Exception along                  |
| with this program; if not, contact CiviCRM LLC                     |
| at info[AT]civicrm[DOT]org. If you have questions about the        |
| GNU Affero General Public License or the licensing of CiviCRM,     |
| see the CiviCRM license FAQ at http://civicrm.org/licensing        |
+--------------------------------------------------------------------+
*/
/**
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2017
 *
 * Generated from xml/schema/CRM/HRLeaveAndAbsences/LeaveRequest.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:22032c3268b6c7fab11cba2139d759cb)
 */
require_once 'CRM/Core/DAO.php';
require_once 'CRM/Utils/Type.php';
/**
 * CRM_HRLeaveAndAbsences_DAO_LeaveRequest constructor.
 */
class CRM_HRLeaveAndAbsences_DAO_LeaveRequest extends CRM_Core_DAO {
  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  static $_tableName = 'civicrm_hrleaveandabsences_leave_request';
  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var boolean
   */
  static $_log = true;
  /**
   * Unique LeaveRequest ID
   *
   * @var int unsigned
   */
  public $id;
  /**
   * FK to AbsenceType
   *
   * @var int unsigned
   */
  public $type_id;
  /**
   * FK to Contact
   *
   * @var int unsigned
   */
  public $contact_id;
  /**
   * One of the values of the Leave Request Status option group
   *
   * @var int unsigned
   */
  public $status_id;
  /**
   * The date and time the leave request starts.
   *
   * @var datetime
   */
  public $from_date;
  /**
   * One of the values of the Leave Request Day Type option group
   *
   * @var int unsigned
   */
  public $from_date_type;
  /**
   * The date and time the leave request ends
   *
   * @var datetime
   */
  public $to_date;
  /**
   * One of the values of the Leave Request Day Type option group
   *
   * @var int unsigned
   */
  public $to_date_type;
  /**
   * One of the values of the Sickness Reason option group
   *
   * @var string
   */
  public $sickness_reason;
  /**
   * A list of values from the LeaveRequestRequiredDocument option group
   *
   * @var string
   */
  public $sickness_required_documents;
  /**
   * The duration of the overtime work in minutes
   *
   * @var int unsigned
   */
  public $toil_duration;
  /**
   * The amount of days accrued for this toil request
   *
   * @var string
   */
  public $toil_to_accrue;
  /**
   * The expiry date of this TOIL Request. When null, it means it never expires.
   *
   * @var date
   */
  public $toil_expiry_date;
  /**
   * The type of this request (leave, toil, sickness etc)
   *
   * @var string
   */
  public $request_type;
  /**
   * Whether this leave request has been deleted or not
   *
   * @var boolean
   */
  public $is_deleted;
  /**
   * The balance change amount to be deducted for the leave request from date
   *
   * @var float
   */
  public $from_date_amount;
  /**
   * The balance change amount to be deducted for the leave request to date
   *
   * @var float
   */
  public $to_date_amount;
  /**
   * Class constructor.
   */
  function __construct() {
    $this->__table = 'civicrm_hrleaveandabsences_leave_request';
    parent::__construct();
  }
  /**
   * Returns foreign keys and entity references.
   *
   * @return array
   *   [CRM_Core_Reference_Interface]
   */
  static function getReferenceColumns() {
    if (!isset(Civi::$statics[__CLASS__]['links'])) {
      Civi::$statics[__CLASS__]['links'] = static ::createReferenceColumns(__CLASS__);
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName() , 'type_id', 'civicrm_hrleaveandabsences_absence_type', 'id');
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName() , 'contact_id', 'civicrm_contact', 'id');
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'links_callback', Civi::$statics[__CLASS__]['links']);
    }
    return Civi::$statics[__CLASS__]['links'];
  }
  /**
   * Returns all the column names of this table
   *
   * @return array
   */
  static function &fields() {
    if (!isset(Civi::$statics[__CLASS__]['fields'])) {
      Civi::$statics[__CLASS__]['fields'] = array(
        'id' => array(
          'name' => 'id',
          'type' => CRM_Utils_Type::T_INT,
          'description' => 'Unique LeaveRequest ID',
          'required' => true,
          'table_name' => 'civicrm_hrleaveandabsences_leave_request',
          'entity' => 'LeaveRequest',
          'bao' => 'CRM_HRLeaveAndAbsences_DAO_LeaveRequest',
          'localizable' => 0,
        ) ,
        'type_id' => array(
          'name' => 'type_id',
          'type' => CRM_Utils_Type::T_INT,
          'description' => 'FK to AbsenceType',
          'required' => true,
          'table_name' => 'civicrm_hrleaveandabsences_leave_request',
          'entity' => 'LeaveRequest',
          'bao' => 'CRM_HRLeaveAndAbsences_DAO_LeaveRequest',
          'localizable' => 0,
          'FKClassName' => 'CRM_HRLeaveAndAbsences_DAO_AbsenceType',
        ) ,
        'contact_id' => array(
          'name' => 'contact_id',
          'type' => CRM_Utils_Type::T_INT,
          'description' => 'FK to Contact',
          'required' => true,
          'table_name' => 'civicrm_hrleaveandabsences_leave_request',
          'entity' => 'LeaveRequest',
          'bao' => 'CRM_HRLeaveAndAbsences_DAO_LeaveRequest',
          'localizable' => 0,
          'FKClassName' => 'CRM_Contact_DAO_Contact',
        ) ,
        'status_id' => array(
          'name' => 'status_id',
          'type' => CRM_Utils_Type::T_INT,
          'description' => 'One of the values of the Leave Request Status option group',
          'required' => true,
          'table_name' => 'civicrm_hrleaveandabsences_leave_request',
          'entity' => 'LeaveRequest',
          'bao' => 'CRM_HRLeaveAndAbsences_DAO_LeaveRequest',
          'localizable' => 0,
          'pseudoconstant' => array(
            'optionGroupName' => 'hrleaveandabsences_leave_request_status',
            'optionEditPath' => 'civicrm/admin/options/hrleaveandabsences_leave_request_status',
          )
        ) ,
        'from_date' => array(
          'name' => 'from_date',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => ts('From Date') ,
          'description' => 'The date and time the leave request starts.',
          'required' => true,
          'table_name' => 'civicrm_hrleaveandabsences_leave_request',
          'entity' => 'LeaveRequest',
          'bao' => 'CRM_HRLeaveAndAbsences_DAO_LeaveRequest',
          'localizable' => 0,
        ) ,
        'from_date_type' => array(
          'name' => 'from_date_type',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('From Date Type') ,
          'description' => 'One of the values of the Leave Request Day Type option group',
          'table_name' => 'civicrm_hrleaveandabsences_leave_request',
          'entity' => 'LeaveRequest',
          'bao' => 'CRM_HRLeaveAndAbsences_DAO_LeaveRequest',
          'localizable' => 0,
          'pseudoconstant' => array(
            'optionGroupName' => 'hrleaveandabsences_leave_request_day_type',
            'optionEditPath' => 'civicrm/admin/options/hrleaveandabsences_leave_request_day_type',
          )
        ) ,
        'to_date' => array(
          'name' => 'to_date',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => ts('To Date') ,
          'description' => 'The date and time the leave request ends',
          'required' => true,
          'table_name' => 'civicrm_hrleaveandabsences_leave_request',
          'entity' => 'LeaveRequest',
          'bao' => 'CRM_HRLeaveAndAbsences_DAO_LeaveRequest',
          'localizable' => 0,
        ) ,
        'to_date_type' => array(
          'name' => 'to_date_type',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('To Date Type') ,
          'description' => 'One of the values of the Leave Request Day Type option group',
          'table_name' => 'civicrm_hrleaveandabsences_leave_request',
          'entity' => 'LeaveRequest',
          'bao' => 'CRM_HRLeaveAndAbsences_DAO_LeaveRequest',
          'localizable' => 0,
          'pseudoconstant' => array(
            'optionGroupName' => 'hrleaveandabsences_leave_request_day_type',
            'optionEditPath' => 'civicrm/admin/options/hrleaveandabsences_leave_request_day_type',
          )
        ) ,
        'sickness_reason' => array(
          'name' => 'sickness_reason',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Sickness Reason') ,
          'description' => 'One of the values of the Sickness Reason option group',
          'maxlength' => 512,
          'size' => CRM_Utils_Type::HUGE,
          'table_name' => 'civicrm_hrleaveandabsences_leave_request',
          'entity' => 'LeaveRequest',
          'bao' => 'CRM_HRLeaveAndAbsences_DAO_LeaveRequest',
          'localizable' => 0,
          'pseudoconstant' => array(
            'optionGroupName' => 'hrleaveandabsences_sickness_reason',
            'optionEditPath' => 'civicrm/admin/options/hrleaveandabsences_sickness_reason',
          )
        ) ,
        'sickness_required_documents' => array(
          'name' => 'sickness_required_documents',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Sickness Required Documents') ,
          'description' => 'A list of values from the LeaveRequestRequiredDocument option group',
          'maxlength' => 10,
          'size' => CRM_Utils_Type::TWELVE,
          'table_name' => 'civicrm_hrleaveandabsences_leave_request',
          'entity' => 'LeaveRequest',
          'bao' => 'CRM_HRLeaveAndAbsences_DAO_LeaveRequest',
          'localizable' => 0,
        ) ,
        'toil_duration' => array(
          'name' => 'toil_duration',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('Toil Duration') ,
          'description' => 'The duration of the overtime work in minutes',
          'table_name' => 'civicrm_hrleaveandabsences_leave_request',
          'entity' => 'LeaveRequest',
          'bao' => 'CRM_HRLeaveAndAbsences_DAO_LeaveRequest',
          'localizable' => 0,
        ) ,
        'toil_to_accrue' => array(
          'name' => 'toil_to_accrue',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Toil To Accrue') ,
          'description' => 'The amount of days accrued for this toil request',
          'maxlength' => 512,
          'size' => CRM_Utils_Type::HUGE,
          'table_name' => 'civicrm_hrleaveandabsences_leave_request',
          'entity' => 'LeaveRequest',
          'bao' => 'CRM_HRLeaveAndAbsences_DAO_LeaveRequest',
          'localizable' => 0,
          'pseudoconstant' => array(
            'optionGroupName' => 'hrleaveandabsences_toil_amounts',
            'optionEditPath' => 'civicrm/admin/options/hrleaveandabsences_toil_amounts',
          )
        ) ,
        'toil_expiry_date' => array(
          'name' => 'toil_expiry_date',
          'type' => CRM_Utils_Type::T_DATE,
          'title' => ts('Toil Expiry Date') ,
          'description' => 'The expiry date of this TOIL Request. When null, it means it never expires.',
          'table_name' => 'civicrm_hrleaveandabsences_leave_request',
          'entity' => 'LeaveRequest',
          'bao' => 'CRM_HRLeaveAndAbsences_DAO_LeaveRequest',
          'localizable' => 0,
        ) ,
        'request_type' => array(
          'name' => 'request_type',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Request Type') ,
          'description' => 'The type of this request (leave, toil, sickness etc)',
          'required' => true,
          'maxlength' => 20,
          'size' => CRM_Utils_Type::MEDIUM,
          'table_name' => 'civicrm_hrleaveandabsences_leave_request',
          'entity' => 'LeaveRequest',
          'bao' => 'CRM_HRLeaveAndAbsences_DAO_LeaveRequest',
          'localizable' => 0,
        ) ,
        'is_deleted' => array(
          'name' => 'is_deleted',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'description' => 'Whether this leave request has been deleted or not',
          'table_name' => 'civicrm_hrleaveandabsences_leave_request',
          'entity' => 'LeaveRequest',
          'bao' => 'CRM_HRLeaveAndAbsences_DAO_LeaveRequest',
          'localizable' => 0,
        ) ,
        'from_date_amount' => array(
          'name' => 'from_date_amount',
          'type' => CRM_Utils_Type::T_MONEY,
          'title' => ts('From Date Amount') ,
          'description' => 'The balance change amount to be deducted for the leave request from date',
          'precision' => array(
            20,
            2
          ) ,
          'table_name' => 'civicrm_hrleaveandabsences_leave_request',
          'entity' => 'LeaveRequest',
          'bao' => 'CRM_HRLeaveAndAbsences_DAO_LeaveRequest',
          'localizable' => 0,
        ) ,
        'to_date_amount' => array(
          'name' => 'to_date_amount',
          'type' => CRM_Utils_Type::T_MONEY,
          'title' => ts('To Date Amount') ,
          'description' => 'The balance change amount to be deducted for the leave request to date',
          'precision' => array(
            20,
            2
          ) ,
          'table_name' => 'civicrm_hrleaveandabsences_leave_request',
          'entity' => 'LeaveRequest',
          'bao' => 'CRM_HRLeaveAndAbsences_DAO_LeaveRequest',
          'localizable' => 0,
        ) ,
      );
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
  static function &fieldKeys() {
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
  static function getTableName() {
    return self::$_tableName;
  }
  /**
   * Returns if this table needs to be logged
   *
   * @return boolean
   */
  function getLog() {
    return self::$_log;
  }
  /**
   * Returns the list of fields that can be imported
   *
   * @param bool $prefix
   *
   * @return array
   */
  static function &import($prefix = false) {
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'hrleaveandabsences_leave_request', $prefix, array());
    return $r;
  }
  /**
   * Returns the list of fields that can be exported
   *
   * @param bool $prefix
   *
   * @return array
   */
  static function &export($prefix = false) {
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'hrleaveandabsences_leave_request', $prefix, array());
    return $r;
  }
  /**
   * Returns the list of indices
   */
  public static function indices($localize = TRUE) {
    $indices = array();
    return ($localize && !empty($indices)) ? CRM_Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }
}

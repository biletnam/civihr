<?php

use CRM_HRLeaveAndAbsences_BAO_LeavePeriodEntitlement as LeavePeriodEntitlement;
use CRM_HRLeaveAndAbsences_BAO_LeaveRequestDate as LeaveRequestDate;
use CRM_HRLeaveAndAbsences_BAO_LeaveRequest as LeaveRequest;

class CRM_HRLeaveAndAbsences_BAO_LeaveBalanceChange extends CRM_HRLeaveAndAbsences_DAO_LeaveBalanceChange {

  const SOURCE_ENTITLEMENT = 'entitlement';
  const SOURCE_LEAVE_REQUEST_DAY = 'leave_request_day';

  /**
   * Create a new LeaveBalanceChange based on array-data
   *
   * @param array $params key-value pairs
   *
   * @return CRM_HRLeaveAndAbsences_DAO_LeaveBalanceChange|NULL
   */
  public static function create($params) {
    $entityName = 'LeaveBalanceChange';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new self();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  }

  /**
   * Returns the sum of all balance changes between the given LeavePeriodEntitlement
   * dates.
   *
   * This method can also sum only balance changes caused by leave requests with
   * specific statuses. For this, one can pass an array of statuses as the
   * $leaveRequestStatus parameter.
   *
   * Note: the balance changes linked to the given LeavePeriodEntitlement, that
   * is source_id == entitlement->id and source_type == 'entitlement', will also
   * be included in the sum.
   *
   * @param \CRM_HRLeaveAndAbsences_BAO_LeavePeriodEntitlement $periodEntitlement
   *    The LeavePeriodEntitlement to get the Balance to
   * @param array $leaveRequestStatus
   *    An array of values from Leave Request Status option list
   *
   * @return float
   */
  public static function getBalanceForEntitlement(LeavePeriodEntitlement $periodEntitlement, $leaveRequestStatus = []) {
    $balanceChangeTable = self::getTableName();
    $leaveRequestDateTable = LeaveRequestDate::getTableName();
    $leaveRequestTable = LeaveRequest::getTableName();

    list($startDate, $endDate) = $periodEntitlement->getStartAndEndDates();
    $contactID = $periodEntitlement->getContactIDFromContract();

    $whereLeaveRequestStatus = '';
    if(is_array($leaveRequestStatus) && !empty($leaveRequestStatus)) {
      array_walk($leaveRequestStatus, 'intval');
      $whereLeaveRequestStatus = ' AND leave_request.status_id IN('. implode(', ', $leaveRequestStatus) .')';
    }

    $query = "
      SELECT SUM(leave_balance_change.amount) balance
      FROM {$balanceChangeTable} leave_balance_change
      LEFT JOIN {$leaveRequestDateTable} leave_request_date 
             ON leave_balance_change.source_id = leave_request_date.id AND 
                leave_balance_change.source_type = '". self::SOURCE_LEAVE_REQUEST_DAY ."'
      LEFT JOIN {$leaveRequestTable} leave_request ON leave_request_date.leave_request_id = leave_request.id
      WHERE (
              leave_request_date.date >= '{$startDate}' AND 
              leave_request_date.date <= '{$endDate}' AND
              leave_request.type_id = {$periodEntitlement->type_id} AND
              leave_request.contact_id = {$contactID} 
              $whereLeaveRequestStatus
            )
            OR
            (
              leave_balance_change.source_id = {$periodEntitlement->id} AND 
              leave_balance_change.source_type = '" . self::SOURCE_ENTITLEMENT . "'
            )
    ";

    $result = CRM_Core_DAO::executeQuery($query);
    $result->fetch();

    return (float)$result->balance;
  }

  /**
   * Returns the LeaveBalanceChange instances that are part of the
   * LeavePeriodEntitlement with the given ID.
   *
   * The Breakdown is made of the balance changes representing the parts that,
   * together, make the period entitlement. They are: The Leave, the Brought
   * Forward and the Public Holidays. These are all balance changes, where the
   * source_id is the LeavePeriodEntitlement's ID and source_type is equal to
   * "entitlement", since they're are created during the entitlement calculation.
   *
   * @param int $entitlementID
   *   The ID of the LeavePeriodEntitlement to get the Breakdown to
   *
   * @return CRM_HRLeaveAndAbsences_BAO_LeaveBalanceChange[]
   */
  public static function getBreakdownBalanceChangesForEntitlement($entitlementID) {
    $entitlementID = (int)$entitlementID;
    $balanceChangeTable = self::getTableName();

    $query = "
      SELECT *
      FROM {$balanceChangeTable}
      WHERE source_id = {$entitlementID} AND
            source_type = '" . self::SOURCE_ENTITLEMENT . "' AND 
            expired_balance_change_id IS NULL
      ORDER BY id
    ";

    $changes = [];

    $result = CRM_Core_DAO::executeQuery($query, [], true, self::class);
    while($result->fetch()) {
      $changes[] = clone $result;
    }

    return $changes;
  }

  /**
   * Returns the balance for the Balance Changes that are part of the
   * LeavePeriodEntitlement with the given ID.
   *
   * This basically gets the output of getBreakdownBalanceChangesForEntitlement()
   * and sums up the amount of the returned LeaveBalanceChange instances.
   *
   * @see CRM_HRLeaveAndAbsences_BAO_LeaveBalanceChange::getBreakdownBalanceChangesForEntitlement()
   *
   * @param int $entitlementID
   *    The ID of the LeavePeriodEntitlement to get the Breakdown Balance to
   *
   * @return float
   */
  public static function getBreakdownBalanceForEntitlement($entitlementID) {
    $balanceChanges = self::getBreakdownBalanceChangesForEntitlement($entitlementID);

    $balance = 0.0;
    foreach($balanceChanges as $balanceChange) {
      $balance += (float)$balanceChange->amount;
    }

    return $balance;
  }

  /**
   * Returns the sum of all balance changes generated by LeaveRequests on
   * LeavePeriodEntitlement with the given ID.
   *
   * This method can also sum only balance changes caused by leave requests with
   * specific statuses. For this, one can pass an array of statuses as the
   * $leaveRequestStatus parameter.
   *
   * Since balance changes caused by LeaveRequests are negative, this method
   * will return a negative number.
   *
   * It's also possible to get the balance only for leave requests taken between
   * a given date range. For this, one can use the $dateLimit and $dateStart params.
   *
   * @param \CRM_HRLeaveAndAbsences_BAO_LeavePeriodEntitlement $periodEntitlement
   * @param array $leaveRequestStatus
   *    An array of values from Leave Request Status option list
   * @param \DateTime $dateLimit
   *    When given, will make the method count only days taken as leave up to this date
   * @param \DateTime $dateStart
   *    When given, will make the method count only days taken as leave starting from this date
   *
   * @return float
   * @internal param int $entitlementID The ID of the entitlement to get the balance*    The ID of the entitlement to get the balance
   */
  public static function getLeaveRequestBalanceForEntitlement(
    LeavePeriodEntitlement $periodEntitlement,
    $leaveRequestStatus = [],
    DateTime $dateLimit = NULL,
    DateTime $dateStart = NULL
  ) {

    list($startDate, $endDate) = $periodEntitlement->getStartAndEndDates();
    $contactID = $periodEntitlement->getContactIDFromContract();

    $balanceChangeTable = self::getTableName();
    $leaveRequestDateTable = LeaveRequestDate::getTableName();
    $leaveRequestTable = LeaveRequest::getTableName();

    $query = "
      SELECT SUM(leave_balance_change.amount) balance
      FROM {$balanceChangeTable} leave_balance_change
      INNER JOIN {$leaveRequestDateTable} leave_request_date 
              ON leave_balance_change.source_id = leave_request_date.id AND 
                 leave_balance_change.source_type = '" . self::SOURCE_LEAVE_REQUEST_DAY . "'
      INNER JOIN {$leaveRequestTable} leave_request ON leave_request_date.leave_request_id = leave_request.id
      WHERE leave_request_date.date >= '{$startDate}' AND 
            leave_request_date.date <= '{$endDate}' AND
            leave_request.type_id = {$periodEntitlement->type_id} AND
            leave_request.contact_id = {$contactID} 
    ";

    if(is_array($leaveRequestStatus) && !empty($leaveRequestStatus)) {
      array_walk($leaveRequestStatus, 'intval');
      $query .= ' AND leave_request.status_id IN('. implode(', ', $leaveRequestStatus) .')';
    }

    if($dateLimit) {
      $query .= " AND leave_request_date.date <= '{$dateLimit->format('Y-m-d')}'";
    }

    if($dateStart) {
      $query .= " AND leave_request_date.date >= '{$dateStart->format('Y-m-d')}'";
    }

    $result = CRM_Core_DAO::executeQuery($query);
    $result->fetch();

    return (float)$result->balance;
  }

  /**
   * This method checks every leave balance change record with an expiry_date in
   * the past and that still don't have a record for the expired days (that is,
   * a balance change record of this same type and with an expired_balance_change_id
   * pointing to the expired record), and creates it.
   *
   * @return int The number of records created
   */
  public static function createExpirationRecords() {
    $numberOfRecordsCreated = 0;

    $tableName = self::getTableName();
    $query = "
      SELECT balance_to_expire.*
      FROM {$tableName} balance_to_expire
      LEFT JOIN {$tableName} expired_balance_change
             ON balance_to_expire.id = expired_balance_change.expired_balance_change_id
      WHERE balance_to_expire.expiry_date IS NOT NULL AND
            balance_to_expire.expiry_date < CURDATE() AND
            balance_to_expire.expired_balance_change_id IS NULL AND
            expired_balance_change.id IS NULL
      ORDER BY balance_to_expire.source_id ASC, balance_to_expire.expiry_date ASC
    ";

    $startDate = null;
    $sourceID = null;
    $sourceType = null;

    $dao = CRM_Core_DAO::executeQuery($query, [], true, self::class);
    while($dao->fetch()) {
      if($dao->source_id != $sourceID && $dao->source_type != $sourceType) {
        $startDate = null;
        $sourceID = $dao->source_id;
        $sourceType = $dao->source_type;
      }

      $expiredAmount = self::calculateExpiredAmount($dao, $startDate);
      // Since these days should be deducted from the entitlement,
      // We need to store the expired amount as a negative number
      $expiredAmount *= -1;

      self::create([
        'source_id' => $dao->source_id,
        'source_type' => $dao->source_type,
        'type_id' => $dao->type_id,
        'amount' => $expiredAmount,
        'expiration_date' => date('YmdHis', strtotime($dao->expiry_date)),
        'expired_balance_change_id' => $dao->id
      ]);

      $numberOfRecordsCreated++;
      $startDate = (new DateTime($dao->expiry_date))->modify('+1 day');
    }
    return $numberOfRecordsCreated;
  }

  /**
   * This method calculates how many days of the given LeaveBalanceChange have
   * expired.
   *
   * To do this, we get the leave request balance (in other words, the number of
   * days taken as leave) up to the balance change expiry date and subtracts it
   * from the balance change amount.
   *
   * This method also supports a $startDate param. When given, the method will
   * calculate the expired amount counting only days taken as leave between the
   * $startDate and the balance change expiry date.
   *
   * @param \CRM_HRLeaveAndAbsences_BAO_LeaveBalanceChange $balanceChange
   * @param \DateTime|NULL $startDate
   *
   * @return float
   */
  private static function calculateExpiredAmount(
    CRM_HRLeaveAndAbsences_BAO_LeaveBalanceChange $balanceChange,
    DateTime $startDate = NULL
  ) {
    $leaveRequestStatus = array_flip(LeaveRequest::buildOptions('status_id'));
    $approvedStatuses = [
      $leaveRequestStatus['Approved'],
      $leaveRequestStatus['Admin Approved']
    ];

    $balanceChange->amount = (float)$balanceChange->amount;

    $entitlement = LeavePeriodEntitlement::findById($balanceChange->source_id);
    $expiredAmount = self::getLeaveRequestBalanceForEntitlement(
      $entitlement,
      $approvedStatuses,
      new DateTime($balanceChange->expiry_date),
      $startDate
    );

    // Since the the Leave Request Balance is negative, when we add it
    // to the amount we're actually subtracting the value
    return $balanceChange->amount + $expiredAmount;
  }
}

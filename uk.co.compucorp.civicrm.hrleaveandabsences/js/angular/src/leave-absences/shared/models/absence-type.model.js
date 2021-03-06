/* eslint-env amd */

define([
  'leave-absences/shared/modules/models',
  'common/models/model',
  'leave-absences/shared/apis/absence-type.api',
  'leave-absences/shared/instances/absence-type.instance'
], function (models) {
  'use strict';

  models.factory('AbsenceType', [
    '$log', 'Model', 'AbsenceTypeAPI', 'AbsenceTypeInstance',
    function ($log, Model, absenceTypeAPI, instance) {
      $log.debug('AbsenceType');

      return Model.extend({
        /**
         * Calls the all() method of the AbsenceType API, and returns an
         * AbsenceTypeInstance for each absenceType.
         *
         * @param  {Object} params  matches the api endpoint params (title, weight etc)
         * @return {Promise}
         */
        all: function (params) {
          return absenceTypeAPI.all(params)
            .then(function (absenceTypes) {
              return absenceTypes.map(function (absenceType) {
                return instance.init(absenceType, true);
              });
            });
        },

        /**
         * Calls the calculateToilExpiryDate() method of the AbsenceType API
         *
         * @param  {string} absenceTypeID
         * @param  {Object} date
         * @param  {Object} params
         * @return {Promise}
         */
        calculateToilExpiryDate: function (absenceTypeID, date, params) {
          return absenceTypeAPI.calculateToilExpiryDate(absenceTypeID, date, params);
        },

        /**
         * Determines if the absence type can expire by querying if
         * the expiration unit and duration are not null.
         *
         * @param   {string} absenceTypeId
         * @return  {Promise}
         */
        canExpire: function (absenceTypeId) {
          return absenceTypeAPI.all({
            accrual_expiration_unit: { 'IS NOT NULL': 1 },
            accrual_expiration_duration: { 'IS NOT NULL': 1 },
            allow_accruals_request: 1,
            id: absenceTypeId,
            options: { limit: 1 },
            return: ['id']
          })
          .then(function (results) {
            return results.length > 0;
          });
        }
      });
    }
  ]);
});

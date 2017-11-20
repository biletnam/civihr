/* eslint-env amd, jasmine */

define([
  'job-contract/app'
], function () {
  'use strict';

  describe('FormPayCtrl', function () {
    var $controller, $rootScope, $scope, FormatCurrencyService, settings, deferred;

    beforeEach(module('hrjc'));

    beforeEach(inject(function (_$controller_, _$rootScope_, _settings_, _FormatCurrencyService_, $q) {
      $controller = _$controller_;
      $rootScope = _$rootScope_;
      FormatCurrencyService = _FormatCurrencyService_;
      settings = _settings_;
      deferred = $q.defer();

      spyOn(FormatCurrencyService, 'removeCharacters').and.callFake(function () {
        deferred = $q.defer();
        deferred.resolve(45000);

        return deferred.promise;
      });

      spyOn(FormatCurrencyService, 'addSeperators').and.callFake(function () {
        deferred = $q.defer();
        deferred.resolve('45,000');

        return deferred.promise;
      });

      initController();
    }));

    describe('calcAnnualPayEst', function () {
      beforeEach(function () {
        $scope.calcAnnualPayEst();
        $scope.$apply();
      });

      it('formats annual pay amount', function () {
        expect($scope.entity.pay.pay_annualized_est).toBe('45,000');
      });
    });

    describe('calcBenefitsPerCycleNet', function () {
      beforeEach(function () {
        $scope.calcBenefitsPerCycleNet();
        $scope.$apply();
      });

      it('formats benefits per cycle pay', function () {
        expect($scope.entity.pay.benefits_per_cycle_net).toBe('45,000');
      });
    });

    describe('calcPayPerCycleGross', function () {
      beforeEach(function () {
        $scope.calcPayPerCycleGross();
        $scope.$apply();
      });

      it('formats pay per cycle gross', function () {
        expect($scope.entity.pay.pay_per_cycle_gross).toBe('45,000');
      });
    });

    describe('calcPayPerCycleNet', function () {
      beforeEach(function () {
        $scope.calcPayPerCycleNet();
        $scope.$apply();
      });

      it('formats the pay per cycle', function () {
        expect($scope.entity.pay.pay_per_cycle_net).toBe('45,000');
      });
    });

    /**
     * Initializes the form controller
     */
    function initController () {
      $scope = $rootScope.$new();
      $scope.utils = {
        payScaleGrade: 'weekly'
      };
      $scope.entity = {
        'pay': {
          'is_paid': 1,
          'pay_is_auto_est': true,
          'pay_annualized_est': 0,
          'annual_benefits': 0,
          'annual_deductions': 0,
          'pay_amount': 45000
        }
      };
      $controller('FormPayCtrl', {
        $scope: $scope,
        settings: settings,
        FormatCurrencyService:
        FormatCurrencyService
      });
    }
  });
});

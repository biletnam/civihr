define(['common/angular'], function(angular) { 'use strict'; return angular.module("common.templates", []).run(["$templateCache", function($templateCache) {$templateCache.put("dialog.html","<div class=\"modal-header\">\n    <button type=\"button\" class=\"close\" ng-click=\"cancel()\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>\n    <h2 class=\"modal-title\">{{title}}</h2>\n</div>\n<div class=\"modal-body\">\n    {{msg}}\n</div>\n<div class=\"modal-footer\">\n    <button type=\"button\" class=\"btn btn-secondary-outline text-uppercase\" ng-click=\"cancel()\">{{copyCancel}}</button>\n    <button type=\"button\" class=\"btn {{classConfirm}} text-uppercase\" ng-click=\"confirm()\">{{copyConfirm}}</button>\n</div>\n");
$templateCache.put("loading.html","<div>\n    <div prevent-animations class=\"crm_spinner\" ng-show=\"!show\">\n        <span class=\"crm_spinner__img\"></span>\n    </div>\n    <div ng-transclude ng-show=\"show\"></div>\n</div>\n");
$templateCache.put("angular-date/datepickerPopup.html","<ul class=\"dropdown-menu datepicker-popup\" ng-style=\"{display: (isOpen && \'block\') || \'none\', top: popupPosition.top+\'px\', left: popupPosition.left+\'px\'}\" ng-keydown=\"keydown($event)\">\n	<li ng-transclude></li>\n</ul>\n");
$templateCache.put("angular-date/day.html","<table role=\"grid\" aria-labelledby=\"{{uniqueId}}-title\" aria-activedescendant=\"{{activeDateId}}\">\n    <thead>\n    <tr>\n        <th>\n            <button type=\"button\" class=\"btn btn-default btn-sm pull-left\" ng-click=\"move(-1)\" tabindex=\"-1\">\n                <strong> < </strong>\n            </button>\n        </th>\n        <th colspan=\"{{5 + showWeeks}}\">\n            <button id=\"{{uniqueId}}-title\" role=\"heading\" aria-live=\"assertive\" aria-atomic=\"true\" type=\"button\"\n                    class=\"btn btn-default btn-sm\" ng-click=\"toggleMode()\" tabindex=\"-1\" style=\"width:100%;\"><strong>{{title}}</strong>\n            </button>\n        </th>\n        <th>\n            <button type=\"button\" class=\"btn btn-default btn-sm pull-right\" ng-click=\"move(1)\" tabindex=\"-1\"><strong> > </strong></button>\n        </th>\n    </tr>\n\n    <tr style=\"border-top: 1px solid #DDD\">\n        <th ng-show=\"showWeeks\" class=\"text-center\"></th>\n        <th ng-repeat=\"label in labels track by $index\" class=\"text-center\">\n            <small aria-label=\"{{label.full}}\">{{label.abbr[0]}}</small>\n        </th>\n    </tr>\n\n    </thead>\n\n    <tbody>\n    <style>\n        tr td.text-center span.out-of-scope{\n            color: #CCCCCC;\n        }\n    </style>\n    <tr ng-repeat=\"row in rows track by $index\">\n        <td ng-show=\"showWeeks\" class=\"text-center h6\"><em>{{ weekNumbers[$index] }}</em></td>\n        <td ng-repeat=\"dt in row track by dt.date\" class=\"text-center\" role=\"gridcell\" id=\"{{dt.uid}}\"\n            aria-disabled=\"{{!!dt.disabled}}\">\n            <button type=\"button\"\n                    style=\"width:100%;\"\n                    class=\"btn btn-default btn-sm\"\n                    ng-class=\"{\n                        \'btn-info\': dt.selected,\n                        active: isActive(dt)\n                    }\"\n                    ng-click=\"select(dt.date)\"\n                    ng-disabled=\"dt.disabled\"\n                    tabindex=\"-1\">\n<!--\'text-muted\': dt.secondary, -->\n                <span ng-class=\"{\'out-of-scope\': dt.secondary, \'text-info\': dt.current}\">{{dt.label}}</span>\n            </button>\n        </td>\n    </tr>\n    </tbody>\n</table>\n");
$templateCache.put("contact-actions/contact-actions.html","<div id=\"civihr-ui-select-contact\">\n  <div class=\"refine-search row\" ng-if=\"$ctrl.refineSearchVisible\">\n    <div class=\"col-xs-12 refine-search__placeholder\">\n      Start typing a name or email...\n    </div>\n    <div class=\"col-xs-12 col-sm-6\">\n      <div class=\"crm_custom-select crm_custom-select--full\">\n        <select\n          ng-required class=\"refine-search__dropdown form-control\"\n          ng-model=\"$ctrl.refineSearch.selected.field\"\n          ng-change=\"$ctrl.refineSearch.availableOptions.refresh()\"\n          ng-options=\"value.label for value in $ctrl.refineSearch.availableFields\">\n          <option value=\"\">Refine search...</option>\n        </select>\n        <span class=\"crm_custom-select__arrow\"></span>\n      </div>\n    </div>\n    <div class=\"col-xs-12 col-sm-6\">\n      <div class=\"loading-indicator\" ng-hide=\"$ctrl.refineSearch.availableOptions.options\"></div>\n      <div class=\"crm_custom-select crm_custom-select--full\" ng-show=\"$ctrl.refineSearch.availableOptions.options.length\">\n        <select class=\"refine-search__dropdown form-control\"\n          ng-model=\"$ctrl.refineSearch.selected.option\"\n          ng-options=\"option.value for option in $ctrl.refineSearch.availableOptions.options\">\n          <option value=\"\">- select -</option>\n        </select>\n        <span class=\"crm_custom-select__arrow\"></span>\n      </div>\n    </div>\n  </div>\n  <div class=\"button-list\" ng-class=\"{\'button-list--with-upper-margin\': $ctrl.refineSearchVisible}\">\n    <a class=\"button-list__button\" ng-click=\"$ctrl.showNewHouseholdModal()\">\n      <i class=\"crm-i fa-home\"></i> New Household\n    </a>\n    <a class=\"button-list__button\" ng-click=\"$ctrl.showNewIndividualModal()\">\n      <i class=\"crm-i fa-user\"></i> New Individual\n    </a>\n    <a class=\"button-list__button\" ng-click=\"$ctrl.showNewOrganizationModal()\">\n      <i class=\"crm-i fa-building\"></i> New Organization\n    </a>\n  </div>\n</div>\n");
$templateCache.put("civihr-ui-select/choices.tpl.html","<ul tabindex=\"-1\" class=\"ui-select-choices ui-select-choices-content select2-results\">\n  <li class=\"ui-select-choices-group\" ng-class=\"{\'select2-result-with-children\': $select.choiceGrouped($group) }\">\n    <div ng-show=\"$select.choiceGrouped($group)\" class=\"ui-select-choices-group-label select2-result-label\" ng-bind=\"$group.name\"></div>\n    <ul role=\"listbox\" class=\"result-list\"\n      id=\"ui-select-choices-{{ $select.generatedId }}\" ng-class=\"{\'select2-result-sub\': $select.choiceGrouped($group), \'select2-result-single\': !$select.choiceGrouped($group) }\">\n      <li role=\"option\" ng-attr-id=\"ui-select-choices-row-{{ $select.generatedId }}-{{$index}}\" class=\"ui-select-choices-row\" ng-class=\"{\'select2-highlighted\': $select.isActive(this), \'select2-disabled\': $select.isDisabled(this)}\">\n        <div class=\"select2-result-label ui-select-choices-row-inner\" ng-class=\"{\'result-list__contact-item\': $select.contactList}\"></div>\n      </li>\n    </ul>\n  </li>\n</ul>\n");
$templateCache.put("civihr-ui-select/match-multiple.tpl.html","<span class=\"ui-select-match\">\n  <li class=\"ui-select-match-item select2-search-choice\" ng-repeat=\"$item in $select.selected\"\n      ng-class=\"{\'select2-search-choice-focus\':$selectMultiple.activeMatchIndex === $index, \'select2-locked\':$select.isLocked(this, $index)}\"\n      ui-select-sort=\"$select.selected\">\n      <span uis-transclude-append></span>\n      <a href=\"javascript:;\" class=\"ui-select-match-close select2-search-choice-close\" ng-click=\"$selectMultiple.removeChoice($index)\" tabindex=\"-1\"></a>\n  </li>\n</span>\n");
$templateCache.put("civihr-ui-select/match.tpl.html","<a class=\"select2-choice ui-select-match\"\n   ng-class=\"{\'select2-default\': $select.isEmpty()}\"\n   ng-click=\"$select.toggle($event)\" aria-label=\"{{ $select.baseTitle }} select\">\n  <span ng-show=\"$select.isEmpty()\" class=\"select2-chosen\" ng-class=\"{\'empty\': $select.isEmpty()}\">{{$select.contactList ? \'-select-\' : $select.placeholder}}</span>\n  <span ng-hide=\"$select.isEmpty()\" class=\"select2-chosen\" ng-transclude></span>\n  <abbr ng-if=\"$select.allowClear && !$select.isEmpty()\" class=\"select2-search-choice-close\" ng-click=\"$select.clear($event)\"></abbr>\n  <span class=\"select2-arrow ui-select-toggle\" ng-class=\"{\'search-enabled\': $select.searchEnabled}\"><b></b></span>\n</a>\n");
$templateCache.put("civihr-ui-select/select-contacts-multiple.tpl.html","<div class=\"civihr-ui-select ui-select-container ui-select-multiple select2 select2-container select2-container-multi\"\n     ng-class=\"{\'form-control\': !$select.open, \'select2-container-active select2-dropdown-open open\': $select.open,\n                \'select2-container-disabled\': $select.disabled,\n                \'search-enabled\': $select.searchEnabled,\n                \'contact-lookup\': $select.contactList}\">\n  <div class=\"civihr-ui-select__content-multiple\">\n    <ul class=\"select2-choices\"  ng-class=\"{\'search-enabled\': $select.searchEnabled}\" >\n      <span class=\"ui-select-match\"></span>\n      <li class=\"select2-search-field\">\n        <input\n          type=\"search\"\n          autocomplete=\"off\"\n          autocorrect=\"off\"\n          autocapitalize=\"off\"\n          spellcheck=\"false\"\n          role=\"combobox\"\n          aria-expanded=\"true\"\n          aria-owns=\"ui-select-choices-{{ $select.generatedId }}\"\n          aria-label=\"{{ $select.baseTitle }}\"\n          aria-activedescendant=\"ui-select-choices-row-{{ $select.generatedId }}-{{ $select.activeIndex }}\"\n          class=\"select2-input ui-select-search\"\n          placeholder=\"{{$selectMultiple.getPlaceholder()}}\"\n          ng-disabled=\"$select.disabled\"\n          ng-model=\"$select.search\"\n          ng-click=\"$select.activate()\"\n          ondrop=\"return false;\">\n      </li>\n    </ul>\n\n    <contact-actions ng-show=\"$select.open && $select.search.length === 0\"></contact-actions>\n\n    <div class=\"ui-select-dropdown select2-drop select2-with-searchbox select2-drop-active\"\n         ng-class=\"{\'select2-display-none\': !$select.open || $select.items.length === 0}\">\n      <div class=\"ui-select-choices\"></div>\n    </div>\n  </div>\n</div>\n");
$templateCache.put("civihr-ui-select/select-contacts.tpl.html","<div class=\"civihr-ui-select ui-select-container select2 select2-container\"\n     ng-class=\"{\'form-control\': !$select.open, \'select2-container-active select2-dropdown-open open\': $select.open,\n                \'select2-container-disabled\': $select.disabled,\n                \'select2-container-active\': $select.focus,\n                \'select2-allowclear\': $select.allowClear && !$select.isEmpty(),\n                \'contact-lookup\': $select.contactList}\">\n  <div class=\"ui-select-match\"></div>\n  <div class=\"ui-select-dropdown select2-drop select2-with-searchbox select2-drop-active\"\n       ng-class=\"{\'select2-display-none\': !$select.open}\">\n    <div class=\"select2-search civihr-ui-select__content\" ng-show=\"$select.searchEnabled\">\n      <input type=\"search\" autocomplete=\"off\" autocorrect=\"off\" autocapitalize=\"off\" spellcheck=\"false\"\n       role=\"combobox\"\n       aria-expanded=\"true\"\n       aria-owns=\"ui-select-choices-{{ $select.generatedId }}\"\n       aria-label=\"{{ $select.baseTitle }}\"\n       aria-activedescendant=\"ui-select-choices-row-{{ $select.generatedId }}-{{ $select.activeIndex }}\"\n             class=\"ui-select-search select2-input\"\n             ng-model=\"$select.search\">\n    </div>\n\n    <contact-actions ng-show=\"$select.search.length === 0\"></contact-actions>\n    <div class=\"ui-select-choices\"></div>\n\n  </div>\n</div>\n");
$templateCache.put("civihr-ui-select/select-multiple.tpl.html","<div class=\"civihr-ui-select ui-select-container ui-select-multiple select2 select2-container select2-container-multi\"\n     ng-class=\"{\'form-control\': !$select.open, \'select2-container-active select2-dropdown-open open\': $select.open,\n                \'select2-container-disabled\': $select.disabled,\n                \'search-enabled\': $select.searchEnabled}\">\n  <div class=\"civihr-ui-select__content-multiple\">\n    <ul class=\"select2-choices\"  ng-class=\"{\'search-enabled\': $select.searchEnabled}\" >\n      <span class=\"ui-select-match\"></span>\n      <li class=\"select2-search-field\">\n        <input\n          type=\"search\"\n          autocomplete=\"off\"\n          autocorrect=\"off\"\n          autocapitalize=\"off\"\n          spellcheck=\"false\"\n          role=\"combobox\"\n          aria-expanded=\"true\"\n          aria-owns=\"ui-select-choices-{{ $select.generatedId }}\"\n          aria-label=\"{{ $select.baseTitle }}\"\n          aria-activedescendant=\"ui-select-choices-row-{{ $select.generatedId }}-{{ $select.activeIndex }}\"\n          class=\"select2-input ui-select-search\"\n          placeholder=\"{{$selectMultiple.getPlaceholder()}}\"\n          ng-disabled=\"$select.disabled\"\n          ng-model=\"$select.search\"\n          ng-click=\"$select.activate()\"\n          ondrop=\"return false;\">\n      </li>\n    </ul>\n    <div class=\"ui-select-dropdown select2-drop select2-with-searchbox select2-drop-active\"\n         ng-class=\"{\'select2-display-none\': !$select.open || $select.items.length === 0}\">\n      <div class=\"ui-select-choices\"></div>\n    </div>\n  </div>\n</div>\n");
$templateCache.put("civihr-ui-select/select.tpl.html","<div class=\"civihr-ui-select ui-select-container select2 select2-container\"\n     ng-class=\"{\'form-control\': !$select.open, \'select2-container-active select2-dropdown-open open\': $select.open,\n                \'select2-container-disabled\': $select.disabled,\n                \'select2-container-active\': $select.focus,\n                \'select2-allowclear\': $select.allowClear && !$select.isEmpty()}\">\n  <div class=\"ui-select-match\"></div>\n  <div class=\"ui-select-dropdown select2-drop select2-with-searchbox select2-drop-active\"\n       ng-class=\"{\'select2-display-none\': !$select.open}\">\n    <div class=\"select2-search civihr-ui-select__content\" ng-show=\"$select.searchEnabled\">\n      <input type=\"search\" autocomplete=\"off\" autocorrect=\"off\" autocapitalize=\"off\" spellcheck=\"false\"\n       role=\"combobox\"\n       aria-expanded=\"true\"\n       aria-owns=\"ui-select-choices-{{ $select.generatedId }}\"\n       aria-label=\"{{ $select.baseTitle }}\"\n       aria-activedescendant=\"ui-select-choices-row-{{ $select.generatedId }}-{{ $select.activeIndex }}\"\n             class=\"ui-select-search select2-input\"\n             ng-model=\"$select.search\">\n    </div>\n    <div class=\"ui-select-choices\"></div>\n  </div>\n</div>\n");
$templateCache.put("contact-actions/modals/form.html","<form class=\"form-horizontal\" name=\"modalFrm\">\n  <div class=\"modal-header\">\n    <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\" ng-click=\"$ctrl.cancel()\">\n      <span aria-hidden=\"true\">&times;</span>\n    </button>\n    <h2 class=\"modal-title\">{{$ctrl.title}}</h2>\n  </div>\n  <div class=\"modal-body\">\n    <crm-loading show=\"!$ctrl.loading\">\n      <section class=\"container-fluid\">\n        <div class=\"alert alert-danger\" ng-show=\"$ctrl.errorMsg\">\n          {{$ctrl.errorMsg}}\n        </div>\n\n        <div class=\"form-group has-feedback\"\n          ng-repeat=\"field in $ctrl.formFields | orderBy: field.weight\"\n          ng-class=\"{\'has-error\': {{::\'modalFrm.\'+ field.field_name +\'.$dirty\'}} && {{::\'modalFrm.\'+ field.field_name +\'.$invalid\'}}}\">\n          <label for=\"{{::field.id}}\" class=\"col-xs-12 col-sm-4 control-label\">\n            {{::field.label}}\n          </label>\n          <div class=\"col-xs-12 col-sm-8\">\n            <input type=\"text\" ng-if=\"field.field_name !== \'email\'\"\n              name=\"{{::field.field_name}}\" id=\"{{::field.id}}\" ng-model=\"field.value\"\n              class=\"form-control\" ng-required=\"field.is_required === \'1\'\">\n            <input type=\"email\" ng-if=\"field.field_name === \'email\'\"\n              name=\"{{::field.field_name}}\" id=\"{{::field.id}}\" ng-model=\"field.value\"\n              class=\"form-control\" ng-required=\"field.is_required === \'1\'\">\n            <span class=\"label label-danger\" ng-show=\"{{::\'modalFrm.\'+ field.field_name +\'.$dirty\'}} && {{::\'modalFrm.\'+ field.field_name +\'.$error.required\'}}\">\n              Field is required.\n            </span>\n            <span ng-if=\"field.field_name === \'email\'\" class=\"label label-danger\" ng-show=\"modalFrm.email.$error.email\">\n              Invalid email address.\n            </span>\n          </div>\n        </div>\n      </section>\n    </crm-loading>\n  </div>\n  <div class=\"modal-footer\">\n    <button type=\"button\" class=\"btn btn-secondary-outline text-uppercase\" data-dismiss=\"modal\" ng-click=\"$ctrl.cancel()\">\n      cancel\n    </button>\n    <button type=\"button\" class=\"btn btn-primary text-uppercase\" ng-click=\"$ctrl.submit()\" ng-disabled=\"!modalFrm.$valid\">\n      save\n    </button>\n  </div>\n</form>\n");}]);});
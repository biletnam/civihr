<?php

require_once 'hrcore.civix.php';

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference as Reference;
use CRM_HRCore_Service_DrupalUserService as DrupalUserService;
use CRM_HRCore_Service_DrupalRoleService as DrupalRoleService;
use CRM_HRCore_SearchTask_ContactFormSearchTaskAdder as ContactFormSearchTaskAdder;
use CRM_HRCore_HookListener_EventBased_OnConfig as OnConfigListener;
use CRM_HRCore_HookListener_EventBased_OnInstall as OnInstallListener;
use CRM_HRCore_HookListener_EventBased_OnUninstall as OnUninstallListener;
use CRM_HRCore_HookListener_EventBased_OnEnable as OnEnableListener;
use CRM_HRCore_HookListener_EventBased_OnDisable as OnDisableListener;
use CRM_HRCore_HookListener_EventBased_OnAlterMenu as OnAlterMenuListener;
use CRM_HRCore_HookListener_EventBased_OnTabset as OnTabsetListener;
use CRM_HRCore_HookListener_ObjectBased_Page_ContactDashboard as ContactDashboardPageHookListener;
use CRM_HRCore_HookListener_ObjectBased_Page_ContactSummary as ContactSummaryPageHookListener;
use CRM_HRCore_HookListener_ObjectBased_Page_CaseDashboard as CaseDashboardPageHookListener;
use CRM_HRCore_HookListener_ObjectBased_Page_Dashlet_CaseDashboard as CaseDashboardPageDashletHookListener;
use CRM_HRCore_HookListener_ObjectBased_Form_Contact as ContactFormHookListener;
use CRM_HRCore_HookListener_ObjectBased_Form_ContactImportMapField as ContactImportMapFieldFormHookListener;
use CRM_HRCore_HookListener_ObjectBased_Form_ProfileEdit as ProfileEditFormHookListener;
use CRM_HRCore_HookListener_ObjectBased_Form_Admin_Extensions as ExtensionsFormAdminHookListener;
use CRM_HRCore_HookListener_ObjectBased_Form_Admin_Options as OptionsFormAdminHookListener;
use CRM_HRCore_HookListener_ObjectBased_Form_Admin_Localization as LocalizationFormAdminHookListener;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function hrcore_civicrm_config(&$config) {
  _hrcore_civix_civicrm_config($config);

  (new OnConfigListener())->handle($config);
}

/**
 * Implements hook_civicrm_searchTasks().
 *
 * @param string $objectName
 * @param array $tasks
 */
function hrcore_civicrm_searchTasks($objectName, &$tasks) {
  $taskAdders = [
    ContactFormSearchTaskAdder::class
  ];

  foreach ($taskAdders as $adder) {
    if ($adder::shouldAdd($objectName)) {
      $adder::add($tasks);
    }
  }
}

function hrcore_civicrm_summaryActions( &$actions, $contactID ) {
  $otherActions = CRM_Utils_Array::value('otherActions', $actions, []);
  $userAdd = CRM_Utils_Array::value('user-add', $otherActions, []);

  if (empty($userAdd)) {
    return;
  }

  // replace default action with link to custom form
  $userAdd['title'] = ts('Create User Account');
  $userAdd['description'] = ts('Create User Account');
  $link = '/civicrm/user/create-account?cid=%d';
  $userAdd['href'] = sprintf($link, $contactID);
  $actions['otherActions']['user-add'] = $userAdd;
}

/**
 * Implements hook_civicrm_container().
 *
 * @param ContainerBuilder $container
 */
function hrcore_civicrm_container($container) {
  $container->register('drupal_role_service', DrupalRoleService::class);
  $container->setDefinition(
    'drupal_user_service',
    new Definition(DrupalUserService::class, [new Reference('drupal_role_service')])
  );
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @param array $files
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function hrcore_civicrm_xmlMenu(&$files) {
  _hrcore_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function hrcore_civicrm_install() {
  require_once 'CRM/HRCore/HookListener/BaseListener.php';
  require_once 'CRM/HRCore/HookListener/EventBased/OnInstall.php';

  (new OnInstallListener())->handle();
  _hrcore_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function hrcore_civicrm_uninstall() {
  require_once 'CRM/HRCore/HookListener/BaseListener.php';
  require_once 'CRM/HRCore/HookListener/EventBased/OnUninstall.php';

  (new OnUninstallListener())->handle();
  _hrcore_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function hrcore_civicrm_enable() {
  require_once 'CRM/HRCore/HookListener/BaseListener.php';
  require_once 'CRM/HRCore/HookListener/EventBased/OnEnable.php';

  (new OnEnableListener())->handle();
  _hrcore_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function hrcore_civicrm_disable() {
  (new OnDisableListener())->handle();
  _hrcore_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed
 *   Based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function hrcore_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _hrcore_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function hrcore_civicrm_managed(&$entities) {
  _hrcore_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * @param array $caseTypes
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function hrcore_civicrm_caseTypes(&$caseTypes) {
  _hrcore_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function hrcore_civicrm_angularModules(&$angularModules) {
  _hrcore_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function hrcore_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _hrcore_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_apiWrappers
 */
function hrcore_civicrm_apiWrappers(&$wrappers, $apiRequest) {
  if ($apiRequest['action'] === 'get') {
    $wrappers[] = new CRM_HRCore_APIWrapper_DefaultLimitRemover();
  }
}

/**
 * Implementation of hook_civicrm_pre hook.
 *
 * @param string $op
 *   Operation being done
 * @param string $objectName
 *   Name of the object on which the operation is being done
 * @param int $objectId
 *   ID of the record the object instantiates
 * @param array $params
 *   Parameter array being used to call the operation
 */
function hrcore_civicrm_pre($op, $objectName, $objectId, &$params) {
  $listeners = [
    new CRM_HRCore_Hook_Pre_PrimaryAddressSetter(),
  ];

  foreach ($listeners as $currentListener) {
    $currentListener->handle($op, $objectName, $objectId, $params);
  }
}

/**
 * Implements hrcore_civicrm_pageRun.
 *
 * @link https://docs.civicrm.org/dev/en/master/hooks/hook_civicrm_pageRun/
 */
function hrcore_civicrm_pageRun($page) {
  _hrcore_add_js_session_vars();

  if (isset($_GET['snippet']) && $_GET['snippet'] == 'json') {
    return;
  }

  $listeners = [
    new ContactDashboardPageHookListener($page),
    new ContactSummaryPageHookListener($page)
  ];

  foreach ($listeners as $listener) {
    $listener->onPageRun();
  }
}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu/
 */
function hrcore_civicrm_navigationMenu(&$params) {
  _hrcore_hrui_civicrm_navigationMenu($params);
  _hrcore_renameMenuLabel($params, 'Contacts', 'Staff');
  _hrcore_renameMenuLabel($params, 'Administer', 'Configure');
  _hrcore_civix_insert_navigation_menu($params, '', [
    'name' => ts('ssp'),
    'label' => ts('Self Service Portal'),
    'url' => 'dashboard',
  ]);
}

/**
 * Implements hook_civicrm_permission().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_permission/
 */
function hrcore_civicrm_permission(&$permissions) {
  $permissions += [
    'access CiviCRM developer menu and tools' => ts('Access CiviCRM developer menu and tools')
  ];
}

/**
 * Renames a menu with the given new label
 *
 * @param array $params
 * @param string $menuName
 * @param string $newLabel
 */
function _hrcore_renameMenuLabel(&$params, $menuName, $newLabel) {
  $menuItemID = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_Navigation', $menuName, 'id', 'name');
  $params[$menuItemID]['attributes']['label'] = $newLabel;
}

/**
 * This function adds the session variable to CRM.vars object.
 */
function _hrcore_add_js_session_vars() {
  CRM_Core_Resources::singleton()->addVars('session', [
    'contact_id' => CRM_Core_Session::getLoggedInContactID()
  ]);
}

/**
 * Implements hrcore_civicrm_buildForm.
 *
 * @link https://docs.civicrm.org/dev/en/master/hooks/hook_civicrm_buildForm/
 */
function hrcore_civicrm_buildForm($formName, &$form) {
  $listeners = [
    new ContactFormHookListener($form),
    new ExtensionsFormAdminHookListener($form),
    new OptionsFormAdminHookListener($form),
    new LocalizationFormAdminHookListener($form),
  ];

  foreach ($listeners as $listener) {
    $listener->onBuildForm();
  }
}

/**
 * Implements hrcore_civicrm_postProcess.
 *
 * @link https://docs.civicrm.org/dev/en/master/hooks/hook_civicrm_postProcess/
 */
function hrcore_civicrm_postProcess($formName, &$form) {
  $listeners = [
    new ContactFormHookListener($form),
  ];

  foreach ($listeners as $listener) {
    $listener->onPostProcess();
  }
}

/**
 * Implements hrcore_civicrm_tabset.
 *
 * @link https://docs.civicrm.org/dev/en/master/hooks/hook_civicrm_tabset/
 */
function hrcore_civicrm_tabset($tabsetName, &$tabs, $contactID) {
  (new OnTabsetListener())->handle($tabsetName, $tabs, $contactID);
}

/**
 * Implements hrcore_civicrm_alterMenu.
 *
 * @link https://docs.civicrm.org/dev/en/master/hooks/hook_civicrm_alterMenu/
 */
function hrcore_civicrm_alterMenu(&$items) {
  (new OnAlterMenuListener())->handle($items);
}

/**
 * Implements hrcore_civicrm_alterContent.
 *
 * @link https://docs.civicrm.org/dev/en/master/hooks/hook_civicrm_alterContent/
 */
function hrcore_civicrm_alterContent(&$content, $context, $tplName, &$object) {
  $listeners = [
    new ContactSummaryPageHookListener($object),
    new CaseDashboardPageHookListener($object),
    new CaseDashboardPageDashletHookListener($object),
    new ContactImportMapFieldFormHookListener($object),
    new ContactFormHookListener($object),
    new ProfileEditFormHookListener($object),
    new LocalizationFormAdminHookListener($object)
  ];

  foreach ($listeners as $listener) {
    $listener->onAlterContent($content);
  }
}

/**
 * Implements hrcore_civicrm_summary.
 *
 * @link https://docs.civicrm.org/dev/en/master/hooks/hook_civicrm_summary/
 */
function hrcore_civicrm_summary($contactId, &$content, &$contentPlacement) {
  _hrcore_hrui_civicrm_summary($contactId, $content, $contentPlacement);
}

/**
 * Implements hook_civicrm_coreResourceList.
 *
 * @link https://docs.civicrm.org/dev/en/master/hooks/hook_civicrm_coreResourceList/
 */
function hrcore_civicrm_coreResourceList(&$items, $region) {
  if ($region == 'html-header') {
    CRM_Core_Resources::singleton()->addScriptFile('uk.co.compucorp.civicrm.hrcore', 'js/dist/hrcore.min.js');
    CRM_Core_Resources::singleton()->addStyleFile('uk.co.compucorp.civicrm.hrcore', 'css/hrcore.css');
  }
}

//////////////////////
//                  //
//       HRUI       //
//                  //
//////////////////////

function _hrcore_hrui_civicrm_navigationMenu(&$params) {
  __hrui_customImportMenuItems($params);
  __hrui_coreMenuChanges($params);
  __hrui_createHelpMenu($params);
  __hrui_createDeveloperMenu($params);
  __hrui_setDynamicMenuIcons($params);
}

function _hrcore_hrui_civicrm_summary($contactId, &$content, &$contentPlacement) {
  $uf = _get_uf_match_contact($contactId);
  if (empty($uf) || empty($uf['uf_id'])) {
    return NULL;
  }
  $user = user_load($uf['uf_id']);
  $content['userid'] = $uf['uf_id'];
  $content['username'] = !empty($user->name) ? $user->name : '';
  $contentPlacement = NULL;
}

/**
 * Check if the extension with the given key is enabled
 *
 * @param string $extensionKey
 * @return boolean
 */
function __hrui_check_extension($extensionKey)  {
  return (boolean) CRM_Core_DAO::getFieldValue(
    'CRM_Core_DAO_Extension',
    $extensionKey,
    'is_active',
    'full_name'
  );
}

/**
 * Generating Custom Fields import child menu items
 *
 */
function __hrui_customImportMenuItems(&$params) {
  $navId = CRM_Core_DAO::singleValueQuery("SELECT max(id) FROM civicrm_navigation");

  $customFieldsNavId = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_Navigation', 'import_custom_fields', 'id', 'name');
  $contactNavId = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_Navigation', 'Contacts', 'id', 'name');

  if ($customFieldsNavId) {
    // Degrade gracefully on 4.4
    if (is_callable(array('CRM_Core_BAO_CustomGroup', 'getMultipleFieldGroup'))) {
      //  Get the maximum key of $params
      $multipleCustomData = CRM_Core_BAO_CustomGroup::getMultipleFieldGroup();

      $multiValuedData = NULL;
      foreach ($multipleCustomData as $key => $value) {
        ++$navId;
        $multiValuedData[$navId] = array (
          'attributes' => array (
            'label'      => $value,
            'name'       => $value,
            'url'        => 'civicrm/import/custom?reset=1&id='.$key,
            'permission' => 'access CiviCRM',
            'operator'   => null,
            'separator'  => null,
            'parentID'   => $customFieldsNavId,
            'navID'      => $navId,
            'active'     => 1
          )
        );
      }
      $params[$contactNavId]['child'][$customFieldsNavId]['child'] = $multiValuedData;
    }
  }
}

/**
 * Changes to some core menu items
 *
 */
function __hrui_coreMenuChanges(&$params) {
  // remove search items
  $searchNavId = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_Navigation', 'Search...', 'id', 'name');
  $toRemove = [
    'Full-text search',
    'Search builder',
    'Custom searches',
    'Find Cases',
    'Find Activities',
  ];
  foreach($toRemove as $item) {
    if (
      in_array($item, ['Find Cases', 'Find Activities'])
      && !(__hrui_check_extension('uk.co.compucorp.civicrm.tasksassignments'))
    ) {
      continue;
    }
    $itemId = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_Navigation', $item , 'id', 'name');
    unset($params[$searchNavId]['child'][$itemId]);
  }

  // remove contact items
  $searchNavId = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_Navigation', 'Contacts', 'id', 'name');
  $toRemove = [
    'New Tag',
    'Manage Tags (Categories)',
    'New Activity',
    'Import Activities',
    'Contact Reports',
  ];
  foreach($toRemove as $item) {
    if (
      in_array($item, ['New Activity', 'Import Activities'])
      && !(__hrui_check_extension('uk.co.compucorp.civicrm.tasksassignments'))
    ) {
      continue;
    }
    $itemId = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_Navigation', $item , 'id', 'name');
    unset($params[$searchNavId]['child'][$itemId]);
  }

  // remove main Reports menu
  $reportsNavId = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_Navigation', 'Reports', 'id', 'name');
  unset($params[$reportsNavId]);

  // Remove Admin items
  $adminNavId = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_Navigation', 'Administer', 'id', 'name');

  $civiReportNavId = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_Navigation', 'CiviReport', 'id', 'name');

  $civiCaseNavId = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_Navigation', 'CiviCase', 'id', 'name');
  $redactionRulesNavId = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_Navigation', 'Redaction Rules', 'id', 'name');
  $supportNavId = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_Navigation', 'Support', 'id', 'name');

  unset($params[$supportNavId]);
  unset($params[$adminNavId]['child'][$civiReportNavId]);
  unset($params[$adminNavId]['child'][$civiCaseNavId]['child'][$redactionRulesNavId]);
}

/**
 * Creates Help Menu in navigation bar
 *
 * @param Array $menu List of available menu items
 */
function __hrui_createHelpMenu(&$menu) {
  _hrcore_civix_insert_navigation_menu($menu, '', [
    'name' => ts('Help'),
    'permission' => 'access CiviCRM',
  ]);

  _hrcore_civix_insert_navigation_menu($menu, 'Help', [
    'name' => ts('User Guide'),
    'url' => 'http://civihr-documentation.readthedocs.io/en/latest/',
    'target' => '_blank',
    'permission' => 'access CiviCRM'
  ]);

  _hrcore_civix_insert_navigation_menu($menu, 'Help', [
    'name' => ts('CiviHR website'),
    'url' => 'https://www.civihr.org/',
    'target' => '_blank',
    'permission' => 'access CiviCRM'
  ]);

  _hrcore_civix_insert_navigation_menu($menu, 'Help', [
    'name' => ts('Get support'),
    'url' => 'https://www.civihr.org/support',
    'target' => '_blank',
    'permission' => 'access CiviCRM'
  ]);
}

/**
 * Creates Developer Menu in navigation bar
 *
 * @param Array $menu List of available menu items
 */
function __hrui_createDeveloperMenu(&$menu) {
  _hrcore_civix_insert_navigation_menu($menu, '', [
    'name' => ts('Developer'),
    'permission' => 'access CiviCRM,access CiviCRM developer menu and tools',
    'operator' => 'AND'
  ]);

  _hrcore_civix_insert_navigation_menu($menu, 'Developer', [
    'name' => ts('API Explorer'),
    'url' => 'civicrm/api',
    'target' => '_blank',
    'permission' => 'access CiviCRM,access CiviCRM developer menu and tools',
    'operator' => 'AND'
  ]);

  _hrcore_civix_insert_navigation_menu($menu, 'Developer', [
    'name' => ts('Developer Docs'),
    'target' => '_blank',
    'url' => 'https://civihr.atlassian.net/wiki/spaces/CIV/pages',
    'permission' => 'access CiviCRM,access CiviCRM developer menu and tools',
    'operator' => 'AND'
  ]);

  _hrcore_civix_insert_navigation_menu($menu, 'Developer', [
    'name' => ts('Style Guide'),
    'target' => '_blank',
    'url' => 'https://www.civihr.org/support',
    'permission' => 'access CiviCRM,access CiviCRM developer menu and tools',
    'operator' => 'AND'
  ]);

  // Adds sub menu under Style Guide menu
  foreach (Civi::service('style_guides')->getAll() as $styleGuide) {
    _hrcore_civix_insert_navigation_menu($menu, 'Developer/Style Guide', [
      'label' => $styleGuide['label'],
      'name' => $styleGuide['name'],
      'url' => 'civicrm/styleguide/' . $styleGuide['name'],
      'permission' => 'access CiviCRM,access CiviCRM developer menu and tools',
      'operator' => 'AND'
    ]);
  }
}

/**
 * Adds icons to dynamically defined menu items
 *
 * @param array $menu
 *   List of available menu items
 */
function __hrui_setDynamicMenuIcons(&$menu) {
  $menuToIcons = [
    'Help' => 'fa fa-question-circle',
    'Developer'=> 'fa fa-code',
  ];

  foreach ($menu as $key => $item) {
    $menuName = $item['attributes']['name'];
    if (array_key_exists($menuName, $menuToIcons)) {
      $menu[$key]['attributes']['icon'] = $menuToIcons[$menuName];
    }
  }
}

function __hrui_is_extension_enabled($key) {
  $isEnabled = CRM_Core_DAO::getFieldValue(
    'CRM_Core_DAO_Extension',
    $key,
    'is_active',
    'full_name'
  );

  return !empty($isEnabled) ? true : false;
}


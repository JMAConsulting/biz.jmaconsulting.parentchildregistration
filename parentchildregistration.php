<?php
require_once 'parentchildregistration.constants.php';

require_once 'parentchildregistration.civix.php';

use CRM_Parentchildregistration_ExtensionUtil as E;

/**
 * Implementation of hook_civicrm_config
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function parentchildregistration_civicrm_config(&$config) {
  _parentchildregistration_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_xmlMenu
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function parentchildregistration_civicrm_xmlMenu(&$files) {
  _parentchildregistration_civix_civicrm_xmlMenu($files);
}

/**
 * Implementation of hook_civicrm_install
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function parentchildregistration_civicrm_install() {
  _parentchildregistration_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_uninstall
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function parentchildregistration_civicrm_uninstall() {
  _parentchildregistration_civix_civicrm_uninstall();
}

/**
 * Implementation of hook_civicrm_enable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function parentchildregistration_civicrm_enable() {
  _parentchildregistration_civix_civicrm_enable();
}

/**
 * Implementation of hook_civicrm_disable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function parentchildregistration_civicrm_disable() {
  _parentchildregistration_civix_civicrm_disable();
}

/**
 * Implementation of hook_civicrm_upgrade
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed  based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function parentchildregistration_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _parentchildregistration_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implementation of hook_civicrm_managed
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function parentchildregistration_civicrm_managed(&$entities) {
  _parentchildregistration_civix_civicrm_managed($entities);
}

/**
 * Implementation of hook_civicrm_caseTypes
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function parentchildregistration_civicrm_caseTypes(&$caseTypes) {
  _parentchildregistration_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implementation of hook_civicrm_alterSettingsFolders
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function parentchildregistration_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _parentchildregistration_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

function checkTemplate($id) {
  if (CRM_Core_DAO::singleValueQuery("SELECT template_title FROM civicrm_event WHERE id = %1", [1 => [$id, 'Integer']]) == 'Parent Child Registration') {
    return TRUE;
  }
  return FALSE;
}

/**
 * Implements hook_civicrm_buildForm().
 *
 * @param string $formName
 * @param CRM_Core_Form $form
 */
function parentchildregistration_civicrm_buildForm($formName, &$form) {
  if ($formName == "CRM_Event_Form_Registration_Register") {
    $template = civicrm_api3('Event', 'get', [
      'id' => $form->_eventId,
      'return.' . EVENT_TEMPLATE_ID => 1,
    ])['values'][$form->_eventId];
    if (!empty($template[EVENT_TEMPLATE_ID])) {
      $templateId = $template[EVENT_TEMPLATE_ID];
    }

    // Check if SLO Zoo Event.
    if (!empty($templateId) && $templateId == SLOZOO) {
      $form->assign('slozoo', TRUE);
    }

    // Check if SLO Var Event.
    if ((!empty($templateId) && $templateId == SLOVAR)) {
      $form->assign('slovar', TRUE);
      $priceSetId = CRM_Price_BAO_PriceSet::getFor('civicrm_event', $form->_eventId);
      if (!empty($priceSetId)) {
        $childPrice = CRM_Core_DAO::executeQuery("SELECT id FROM civicrm_price_field WHERE name LIKE '%Child%' AND price_set_id = %1", [1 => [$priceSetId, "Integer"]])->fetchAll()[0]['id'];
      }
      // get list of values.
      if (!empty($childPrice)) {
        $form->assign('childPrice', $childPrice);
        $priceValues = CRM_Core_DAO::executeQuery("SELECT id, name FROM civicrm_price_field_value WHERE price_field_id = %1", [1 => [$childPrice, "Integer"]])->fetchAll();
        $form->assign('childPriceValues', $priceValues);
      }
    }


    if (!empty($templateId)) {
      // Add JS
      CRM_Core_Region::instance('page-body')->add(array(
        'template' => 'CRM/Parentchildregistration/ParentChild.tpl',
      ));
    }
  }
}

/**
 * Implements hook_civicrm_validateForm().
 *
 * @param string $formName
 * @param array $fields
 * @param array $files
 * @param CRM_Core_Form $form
 * @param array $errors
 */
function parentchildregistration_civicrm_validateForm($formName, &$fields, &$files, &$form, &$errors) {
  if ($formName == "CRM_Event_Form_Registration_Register") {
    $template = civicrm_api3('Event', 'get', [
      'id' => $form->_eventId,
      'return.' . EVENT_TEMPLATE_ID => 1,
    ])['values'][$form->_eventId];
    if (!empty($template[EVENT_TEMPLATE_ID])) {
      $templateId = $template[EVENT_TEMPLATE_ID];
    }
    if (!empty($templateId)) {
      $count = 1;
      $template = getEventTemplates($templateId);
      while ($count < 8) {
        $constant = constant('WAIVER_' . $count);

        if (empty($fields[$constant][1]) && defined('WAIVER_' . $count)) {
          if ($count == 5) {
            if (!in_array($template, ['SLO Skill Building', 'Workshop Behaviour', 'Workshop Communication', 'Workshop - Other', 'Workshop - Social', 'Webinar - Live'])) {
              $errors[$constant] = E::ts('This field is required.');
            }
          } else {
            $errors[$constant] = E::ts('This field is required.');
          }
        }
        $count++;
      }
    }
    if (!empty($fields['child_select'])) {
      switch($fields['child_select']) {
        case 1:
          if (empty($fields[CHILD1FN]) || empty($fields[CHILD1LN])) {
            $errors[CHILD1FN] = E::ts('First and last name of child 1 must be entered.');
          }
          if (!empty($fields[CHILD1DOB]) && $fields[CHILD1DOB] > date('Y-m-d')) {
            $errors[CHILD1DOB] = E::ts('Date of birth of child 1 must not be before today');
          }
          break;
        case 2:
          if (empty($fields[CHILD1FN]) || empty($fields[CHILD1LN])) {
            $errors[CHILD1FN] = E::ts('First and last name of child 1 must be entered.');
          }
          if (!empty($fields[CHILD1DOB]) && $fields[CHILD1DOB] > date('Y-m-d')) {
            $errors[CHILD1DOB] = E::ts('Date of birth of child 1 must not be before today');
          }
          if (empty($fields[CHILD2FN]) || empty($fields[CHILD2LN])) {
            $errors[CHILD2FN] = E::ts('First and last name of child 2 must be entered.');
          }
          if (!empty($fields[CHILD2DOB]) && $fields[CHILD2DOB] > date('Y-m-d')) {
            $errors[CHILD2DOB] = E::ts('Date of birth of child 2 must not be before today');
          }
          break;
        case 3:
          if (empty($fields[CHILD1FN]) || empty($fields[CHILD1LN])) {
            $errors[CHILD1FN] = E::ts('First and last name of child 1 must be entered.');
          }
          if (!empty($fields[CHILD1DOB]) && $fields[CHILD1DOB] > date('Y-m-d')) {
            $errors[CHILD1DOB] = E::ts('Date of birth of child 1 must not be before today');
          }
          if (empty($fields[CHILD2FN]) || empty($fields[CHILD2LN])) {
            $errors[CHILD2FN] = E::ts('First and last name of child 2 must be entered.');
          }
          if (!empty($fields[CHILD2DOB]) && $fields[CHILD2DOB] > date('Y-m-d')) {
            $errors[CHILD2DOB] = E::ts('Date of birth of child 2 must not be before today');
          }
          if (empty($fields[CHILD3FN]) || empty($fields[CHILD3LN])) {
            $errors[CHILD3FN] = E::ts('First and last name of child 3 must be entered.');
          }
          if (!empty($fields[CHILD3DOB]) && $fields[CHILD3DOB] > date('Y-m-d')) {
            $errors[CHILD3DOB] = E::ts('Date of birth of child 3 must not be before today');
          }
          break;
        case 4:
          if (empty($fields[CHILD1FN]) || empty($fields[CHILD1LN])) {
            $errors[CHILD1FN] = E::ts('First and last name of child 1 must be entered.');
          }
          if (!empty($fields[CHILD1DOB]) && $fields[CHILD1DOB] > date('Y-m-d')) {
            $errors[CHILD1DOB] = E::ts('Date of birth of child 1 must not be before today');
          }
          if (empty($fields[CHILD2FN]) || empty($fields[CHILD2LN])) {
            $errors[CHILD2FN] = E::ts('First and last name of child 2 must be entered.');
          }
          if (!empty($fields[CHILD2DOB]) && $fields[CHILD2DOB] > date('Y-m-d')) {
            $errors[CHILD2DOB] = E::ts('Date of birth of child 2 must not be before today');
          }
          if (empty($fields[CHILD3FN]) || empty($fields[CHILD3LN])) {
            $errors[CHILD3FN] = E::ts('First and last name of child 3 must be entered.');
          }
          if (!empty($fields[CHILD3DOB]) && $fields[CHILD3DOB] > date('Y-m-d')) {
            $errors[CHILD3DOB] = E::ts('Date of birth of child 3 must not be before today');
          }
          if (empty($fields[CHILD4FN]) || empty($fields[CHILD4LN])) {
            $errors[CHILD4FN] = E::ts('First and last name of child 4 must be entered.');
          }
          if (!empty($fields[CHILD4DOB]) && $fields[CHILD4DOB] > date('Y-m-d')) {
            $errors[CHILD3DOB] = E::ts('Date of birth of child 3 must not be before today');
          }
          break;
      }
    }
    if (!empty($templateId)) {
      $varPrice = CRM_Core_Smarty::singleton()->get_template_vars('childPrice');
      if (!empty($fields['price_' . $varPrice])) {
        $priceVals = CRM_Core_Smarty::singleton()->get_template_vars('childPriceValues');
        foreach ($priceVals as $prices) {
          if ($fields['price_' . $varPrice] == $prices['id']) {
            switch ($prices['name']) {
              case 1:
                if (empty($fields[CHILD1FN]) || empty($fields[CHILD1LN])) {
                  $errors[CHILD1FN] = E::ts('First and last name of child 1 must be entered.');
                }
                if (!empty($fields[CHILD1DOB]) && $fields[CHILD1DOB] > date('Y-m-d')) {
                  $errors[CHILD1DOB] = E::ts('Date of birth of child 1 must not be before today');
                }
                break;
              case 2:
                if (empty($fields[CHILD1FN]) || empty($fields[CHILD1LN])) {
                  $errors[CHILD1FN] = E::ts('First and last name of child 1 must be entered.');
                }
                if (!empty($fields[CHILD1DOB]) && $fields[CHILD1DOB] > date('Y-m-d')) {
                  $errors[CHILD1DOB] = E::ts('Date of birth of child 1 must not be before today');
                }
                if (empty($fields[CHILD2FN]) || empty($fields[CHILD2LN])) {
                  $errors[CHILD2FN] = E::ts('First and last name of child 2 must be entered.');
                }
                if (!empty($fields[CHILD2DOB]) && $fields[CHILD2DOB] > date('Y-m-d')) {
                  $errors[CHILD2DOB] = E::ts('Date of birth of child 2 must not be before today');
                }
                break;
              case 3:
                if (empty($fields[CHILD1FN]) || empty($fields[CHILD1LN])) {
                  $errors[CHILD1FN] = E::ts('First and last name of child 1 must be entered.');
                }
                if (!empty($fields[CHILD1DOB]) && $fields[CHILD1DOB] > date('Y-m-d')) {
                  $errors[CHILD1DOB] = E::ts('Date of birth of child 1 must not be before today');
                }
                if (empty($fields[CHILD2FN]) || empty($fields[CHILD2LN])) {
                  $errors[CHILD2FN] = E::ts('First and last name of child 2 must be entered.');
                }
                if (!empty($fields[CHILD2DOB]) && $fields[CHILD2DOB] > date('Y-m-d')) {
                  $errors[CHILD2DOB] = E::ts('Date of birth of child 2 must not be before today');
                }
                if (empty($fields[CHILD3FN]) || empty($fields[CHILD3LN])) {
                  $errors[CHILD3FN] = E::ts('First and last name of child 3 must be entered.');
                }
                if (!empty($fields[CHILD3DOB]) && $fields[CHILD3DOB] > date('Y-m-d')) {
                  $errors[CHILD3DOB] = E::ts('Date of birth of child 3 must not be before today');
                }
                break;
              case 4:
                if (empty($fields[CHILD1FN]) || empty($fields[CHILD1LN])) {
                  $errors[CHILD1FN] = E::ts('First and last name of child 1 must be entered.');
                }
                if (!empty($fields[CHILD1DOB]) && $fields[CHILD1DOB] > date('Y-m-d')) {
                  $errors[CHILD1DOB] = E::ts('Date of birth of child 1 must not be before today');
                }
                if (empty($fields[CHILD2FN]) || empty($fields[CHILD2LN])) {
                  $errors[CHILD2FN] = E::ts('First and last name of child 2 must be entered.');
                }
                if (!empty($fields[CHILD2DOB]) && $fields[CHILD2DOB] > date('Y-m-d')) {
                  $errors[CHILD2DOB] = E::ts('Date of birth of child 2 must not be before today');
                }
                if (empty($fields[CHILD3FN]) || empty($fields[CHILD3LN])) {
                  $errors[CHILD3FN] = E::ts('First and last name of child 3 must be entered.');
                }
                if (!empty($fields[CHILD3DOB]) && $fields[CHILD3DOB] > date('Y-m-d')) {
                  $errors[CHILD3DOB] = E::ts('Date of birth of child 3 must not be before today');
                }
                if (empty($fields[CHILD4FN]) || empty($fields[CHILD4LN])) {
                  $errors[CHILD4FN] = E::ts('First and last name of child 4 must be entered.');
                }
                if (!empty($fields[CHILD4DOB]) && $fields[CHILD4DOB] > date('Y-m-d')) {
                  $errors[CHILD4DOB] = E::ts('Date of birth of child 4 must not be before today');
                }
                break;
            }
          }
        }
      }
    }

    if (!empty($fields[CHILDUNDER]) || !empty($fields[CHILDTHREE]) || !empty($fields[CHILDPLUS])) {
      $totalFields = $fields[CHILDUNDER] + $fields[CHILDTHREE] + $fields[CHILDPLUS];
      if ($totalFields == 1) {
        if (empty($fields[CHILD1FN]) || empty($fields[CHILD1LN])) {
          $errors[CHILD1FN] = E::ts('First and last name of child 1 must be entered.');
        }
        if (!empty($fields[CHILD1DOB]) && $fields[CHILD1DOB] > date('Y-m-d')) {
          $errors[CHILD1DOB] = E::ts('Date of birth of child 1 must not be before today');
        }
      }
      if ($totalFields == 2) {
        if (empty($fields[CHILD1FN]) || empty($fields[CHILD1LN])) {
          $errors[CHILD1FN] = E::ts('First and last name of child 1 must be entered.');
        }
        if (!empty($fields[CHILD1DOB]) && $fields[CHILD1DOB] > date('Y-m-d')) {
          $errors[CHILD1DOB] = E::ts('Date of birth of child 1 must not be before today');
        }
        if (empty($fields[CHILD2FN]) || empty($fields[CHILD2LN])) {
          $errors[CHILD2FN] = E::ts('First and last name of child 2 must be entered.');
        }
        if (!empty($fields[CHILD2DOB]) && $fields[CHILD2DOB] > date('Y-m-d')) {
          $errors[CHILD2DOB] = E::ts('Date of birth of child 2 must not be before today');
        }
      }
      if ($totalFields == 3) {
        if (empty($fields[CHILD1FN]) || empty($fields[CHILD1LN])) {
          $errors[CHILD1FN] = E::ts('First and last name of child 1 must be entered.');
        }
        if (!empty($fields[CHILD1DOB]) && $fields[CHILD1DOB] > date('Y-m-d')) {
          $errors[CHILD1DOB] = E::ts('Date of birth of child 1 must not be before today');
        }
        if (empty($fields[CHILD2FN]) || empty($fields[CHILD2LN])) {
          $errors[CHILD2FN] = E::ts('First and last name of child 2 must be entered.');
        }
        if (!empty($fields[CHILD2DOB]) && $fields[CHILD2DOB] > date('Y-m-d')) {
          $errors[CHILD2DOB] = E::ts('Date of birth of child 2 must not be before today');
        }
        if (empty($fields[CHILD3FN]) || empty($fields[CHILD3LN])) {
          $errors[CHILD3FN] = E::ts('First and last name of child 3 must be entered.');
        }
        if (!empty($fields[CHILD3DOB]) && $fields[CHILD3DOB] > date('Y-m-d')) {
          $errors[CHILD3DOB] = E::ts('Date of birth of child 3 must not be before today');
        }
      }
      if ($totalFields >= 4) {
        if (empty($fields[CHILD1FN]) || empty($fields[CHILD1LN])) {
          $errors[CHILD1FN] = E::ts('First and last name of child 1 must be entered.');
        }
        if (!empty($fields[CHILD1DOB]) && $fields[CHILD1DOB] > date('Y-m-d')) {
          $errors[CHILD1DOB] = E::ts('Date of birth of child 1 must not be before today');
        }
        if (empty($fields[CHILD2FN]) || empty($fields[CHILD2LN])) {
          $errors[CHILD2FN] = E::ts('First and last name of child 2 must be entered.');
        }
        if (!empty($fields[CHILD2DOB]) && $fields[CHILD2DOB] > date('Y-m-d')) {
          $errors[CHILD2DOB] = E::ts('Date of birth of child 2 must not be before today');
        }
        if (empty($fields[CHILD3FN]) || empty($fields[CHILD3LN])) {
          $errors[CHILD3FN] = E::ts('First and last name of child 3 must be entered.');
        }
        if (!empty($fields[CHILD3DOB]) && $fields[CHILD3DOB] > date('Y-m-d')) {
          $errors[CHILD3DOB] = E::ts('Date of birth of child 3 must not be before today');
        }
        if (empty($fields[CHILD4FN]) || empty($fields[CHILD4LN])) {
          $errors[CHILD4FN] = E::ts('First and last name of child 4 must be entered.');
        }
        if (!empty($fields[CHILD4DOB]) && $fields[CHILD4DOB] > date('Y-m-d')) {
          $errors[CHILD4DOB] = E::ts('Date of birth of child 4 must not be before today');
        }
      }
    }
    if (!empty($fields[CHILDPRICE])) {
      switch ($fields[CHILDPRICE]) {
        case 59:
          if (empty($fields[CHILD1FN]) || empty($fields[CHILD1LN])) {
            $errors[CHILD1FN] = E::ts('First and last name of child 1 must be entered.');
          }
          if (!empty($fields[CHILD1DOB]) && $fields[CHILD1DOB] > date('Y-m-d')) {
            $errors[CHILD1DOB] = E::ts('Date of birth of child 1 must not be before today');
          }
        break;
        case 60:
          if (empty($fields[CHILD1FN]) || empty($fields[CHILD1LN])) {
            $errors[CHILD1FN] = E::ts('First and last name of child 1 must be entered.');
          }
          if (!empty($fields[CHILD1DOB]) && $fields[CHILD1DOB] > date('Y-m-d')) {
            $errors[CHILD1DOB] = E::ts('Date of birth of child 1 must not be before today');
          }
          if (empty($fields[CHILD2FN]) || empty($fields[CHILD2LN])) {
            $errors[CHILD2FN] = E::ts('First and last name of child 2 must be entered.');
          }
          if (!empty($fields[CHILD2DOB]) && $fields[CHILD2DOB] > date('Y-m-d')) {
            $errors[CHILD2DOB] = E::ts('Date of birth of child 2 must not be before today');
          }
        break;
        case 61:
          if (empty($fields[CHILD1FN]) || empty($fields[CHILD1LN])) {
            $errors[CHILD1FN] = E::ts('First and last name of child 1 must be entered.');
          }
          if (!empty($fields[CHILD1DOB]) && $fields[CHILD1DOB] > date('Y-m-d')) {
            $errors[CHILD1DOB] = E::ts('Date of birth of child 1 must not be before today');
          }
          if (empty($fields[CHILD2FN]) || empty($fields[CHILD2LN])) {
            $errors[CHILD2FN] = E::ts('First and last name of child 2 must be entered.');
          }
          if (!empty($fields[CHILD2DOB]) && $fields[CHILD2DOB] > date('Y-m-d')) {
            $errors[CHILD2DOB] = E::ts('Date of birth of child 2 must not be before today');
          }
          if (empty($fields[CHILD3FN]) || empty($fields[CHILD3LN])) {
            $errors[CHILD3FN] = E::ts('First and last name of child 3 must be entered.');
          }
          if (!empty($fields[CHILD3DOB]) && $fields[CHILD3DOB] > date('Y-m-d')) {
            $errors[CHILD3DOB] = E::ts('Date of birth of child 3 must not be before today');
          }
        break;
        case 74:
          if (empty($fields[CHILD1FN]) || empty($fields[CHILD1LN])) {
            $errors[CHILD1FN] = E::ts('First and last name of child 1 must be entered.');
          }
          if (!empty($fields[CHILD1DOB]) && $fields[CHILD1DOB] > date('Y-m-d')) {
            $errors[CHILD1DOB] = E::ts('Date of birth of child 1 must not be before today');
          }
          if (empty($fields[CHILD2FN]) || empty($fields[CHILD2LN])) {
            $errors[CHILD2FN] = E::ts('First and last name of child 2 must be entered.');
          }
          if (!empty($fields[CHILD2DOB]) && $fields[CHILD2DOB] > date('Y-m-d')) {
            $errors[CHILD2DOB] = E::ts('Date of birth of child 2 must not be before today');
          }
          if (empty($fields[CHILD3FN]) || empty($fields[CHILD3LN])) {
            $errors[CHILD3FN] = E::ts('First and last name of child 3 must be entered.');
          }
          if (!empty($fields[CHILD3DOB]) && $fields[CHILD3DOB] > date('Y-m-d')) {
            $errors[CHILD3DOB] = E::ts('Date of birth of child 3 must not be before today');
          }
          if (empty($fields[CHILD4FN]) || empty($fields[CHILD4LN])) {
            $errors[CHILD4FN] = E::ts('First and last name of child 4 must be entered.');
          }
          if (!empty($fields[CHILD4DOB]) && $fields[CHILD4DOB] > date('Y-m-d')) {
            $errors[CHILD4DOB] = E::ts('Date of birth of child 4 must not be before today');
          }
        break;
      }
    }
  }
}

/**
 * Implements hook_civicrm_post().
 *
 */
function parentchildregistration_civicrm_post($op, $objectName, $objectId, &$objectRef) {
  if ($op == "edit" && $objectName == "Participant") {
    if ($objectRef->status_id == 4) {
      // Cancel related participants as well.
      $registeredById = CRM_Core_DAO::executeQuery("SELECT id FROM civicrm_participant WHERE registered_by_id = %1", [1 => [$objectId, "Integer"]])->fetchAll();
      if (!empty($registeredById)) {
        foreach ($registeredById as $participantId) {
          civicrm_api3('Participant', 'create', ['id' => $participantId['id'], 'status_id' => 4]);
        }
      }
    }
  }
}

/**
 * Implements hook_civicrm_postProcess().
 *
 * @param string $formName
 * @param CRM_Core_Form $form
 */
function parentchildregistration_civicrm_postProcess($formName, &$form) {
  if ($formName == "CRM_Event_Form_Registration_Confirm") {
    $template = civicrm_api3('Event', 'get', [
      'id' => $form->_eventId,
      'return.' . EVENT_TEMPLATE_ID => 1,
    ])['values'][$form->_eventId];
    if (!empty($template[EVENT_TEMPLATE_ID])) {
      $templateId = $template[EVENT_TEMPLATE_ID];
    }
    if (!$templateId) {
      return;
    }
    $parent = $form->_values['participant']['participant_contact_id'];
    $participantId = $form->getVar('_participantId');

    $address = civicrm_api3('Address', 'get', ['contact_id' => $parent])['values'];

    if (empty($form->_values['params'][$participantId][CHILD1FN])) {
      return;
    }

    $relatedContacts = [
      'child1' => [
        'first_name' => $form->_values['params'][$participantId][CHILD1FN] ?: '',
        'last_name' => $form->_values['params'][$participantId][CHILD1LN] ?: '',
        'birth_date' => $form->_values['params'][$participantId][CHILD1DOB] ?: '',
        'gender' => $form->_values['params'][$participantId][CHILD1GEN] ?: '',
      ],
      'child2' => [
        'first_name' => $form->_values['params'][$participantId][CHILD2FN] ?: '',
        'last_name' => $form->_values['params'][$participantId][CHILD2LN] ?: '',
        'birth_date' => $form->_values['params'][$participantId][CHILD2DOB] ?: '',
        'gender' => $form->_values['params'][$participantId][CHILD2GEN] ?: '',
      ],
      'child3' => [
        'first_name' => $form->_values['params'][$participantId][CHILD3FN] ?: '',
        'last_name' => $form->_values['params'][$participantId][CHILD3LN] ?: '',
        'birth_date' => $form->_values['params'][$participantId][CHILD3DOB] ?: '',
        'gender' => $form->_values['params'][$participantId][CHILD3GEN] ?: '',
      ],
      'child4' => [
        'first_name' => $form->_values['params'][$participantId][CHILD4FN] ?: '',
        'last_name' => $form->_values['params'][$participantId][CHILD4LN] ?: '',
        'birth_date' => $form->_values['params'][$participantId][CHILD4DOB] ?: '',
        'gender' => $form->_values['params'][$participantId][CHILD4GEN] ?: '',
      ],
    ];
    foreach ($relatedContacts as $person => $params) {
      if (empty($params['first_name']) && empty($params['last_name'])) {
        continue;
      }
      $dedupeParams = CRM_Dedupe_Finder::formatParams($params, 'Individual');
      $dedupeParams['check_permission'] = FALSE;
      $rule = CRM_Core_DAO::singleValueQuery("SELECT max(id) FROM civicrm_dedupe_rule_group WHERE name = 'Child_Rule_10'");
      $dupes = CRM_Dedupe_Finder::dupesByParams($dedupeParams, 'Individual', NULL, array(), $rule);
      $cid = CRM_Utils_Array::value('0', $dupes, NULL);
      $params['contact_type'] = 'Individual';
      if (in_array($person, ['child1', 'child2', 'child3', 'child4'])) {
        $params['contact_sub_type'] = 'Child';
      }
      if ($cid) {
        $params['contact_id'] = $cid;
      }
      $contact[$person] = (array) civicrm_api3('Contact', 'create', $params)['id'];
      if ($person == 'child1') {
        $leadChildId = $contact[$person];
      }

      // Check child contacts for date of first contact.
      if (strpos($person, 'child') !== false) {
	$isFilled = CRM_Core_DAO::executeQuery("SELECT entity_id FROM civicrm_value_newsletter_cu_3 WHERE entity_id IN (" . $contact[$person][0] . ") AND (first_contacted_358 IS NOT NULL OR first_contacted_358 != '')")->fetchAll();
        if (empty($isFilled)) {
          civicrm_api3('CustomValue', 'create', [
            'entity_id' => $contact[$person][0],
            'custom_29' => date('Ymd'),
          ]);
        }
        // Add I am a person with ASD.
        civicrm_api3('CustomValue', 'create', [
          'entity_id' => $contact[$person][0],
          'custom_7' => 'Une personne TSA',
        ]);
      }

      // Create participant record for child.
      civicrm_api3('Participant', 'create', [
        'contact_id' => $contact[$person][0],
        'event_id' => $form->_eventId,
        'registered_by_id' => $participantId,
        'status_id' => 17,
        'role_id' => 1,
      ]);

      // Add address for child.
      foreach ($address as $k => &$val) {
        unset($val['id']);
        $val['contact_id'] = $contact[$person][0];
        $val['master_id'] = $k;
        civicrm_api3('Address', 'create', $address[$k]);
      }

      if (!empty($form->_values['params'][$participantId]['postal_code-Primary'])) {

        list($chapter, $region) = getChapRegCodes($form->_values['params'][$participantId]['postal_code-Primary']);
        if ($chapter || $region) {
          $cParams = [
            'chapter' => $chapter,
            'region' => $region,
            'contact_id' => $contact[$person][0],
          ];
          setChapRegCodes($cParams);
        }
      }
    }

    // Create relationships
    $sibling = CRM_Core_DAO::getFieldValue('CRM_Contact_DAO_RelationshipType', 'Sibling of', 'id', 'name_a_b');
    $childRel = CRM_Core_DAO::getFieldValue('CRM_Contact_DAO_RelationshipType', 'Child of', 'id', 'name_a_b');

    // Check if contact has child with lead family member. If he doesn't then add first child as lead member.
    $isLeadFamilyPresent = CRM_Core_DAO::singleValueQuery("SELECT n.lead_family_member__28 FROM civicrm_value_newsletter_cu_3 n INNER JOIN civicrm_relationship r ON n.entity_id = r.contact_id_a WHERE r.relationship_type_id = 1 AND r.contact_id_b = %1 AND n.lead_family_member__28 = 1 LIMIT 1", [1 => [$parent, 'Integer']]);
    if (empty($isLeadFamilyPresent)) {
      if (is_array($leadChildId) && !empty($leadChildId[0])) {
        $leadChildId = $leadChildId[0];
      }
      if (!empty($leadChildId)) {
        civicrm_api3('Contact', 'create', ['id' => $leadChildId, LEAD_MEMBER => 1]);
      }
    }

    foreach ($contact as $person => $cid) {
      if (!empty($contact[$person])) {
        createRelationship($contact[$person][0], $parent, $childRel);
      }
    }

    if (!empty($contact['child2'])) {
      createRelationship($contact['child1'][0], $contact['child2'][0], $sibling);
    }
    if (!empty($contact['child3'])) {
      createRelationship($contact['child1'][0], $contact['child3'][0], $sibling);
      createRelationship($contact['child2'][0], $contact['child3'][0], $sibling);
    }
    if (!empty($contact['child4'])) {
      createRelationship($contact['child1'][0], $contact['child4'][0], $sibling);
      createRelationship($contact['child2'][0], $contact['child4'][0], $sibling);
      createRelationship($contact['child3'][0], $contact['child4'][0], $sibling);
    }
  }
}

function getChapRegCodes($postalCode) {
  $chapterCode = strtoupper(substr($postalCode, 0, 3));
  $sql = "SELECT pcode, region, chapter FROM chapters_lookup WHERE pcode = '{$chapterCode}'";
  $dao = CRM_Core_DAO::executeQuery($sql);
  while ($dao->fetch()) {
    $region = $dao->region;
    $chapter = $dao->chapter;
  }
  return [$chapter, $region];
}


function getChapRegIds() {
  $chapterId = civicrm_api3('CustomField', 'getvalue', array(
    'name' => 'Chapter',
    'return' => 'id',
    'custom_group_id' => "chapter_region",
  ));

  $regionId = civicrm_api3('CustomField', 'getvalue', array(
    'name' => 'Service_Region',
    'return' => 'id',
    'custom_group_id' => "chapter_region",
  ));
  return [$chapterId, $regionId];
}

function setChapRegCodes($params, $existingCodes = []) {
  list($chapterId, $regionId) = getChapRegIds();
  // Check if the chapter and region exist in CiviCRM.
  $chapters = CRM_Core_OptionGroup::values('chapter_20180619153429', FALSE, FALSE, FALSE, NULL, 'label', FALSE);
  $regions = CRM_Core_OptionGroup::values('service_region_20190320122604', FALSE, FALSE, FALSE, NULL, 'label', FALSE);

  if (!empty($params['chapter']) && array_search($params['chapter'], $chapters)) {
    civicrm_api3('CustomValue', 'create', array(
      'entity_id' => $params['contact_id'],
      'custom_' . $chapterId => CRM_Core_DAO::VALUE_SEPARATOR . $params['chapter'] . CRM_Core_DAO::VALUE_SEPARATOR,
    ));
  }
  if (!empty($params['region']) && array_search($params['region'], $regions)) {
    civicrm_api3('CustomValue', 'create', array(
      'entity_id' => $params['contact_id'],
      'custom_' . $regionId => CRM_Core_DAO::VALUE_SEPARATOR . $params['region'] . CRM_Core_DAO::VALUE_SEPARATOR,
    ));
  }
}

function createRelationship($cida, $cidb, $type) {
  $relationshipParams = array(
    "contact_id_a" => $cida,
    "contact_id_b" => $cidb,
    "relationship_type_id" => $type,
  );
  $rel = civicrm_api3("Relationship", "get", $relationshipParams);
  if ($rel['count'] < 1) {
    civicrm_api3("Relationship", "create", $relationshipParams);
  }
}

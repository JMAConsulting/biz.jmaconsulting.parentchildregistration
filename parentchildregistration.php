<?php
require_once 'parentchildregistration.constants.php';

require_once 'parentchildregistration.civix.php';

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
    if (!checkTemplate($form->_eventId)) {
      return;
    }

    // Add JS
    CRM_Core_Region::instance('page-body')->add(array(
      'template' => 'CRM/Parentchildregistration/ParentChild.tpl',
    ));
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
    $child1 = $form->_values['participant']['participant_contact_id'];
    $participantId = $form->getVar('_participantId');

    $relatedContacts = [
      'parent1' => [
        'first_name' => $form->_values['params'][$participantId][PARENT1FN] ?: '',
        'last_name' => $form->_values['params'][$participantId][PARENT1LN] ?: '',
      ],
      'parent2' => [
        'first_name' => $form->_values['params'][$participantId][PARENT2FN] ?: '',
        'last_name' => $form->_values['params'][$participantId][PARENT2LN] ?: '',
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
    ];
    foreach ($relatedContacts as $person => $params) {
      if (empty($params['first_name']) && empty($params['last_name'])) {
        continue;
      }
      $dedupeParams = CRM_Dedupe_Finder::formatParams($params, 'Individual');
      $dedupeParams['check_permission'] = FALSE;
      $rule = CRM_Core_DAO::singleValueQuery("SELECT max(id) FROM civicrm_dedupe_rule_group WHERE name = 'Only_first_last_12'");
      $dupes = CRM_Dedupe_Finder::dupesByParams($dedupeParams, $type, NULL, array(), $rule);
      $cid = CRM_Utils_Array::value('0', $dupes, NULL);
      $params['contact_type'] = 'Individual';
      if ($cid) {
        $params['contact_id'] = $cid;
      }
      $contact[$person] = (array) civicrm_api3('Contact', 'create', $params)['id'];
    }

    // Create relationships
    $spouse = CRM_Core_DAO::getFieldValue('CRM_Contact_DAO_RelationshipType', 'Spouse of', 'id', 'name_a_b');
    $sibling = CRM_Core_DAO::getFieldValue('CRM_Contact_DAO_RelationshipType', 'Sibling of', 'id', 'name_a_b');
    $parent = CRM_Core_DAO::getFieldValue('CRM_Contact_DAO_RelationshipType', 'Parent of', 'id', 'name_a_b');

    // Create parent of relationship with first child.
    if (!empty($contact['parent1'])) {
      createRelationship($contact['parent1'][0], $child1, $parent);
    }

    if (!empty($contact['parent2'])) {
      createRelationship($contact['parent2'][0], $child1, $parent);
      createRelationship($contact['parent2'][0], $contact['parent1'][0], $spouse);
    }

    if (!empty($contact['child2'])) {
      createRelationship($contact['child2'][0], $child1, $sibling);
      if (!empty($contact['parent1'])) {
        createRelationship($contact['parent1'][0], $contact['child2'][0], $parent);
      }

      if (!empty($contact['parent2'])) {
        createRelationship($contact['parent2'][0], $contact['child2'][0], $parent);
      }
    }

    if (!empty($contact['child3'])) {
      createRelationship($contact['child3'][0], $child1, $sibling);
      if (!empty($contact['parent1'])) {
        createRelationship($contact['parent1'][0], $contact['child3'][0], $parent);
      }

      if (!empty($contact['parent2'])) {
        createRelationship($contact['parent2'][0], $contact['child3'][0], $parent);
      }

      if (!empty($contact['child2'])) {
        createRelationship($contact['child2'][0], $contact['child3'][0], $sibling);
      }
    }
  }
}

function createRelationship($cida, $cidb, $type) {
  $relationshipParams = array(
    "contact_id_a" => $cida,
    "contact_id_b" => $cidb,
    "relationship_type_id" => $type,
  );
  civicrm_api3("Relationship", "create", $relationshipParams);
}
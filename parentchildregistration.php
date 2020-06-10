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
    $templateId = civicrm_api3('Event', 'get', [
      'id' => $form->_eventId,
      'return.' . EVENT_TEMPLATE_ID => 1,
    ])['values'][$form->_eventId][EVENT_TEMPLATE_ID];

    if ($templateId) {
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
    $templateId = civicrm_api3('Event', 'get', [
      'id' => $form->_eventId,
      'return.' . EVENT_TEMPLATE_ID => 1,
    ])['values'][$form->_eventId][EVENT_TEMPLATE_ID];
    if (!$templateId) {
      return;
    }

    $varPrice = CRM_Core_Smarty::singleton()->get_template_vars('childPrice');
    if (!empty($fields['price_'.$varPrice])) {
      $priceVals = CRM_Core_Smarty::singleton()->get_template_vars('childPriceValues');
      foreach ($priceVals as $prices) {
        if ($fields['price_'.$varPrice] == $prices['id']) {
          switch ($prices['name']) {
           case 1:
              if (empty($fields[CHILD2FN]) && empty($fields[CHILD2LN])) {
                $errors[CHILD2FN] = ts('First and last name of child 2 must be entered.');
              }
            break;
            case 2:
              if (empty($fields[CHILD2FN]) && empty($fields[CHILD2LN])) {
                $errors[CHILD2FN] = ts('First and last name of child 2 must be entered.');
              }
              if (empty($fields[CHILD3FN]) && empty($fields[CHILD3LN])) {
                $errors[CHILD3FN] = ts('First and last name of child 3 must be entered.');
              }
            break;
            case 3:
              if (empty($fields[CHILD2FN]) && empty($fields[CHILD2LN])) {
                $errors[CHILD2FN] = ts('First and last name of child 2 must be entered.');
              }
              if (empty($fields[CHILD3FN]) && empty($fields[CHILD3LN])) {
                $errors[CHILD3FN] = ts('First and last name of child 3 must be entered.');
              }
              if (empty($fields[CHILD4FN]) && empty($fields[CHILD4LN])) {
                $errors[CHILD4FN] = ts('First and last name of child 4 must be entered.');
              }
            break;
          }
        }
      }
    }

/**
    if (!empty($fields[CHILDUNDER]) || !empty($fields[CHILDTHREE]) || !empty($fields[CHILDPLUS])) {
      $totalFields = $fields[CHILDUNDER] + $fields[CHILDTHREE] + $fields[CHILDPLUS];
      if ($totalFields == 1) {
        if (empty($fields[CHILD1FN]) && empty($fields[CHILD1LN])) {
          $errors[CHILD1FN] = ts('First and last name of child 1 must be entered.');
        }
      }
      if ($totalFields == 2) {
        if (empty($fields[CHILD1FN]) && empty($fields[CHILD1LN])) {
          $errors[CHILD1FN] = ts('First and last name of child 1 must be entered.');
        }
        if (empty($fields[CHILD2FN]) && empty($fields[CHILD2LN])) {
          $errors[CHILD2FN] = ts('First and last name of child 2 must be entered.');
        }
      }
      if ($totalFields == 3) {
        if (empty($fields[CHILD1FN]) && empty($fields[CHILD1LN])) {
          $errors[CHILD1FN] = ts('First and last name of child 1 must be entered.');
        }
        if (empty($fields[CHILD2FN]) && empty($fields[CHILD2LN])) {
          $errors[CHILD2FN] = ts('First and last name of child 2 must be entered.');
        }
        if (empty($fields[CHILD3FN]) && empty($fields[CHILD3LN])) {
          $errors[CHILD3FN] = ts('First and last name of child 3 must be entered.');
        }
      }
      if ($totalFields >= 4) {
        if (empty($fields[CHILD1FN]) && empty($fields[CHILD1LN])) {
          $errors[CHILD1FN] = ts('First and last name of child 1 must be entered.');
        }
        if (empty($fields[CHILD2FN]) && empty($fields[CHILD2LN])) {
          $errors[CHILD2FN] = ts('First and last name of child 2 must be entered.');
        }
        if (empty($fields[CHILD3FN]) && empty($fields[CHILD3LN])) {
          $errors[CHILD3FN] = ts('First and last name of child 3 must be entered.');
        }
        if (empty($fields[CHILD4FN]) && empty($fields[CHILD4LN])) {
          $errors[CHILD4FN] = ts('First and last name of child 4 must be entered.');
        }
      }
    }
    */

    if (!empty($fields[CHILDPRICE])) {
      switch ($fields[CHILDPRICE]) {
        case 19:
          if (empty($fields[CHILD2FN]) && empty($fields[CHILD2LN])) {
            $errors[CHILD2FN] = ts('First and last name of child 2 must be entered.');
          }
        break;
        case 20:
          if (empty($fields[CHILD2FN]) && empty($fields[CHILD2LN])) {
            $errors[CHILD2FN] = ts('First and last name of child 2 must be entered.');
          }
          if (empty($fields[CHILD3FN]) && empty($fields[CHILD3LN])) {
            $errors[CHILD3FN] = ts('First and last name of child 3 must be entered.');
          }
        break;
        case 21:
          if (empty($fields[CHILD2FN]) && empty($fields[CHILD2LN])) {
            $errors[CHILD2FN] = ts('First and last name of child 2 must be entered.');
          }
          if (empty($fields[CHILD3FN]) && empty($fields[CHILD3LN])) {
            $errors[CHILD3FN] = ts('First and last name of child 3 must be entered.');
          }
          if (empty($fields[CHILD4FN]) && empty($fields[CHILD4LN])) {
            $errors[CHILD4FN] = ts('First and last name of child 4 must be entered.');
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
  if ($formName == "CRM_Event_Form_ManageEvent_EventInfo" && !empty($form->getVar('_templateId'))) {
    $eventId = $form->get('id');
    civicrm_api3('CustomValue', 'create', [
      'entity_id' => $eventId,
      EVENT_TEMPLATE_ID => $form->getVar('_templateId'),
    ]);
  }
  if ($formName == "CRM_Event_Form_Registration_Confirm") {
    $templateId = civicrm_api3('Event', 'get', [
      'id' => $form->_eventId,
      'return.' . EVENT_TEMPLATE_ID => 1,
    ])['values'][$form->_eventId][EVENT_TEMPLATE_ID];
    if (!$templateId) {
      return;
    }
    $child1 = $form->_values['participant']['participant_contact_id'];
    $parentIds = [];
    $participantId = $form->getVar('_participantId');

    $relatedContacts = [
      'parent1' => [
        'first_name' => $form->_values['params'][$participantId][PARENT1FN] ?: '',
        'last_name' => $form->_values['params'][$participantId][PARENT1LN] ?: '',
        'email' => $form->_values['params'][$participantId][PARENT1EMAIL] ?: '',
      ],
      'parent2' => [
        'first_name' => $form->_values['params'][$participantId][PARENT2FN] ?: '',
        'last_name' => $form->_values['params'][$participantId][PARENT2LN] ?: '',
        'email' => $form->_values['params'][$participantId][PARENT2EMAIL] ?: '',
      ],
      'child2' => [
        'first_name' => $form->_values['params'][$participantId][CHILD2FN] ?: '',
        'last_name' => $form->_values['params'][$participantId][CHILD2LN] ?: '',
        'birth_date' => $form->_values['params'][$participantId][CHILD2DOB] ?: '',
        'email' => $form->_values['params'][$participantId][CHILD2EMAIL] ?: '',
      ],
      'child3' => [
        'first_name' => $form->_values['params'][$participantId][CHILD3FN] ?: '',
        'last_name' => $form->_values['params'][$participantId][CHILD3LN] ?: '',
        'birth_date' => $form->_values['params'][$participantId][CHILD3DOB] ?: '',
        'email' => $form->_values['params'][$participantId][CHILD3EMAIL] ?: '',
      ],
      'child4' => [
        'first_name' => $form->_values['params'][$participantId][CHILD4FN] ?: '',
        'last_name' => $form->_values['params'][$participantId][CHILD4LN] ?: '',
        'birth_date' => $form->_values['params'][$participantId][CHILD4DOB] ?: '',
        'email' => $form->_values['params'][$participantId][CHILD4EMAIL] ?: '',
      ],
    ];
    // Create relationships
    $sibling = CRM_Core_DAO::getFieldValue('CRM_Contact_DAO_RelationshipType', 'Sibling of', 'id', 'name_a_b');
    $childRel = CRM_Core_DAO::getFieldValue('CRM_Contact_DAO_RelationshipType', 'Child of', 'id', 'name_a_b');

    $children = civicrm_api3('Relationship', 'get', [
      'relationship_type_id' => $sibling,
      'contact_id_a' => $child1,
      'sequential' => 1,
      'options' => ['limit' => 3, 'sort' => 'id ASC'],
      'api.Contact.get' => ['id' => "\$value.contact_id_b", 'sequential' => 1],
    ])['values'];
    $parents = civicrm_api3('Relationship', 'get', [
      'relationship_type_id' => $childRel,
      'contact_id_a' => $child1,
      'sequential' => 1,
      'options' => ['limit' => 2, 'sort' => 'id ASC'],
      'api.Contact.get' => ['id' => "\$value.contact_id_b", 'sequential' => 1],
    ])['values'];
    $restoreInformation = ['id' => $child1];
    foreach ($relatedContacts as $person => $params) {
      if ($person == 'parent1' && !empty($parents[0])) {
        $relatedContacts[$person]['contact_id'] = $parent[0]['contact_id_b'];
        $restoreInformation[PARENT1FN] = $parents['api.Contact.get']['values'][0]['first_name'];
        $restoreInformation[PARENT1LN] = $parents['api.Contact.get']['values'][0]['last_name'];
      }
      if ($person == 'parent2' && !empty($parents[1])) {
        $relatedContacts[$person]['contact_id'] = $parent[1]['contact_id_b'];
        $restoreInformation[PARENT2FN] = $parents['api.Contact.get']['values'][1]['first_name'];
        $restoreInformation[PARENT2LN] = $parents['api.Contact.get']['values'][1]['last_name'];
      }
      if ($person == 'child2' && !empty($children[0])) {
        $relatedContacts[$person]['contact_id'] = $children[0]['contact_id_b'];
        $restoreInformation[CHILD2FN] = $children['api.Contact.get']['values'][0]['first_name'];
        $restoreInformation[CHILD2LN] = $children['api.Contact.get']['values'][0]['last_name'];
        $restoreInformation[CHILD2DOB] = $children['api.Contact.get']['values'][0]['birth_date'];
      }
      if ($person == 'child3' && !empty($children[1])) {
        $relatedContacts[$person]['contact_id'] = $children[1]['contact_id_b'];
        $restoreInformation[CHILD3FN] = $children['api.Contact.get']['values'][1]['first_name'];
        $restoreInformation[CHILD3LN] = $children['api.Contact.get']['values'][1]['last_name'];
        $restoreInformation[CHILD3DOB] = $children['api.Contact.get']['values'][1]['birth_date'];
      }
      if ($person == 'child4' && !empty($children[2])) {
        $relatedContacts[$person]['contact_id'] = $children[2]['contact_id_b'];
        $restoreInformation[CHILD4FN] = $children['api.Contact.get']['values'][2]['first_name'];
        $restoreInformation[CHILD4LN] = $children['api.Contact.get']['values'][2]['last_name'];
        $restoreInformation[CHILD4DOB] = $children['api.Contact.get']['values'][2]['birth_date'];
      }
    }
    foreach ($relatedContacts as $person => $params) {
      if (empty($params['first_name']) && empty($params['last_name'])) {
        continue;
      }
      $dedupeParams = CRM_Dedupe_Finder::formatParams($params, 'Individual');
      $dedupeParams['check_permission'] = FALSE;
      $rule = CRM_Core_DAO::singleValueQuery("SELECT max(id) FROM civicrm_dedupe_rule_group WHERE name = 'Child_Rule_8'");
      $dupes = CRM_Dedupe_Finder::dupesByParams($dedupeParams, 'Individual', NULL, array(), $rule);
      $cid = CRM_Utils_Array::value('0', $dupes, CRM_Utils_Array::value('contact_id', $params));
      $params['contact_type'] = 'Individual';
      if (in_array($person, ['child2', 'child3', 'child4'])) {
        $params['contact_sub_type'] = 'Child';
      }
      if ($cid) {
        $params['contact_id'] = $cid;
      }
      $contact[$person] = (array) civicrm_api3('Contact', 'create', $params)['id'];

      // Create participant record for child.
      $params =  [
        'contact_id' => $contact[$person][0],
        'event_id' => $form->_eventId,
        'registered_by_id' => $participantId,
        'status_id' => 'additional_participant',
        'role_id' => 1,
      ];
      // If the parent is staying add them so that they count.
      if ($person == 'parent1' && !empty($form->_values['params'][$participantId][PARENT1_STAYING])) {
        $params['status_id'] = 'Registered';
      }
      if ($person == 'parent2'  && !empty($form->_values['params'][$participantId][PARENT2_STAYING])) {
        $params['status_id'] = 'Registered';
      }
      civicrm_api3('Participant', 'create', $params);

      // Add address for child.
      $address = civicrm_api3('Address', 'get', ['contact_id' => $contact['parent1'][0]])['values'];
      if ($person !== 'parent1') {
        foreach ($address as $k => &$val) {
          $check = civicrm_api3('Address', 'get', ['contact_id' => $contact[$person][0], 'master_id' => $k]);
          if (!$check['count']) {
            unset($val['id']);
            $val['contact_id'] = $contact[$person][0];
            $val['master_id'] = $k;
            civicrm_api3('Address', 'create', $address[$k]);
          }
        }
      }
      else {
        foreach ($address as $k => &$val) {
          $check = civicrm_api3('Address', 'get', ['contact_id' => $child1, 'master_id' => $k]);
          if (!$check['count']) {
            unset($val['id']);
            $val['contact_id'] = $child1;
            $val['master_id'] = $k;
            civicrm_api3('Address', 'create', $address[$k]);
          }
        }
      }

      if (!empty($form->_values['params'][$participantId]['postal_code-Primary'])) {
/**
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
      **/
    }

    if (in_array($person, ['parent1', 'parent2'])) {
      $parentIds[] = $contact[$person][0];
    }

    // Relationships for child1 with parents
    foreach ($parentIds as $parentId) {
      createRelationship($child1, $parentId, $childRel);
    }

    // Relationships for rest of children with parents
    foreach ($contact as $person => $cid) {
      if (!empty($contact[$person])) {
        foreach ($parentIds as $parentId) {
          if (in_array($person, ['child2', 'child3', 'child4'])) {
            createRelationship($contact[$person][0], $parentId, $childRel);
          }
        }
      }
    }

    // Sibling relationships
    if (!empty($contact['child2'])) {
      createRelationship($child1, $contact['child2'][0], $sibling);
    }
    if (!empty($contact['child3'])) {
      createRelationship($child1, $contact['child3'][0], $sibling);
      createRelationship($contact['child2'][0], $contact['child3'][0], $sibling);
    }
    if (!empty($contact['child4'])) {
      createRelationship($child1, $contact['child4'][0], $sibling);
      createRelationship($contact['child2'][0], $contact['child4'][0], $sibling);
      createRelationship($contact['child3'][0], $contact['child4'][0], $sibling);
    }
  }

  //restore deleted (if any) custom field data
  civicrm_api3('Contact', 'create', $restoreInformation);
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
    'name' => 'Region',
    'return' => 'id',
    'custom_group_id' => "chapter_region",
  ));
  return [$chapterId, $regionId];
}

function setChapRegCodes($params, $existingCodes = []) {
  list($chapterId, $regionId) = getChapRegIds();

  if (!empty($params['chapter'])) {
    civicrm_api3('CustomValue', 'create', array(
      'entity_id' => $params['contact_id'],
      'custom_' . $chapterId => CRM_Core_DAO::VALUE_SEPARATOR . $params['chapter'] . CRM_Core_DAO::VALUE_SEPARATOR,
    ));
  }
  if (!empty($params['region'])) {
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

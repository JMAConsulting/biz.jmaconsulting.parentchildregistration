<?php
use CRM_Parentchildregistration_ExtensionUtil as E;

/**
 * Collection of upgrade steps.
 */
class CRM_Parentchildregistration_Upgrader extends CRM_Parentchildregistration_Upgrader_Base {

  public function upgrade_1100() {
    $this->ctx->log->info('Applying update 1.1');
    $sql = "INSERT IGNORE INTO civicrm_value_parent_child__17 (
      entity_id,
      parent_1_first_name_86,
      parent_1_last_name_87,
      parent_1_email_102,
      parent_2_first_name_88,
      parent_2_last_name_89,
      parent_2_email_103,
      child_2_first_name_90,
      child_2_last_name_91,
      child_2_email_104,
      child_2_dob_96,
      child_3_first_name_92,
      child_3_last_name_93,
      child_3_email_105,
      child_3_dob_97,
      child_4_first_name_94,
      child_4_last_name_95,
      child_4_email_106,
      child_4_dob_98)

      SELECT
      entity_id,
      parent_1_first_name_11,
      parent_1_last_name_12,
      parent_1_email_28,
      parent_2_first_name_13,
      parent_2_last_name_14,
      parent_2_email_29,
      child_2_first_name_15,
      child_2_last_name_16,
      child_2_email_31,
      child_2_dob_22,
      child_3_first_name_17,
      child_3_last_name_18,
      child_3_email_32,
      child_3_dob_23,
      child_4_first_name_19,
      child_4_last_name_20,
      child_4_email_33,
      child_4_dob_24
       FROM civicrm_value_parent_child__7
       WHERE civicrm_value_parent_child__7.entity_id IS NOT NULL
      ";
      CRM_Core_DAO::executeQuery($sql);

      return TRUE;
  }
}

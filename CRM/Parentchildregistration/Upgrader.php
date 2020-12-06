<?php
use CRM_Parentchildregistration_ExtensionUtil as E;

/**
 * Collection of upgrade steps.
 */
class CRM_Parentchildregistration_Upgrader extends CRM_Parentchildregistration_Upgrader_Base {

  public function upgrade_1100() {
    $this->ctx->log->info('Applying update 1.1');
    $sql = "INSERT IGNORE INTO civicrm_value_parent_child__19 (
      entity_id,
      parent_1_first_name_91,
      parent_1_last_name_92,
      parent_1_email_107,
      parent_2_first_name_93,
      parent_2_last_name_94,
      parent_2_email_108,
      child_2_first_name_95,
      child_2_last_name_96,
      child_2_email_109,
      child_2_dob_101,
      child_2_gender_104,
      child_3_first_name_97,
      child_3_last_name_98,
      child_3_email_110,
      child_3_dob_102,
      child_3_gender_105,
      child_4_first_name_99,
      child_4_last_name_100,
      child_4_email_111,
      child_4_dob_103,
      child_4_gender_106)

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
      child_2_gender_25,
      child_3_first_name_17,
      child_3_last_name_18,
      child_3_email_32,
      child_3_dob_23,
      child_3_gender_26,
      child_4_first_name_19,
      child_4_last_name_20,
      child_4_email_33,
      child_4_dob_24,
      child_4_gender_27
       FROM civicrm_value_parent_child__7
       WHERE civicrm_value_parent_child__7.entity_id IS NOT NULL
      ";
      CRM_Core_DAO::executeQuery($sql);

      return TRUE;
  }
}

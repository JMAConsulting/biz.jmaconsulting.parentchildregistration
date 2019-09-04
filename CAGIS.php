<?php

Class CRM_CAGIS_Import {

  public $civicrmPath = '/home/cagis.jmaconsulting.biz/htdocs/wp-content/plugins/civicrm/civicrm/';
  public $sourceContactId = '';

  function __construct() {
    // you can run this program either from an apache command, or from the cli
    $this->initialize();
  }

  function initialize() {
    $civicrmPath = $this->civicrmPath;
    require_once $civicrmPath .'civicrm.config.php';
    require_once $civicrmPath .'CRM/Core/Config.php';
    $config = CRM_Core_Config::singleton();
  }

  function createWPUser() {
    $totalCount = civicrm_api3('Contact', 'getcount', [
      'contact_type' =>  'Individual',
    ]);
    $batchTotal = 100;
    $count = 0;
    $config = CRM_Core_Config::singleton();

    while($batchTotal <= $totalCount)  {
      $contacts = civicrm_api3('Contact', 'get', [
        'contact_type' =>  'Individual',
        'sequential' => 1,
        'options' => [
          'limit' => $batchTotal,
          'offset' => $count,
        ],
      ])['values'];
      foreach ($contacts as $contact) {
        $ufID = CRM_Core_DAO::getFieldValue('CRM_Core_BAO_UFMatch', $contact['id'], 'uf_id', 'contact_id');
        if (!$ufID) {
          $cmsName = strtolower($contact['first_name'] . '.' . $contact['last_name'] . '.' . $contact['id']);
          $params = [
            'cms_pass' => 'changeme',
            'cms_name' => $cmsName,
            'email' => $contact['email'],
          ];
          CRM_Core_BAO_CMSUser::create($params, 'email');
        }
      }
      $count += $batchTotal;
      $batchTotal += 101;
    }
  }

}

$import = new CRM_CAGIS_Import();
$import->createWPUser();

<?php
  define('CIVICRM_CMSDIR', '/home/girlsinscience.ca/htdocs/');

Class CRM_CAGIS_Import {

  public $civicrmPath = '/home/girlsinscience.ca/htdocs/wp-content/plugins/civicrm/civicrm/';
  public $sourceContactId = '';
 // define('CIVICRM_CMSDIR', '/home/girlsinscience.ca/htdocs/');

  function __construct() {
    // you can run this program either from an apache command, or from the cli
    $this->initialize();
  }

  function initialize() {
//define('CIVICRM_CMSDIR', '/home/girlsinscience.ca/htdocs/');  
 $civicrmPath = $this->civicrmPath;
    require_once $civicrmPath .'civicrm.config.php';
    require_once $civicrmPath .'CRM/Core/Config.php';
    $config = CRM_Core_Config::singleton();
  }

  function createWPUser() {
    $totalCount = civicrm_api3('Contact', 'getcount', [
      'contact_type' =>  'Individual',
    ]);
//$totalCount = 4;
    $batchTotal = 50;
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
        if (empty($contact['email'])) {continue;}
        $ufID = CRM_Core_DAO::getFieldValue('CRM_Core_BAO_UFMatch', $contact['id'], 'uf_id', 'contact_id');
        //if (!$ufID && !empty($contact['email'])) {
          $ufID = $ufID ?: CRM_Core_DAO::getFieldValue('CRM_Core_BAO_UFMatch', $contact['email'], 'uf_id', 'uf_name');
          $ufID = CRM_Core_DAO::singleValueQuery("SELECT MAX(uf_id) FROM civicrm_uf_match WHERE uf_name =  '" . $contact['email'] . "' ");       
 //}
        if (!$ufID) {
          $cmsName = strtolower($contact['first_name'] . '.' . $contact['last_name'] . '.' . $contact['id']);
          $params = [
            'contactID' => $contact['id'],
            'cms_pass' => 'changeme',
            'cms_name' => $cmsName,
            'email' => $contact['email'],
          ];
try{ 
         CRM_Core_BAO_CMSUser::create($params, 'email');
}
catch (CRM_Core_Exception $e) {}
        }
print_r($ufID);
      }
      //print_r($ufID);
      $count += $batchTotal;
      $batchTotal += 51;
    }
  }

}

$import = new CRM_CAGIS_Import();
$import->createWPUser();

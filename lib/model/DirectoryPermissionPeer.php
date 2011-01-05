<?php

  // include base peer class
  require_once 'model/om/BaseDirectoryPermissionPeer.php';

  // include object class
  include_once 'model/DirectoryPermission.php';


/**
 * @package    model
 */
class DirectoryPermissionPeer extends BaseDirectoryPermissionPeer {
  
  public static function getPermissionsByFileName($sFileName) {
    $oCriteria = new Criteria();
    $oCriteria->add(self::FILENAME, $sFileName);
    return self::doSelect($oCriteria);
  }
  
  public static function getPermissionGroupIdsByFileName($sFileName) {
    $aResult = array();
    foreach(self::getPermissionsByFileName($sFileName) as $oPermission) {
      $aResult[] = $oPermission->getGroupId();
    }
    return $aResult;
  }
}


<?php

  // include base peer class
  require_once 'model/om/BaseWebdavLockPeer.php';

  // include object class
  include_once 'model/WebdavLock.php';


/**
 * @package    model
 */
class WebdavLockPeer extends BaseWebdavLockPeer {
	
  public static function removeOutdatedLocks() {
    $oCriteria = new Criteria();
    $oCriteria->add(self::EXPIRES_AT, self::EXPIRES_AT.' < NOW()', Criteria::CUSTOM);
    return self::doDelete($oCriteria);
  }
}


<?php

require_once 'model/om/BaseWebdavLock.php';


/**
 * @package    model
 */
class WebdavLock extends BaseWebdavLock {
  public function save(PropelPDO $oConnection = null) {
    $this->setUpdatedAt(date('c'));

    if($this->isNew()) {
      if(Session::getSession()->isAuthenticated()) {
        $this->setOwner(Session::getSession()->getUserId());
      }
      $this->setCreatedAt(date('c'));
    }
    return parent::save($oConnection);
  }
}


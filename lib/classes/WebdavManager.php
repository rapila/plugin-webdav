<?php

class WebdavManager extends Manager {
  public function render() {
    $sDirectory = MAIN_DIR."/".Settings::getSetting('webdav', 'base_dir', '.');
		$sRealPathDir = realpath($sDirectory);
		if($sRealPathDir === false) {
			throw new Exception("Error in WedavManager:: directory $sDirectory does not exist");
		}
    $oServer = new WebDAVServer($sRealPathDir);
    // ob_start(array($this, 'log'), 512);
    $oServer->ServeRequest();
    // ob_end_flush();
  }
  
  public function log($sInput) {
    error_log("RESPONSE: ".ErrorHandler::readableDump($sInput));
    return $sInput;
  }
}
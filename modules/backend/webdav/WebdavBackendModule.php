<?php
class WebdavBackendModule extends BackendModule {
  
  // base dir where the protected data excange dirs are located
  private $sWebdavBaseDirPath;
  
  // file path of directory
  private $sFilePath;
  
  // directories that exist
  private $aFiles;
  
  private $iError;
  private $sErrorLocation;
  const ERROR_BASE_DIR_CONFIG = 1;
  const ERROR_BASE_DIR_PATH = 2;
  const ERROR_BASE_DIR_PERMISSION = 3;
  
  const NEW_DIR_IDENTIFIER = 'new_webdav_dir';
  
  public function __construct() {
    $this->initialize();
    if(Manager::hasNextPathItem()) {
      $this->sFilePath = Manager::peekNextPathItem();
    }
  }
  /**
   * initialize() 
   * check configuration and existence of base webdav directory
   */
  private function initialize() {
    $this->sWebdavBaseDirPath = Settings::getSetting('webdav', 'base_dir', null);
    if($this->sWebdavBaseDirPath === null) {
      $this->iError = self::ERROR_BASE_DIR_CONFIG;
    } else {
      $this->sWebdavBaseDirPath = MAIN_DIR."/$this->sWebdavBaseDirPath";
      if(file_exists($this->sWebdavBaseDirPath) && !@is_dir($this->sWebdavBaseDirPath)) {
        $this->iError = self::ERROR_BASE_DIR_PATH;
      }
      if(!file_exists($this->sWebdavBaseDirPath)) {
        if(!@mkdir($this->sWebdavBaseDirPath)) {
          $this->iError = self::ERROR_BASE_DIR_PERMISSION;
        }
      } elseif(is_writable($this->sWebdavBaseDirPath) === false) {
        $this->iError = self::ERROR_BASE_DIR_PERMISSION;
      }
    }
    if($this->iError) {
      $this->sErrorLocation = __CLASS__."->".__METHOD__;
    }
    if($this->iError === null) {
      $this->sWebdavBaseDirPath = realpath($this->sWebdavBaseDirPath);
      $this->aFiles = array_keys(ResourceFinder::getFolderContents($this->sWebdavBaseDirPath));
    }
  }

  public function getChooser() {
    $oTemplate = $this->constructTemplate('list');
    if($this->iError === null && is_array($this->aFiles) && count($this->aFiles) > 0) {
      $this->parseTree($oTemplate, ArrayUtil::arrayWithValuesAsKeys($this->aFiles), $this->sFilePath);
    } else {
      $oTemplate->replaceIdentifier('tree', TagWriter::quickTag('p', array(), StringPeer::getString('webdav.no_subdirectories')));
    }
    return $oTemplate;
  }
  
  public function getDetail() {
    // display error info
    if($this->iError) {
      $oTemplate = $this->constructTemplate('error_message');
      $oTemplate->replaceIdentifier('error_title', StringPeer::getString('webdav.error_message').' '.$this->sErrorLocation);
      $oTemplate->replacePstring("error_details", array('dir_name' => $this->sWebdavBaseDirPath), 'webdav.error_'.$this->iError);
      return $oTemplate;
    }
    // display module info
    if($this->sFilePath === null) {
      $oTemplate = $this->constructTemplate('module_info');
      if(count($this->aFiles) > 0) {
        $oTemplate->replaceIdentifier('edit_or_create_message', StringPeer::getString('webdav.choose_or_create'));
      }
      $oTemplate->replaceIdentifier('create_link', TagWriter::quickTag('a', array('class' => 'edit_related_link highlight', 'href' => LinkUtil::link('webdav', null, array('action' => 'create'))), StringPeer::getString('webdav.create')));
      $oTemplate->replaceIdentifier('user_backend_link', TagWriter::quickTag('a', array('class' => 'edit_related_link highlight', 'href' => LinkUtil::link('users')), StringPeer::getString('module.backend.users')));
      return $oTemplate;
    }

    $oTemplate = $this->constructTemplate('detail');
    $oTemplate->replaceIdentifier('module_info_link', TagWriter::quickTag('a', array('title' => StringPeer::getString('module_info'), 'class' => 'info', 'href' => LinkUtil::link('webdav', null, array('get_module_info' => 'true')))));
    if($this->sFilePath === self::NEW_DIR_IDENTIFIER) {
      $aDirPermissionsGroupIds = array();
    } else {
      $aDirPermissionsGroupIds = DirectoryPermissionPeer::getPermissionGroupIdsByFileName($this->sFilePath);
      $oDeleteTemplate = $this->constructTemplate("delete_button", true);
      $oDeleteTemplate->replacePstring("delete_item", array('name' => $this->sFilePath));
      $oTemplate->replaceIdentifier("delete_button", $oDeleteTemplate, null, Template::LEAVE_IDENTIFIERS);
      // show users with usergroups
      $oUsersWithGroups = UserPeer::getUsersWithRights($aDirPermissionsGroupIds);
      $oUsersTemplate = $this->constructTemplate('detail_users');
      if(count($oUsersWithGroups) > 0) {
        foreach($oUsersWithGroups as $oUser) {
          $oUsersTemplate->replaceIdentifierMultiple('users', TagWriter::quickTag('a', array('class' => 'highlight', 'title' => StringPeer::getString('user.edit'), 'class' => 'webdav_files', 'href' => LinkUtil::link(array('users', $oUser->getId()), null, array('check_userkind' => true))), $oUser->getFullName().' [Benutzername:'.$oUser->getUserName().']'));
        }
      } else {
        $oUsersTemplate->replaceIdentifier('users', TagWriter::quickTag('div', array('class' => 'webdav_files'), StringPeer::getString('webdav.no_users_with_permission_message')));
      }
      $oUsersTemplate->replaceIdentifier('user_backend_link', TagWriter::quickTag('a', array('class' => 'edit_related_link', 'href' => LinkUtil::link('users', null, array('check_userkind' => 'true'))), StringPeer::getString('user.edit')));
      $oTemplate->replaceIdentifier("users_with_permission", $oUsersTemplate);
      
      $sServerPath = LinkUtil::absoluteLink(substr($this->sWebdavBaseDirPath, strrpos($this->sWebdavBaseDirPath, '/')).'/'.$this->sFilePath);
      $oTemplate->replaceIdentifier("server_address", $sServerPath);

      // show files in current dir
      $aDirFiles = array_keys(ResourceFinder::getFolderContents($this->sWebdavBaseDirPath.'/'.$this->sFilePath));
      if(count($aDirFiles) > 0) {
        $oFilesTemplate = $this->constructTemplate("detail_files");
        $sWebDavDirPath = $this->sWebdavBaseDirPath.'/'.$this->sFilePath.'/';
        foreach($aDirFiles as $i => $sFilePath) {
          $iFileSize = filesize($sWebDavDirPath.$sFilePath);
          $sFileSize = DocumentUtil::getDocumentSize($iFileSize);
          if(substr($sFileSize, 0, 1) == '0') {
            $sFileSize = 'unknown';
          }            
          $oFilesTemplate->replaceIdentifierMultiple('files', TagWriter::quickTag('div', array('class' => 'webdav_files'), $sFilePath.' ['.$sFileSize.']'));
        }
        $oTemplate->replaceIdentifier('detail_files', $oFilesTemplate);
      }
    }
    $oTemplate->replaceIdentifier('name', $this->sFilePath);
    $oTemplate->replaceIdentifier('file_path_old', $this->sFilePath);
    if(isset($_POST['file_path'])) {
      $this->sFilePath = $_POST['file_path'];
    }
    $oTemplate->replaceIdentifier('file_path', $this->sFilePath === self::NEW_DIR_IDENTIFIER ? '' :  $this->sFilePath);
    $oTemplate->replaceIdentifier('file_path_readonly', $this->sFilePath != self::NEW_DIR_IDENTIFIER ? ' readonly="readonly"' : '', null, Template::NO_HTML_ESCAPE);
    $aGroups = GroupPeer::getAll();
    $oTemplate->replaceIdentifier('group_options', TagWriter::optionsFromObjects($aGroups, 'getId', 'getName', $aDirPermissionsGroupIds, array()));
    $oTemplate->replaceIdentifier('group_backend_link', TagWriter::quickTag('a', array('class' => 'edit_related_link highlight', 'href' => LinkUtil::link('groups')), StringPeer::getString('group.edit')));

    return $oTemplate;
  }
  
  public function validateForm($oFlash) {
    $oFlash->checkForValue('file_path');
    if($_POST['file_path'] != null) {
      $sFilePath = self::normalize($_POST['file_path']);
      $_POST['file_path'] = $sFilePath;
      if($sFilePath !== $this->sFilePath) {
        if(in_array($sFilePath, $this->aFiles)) {
          $oFlash->addMessage('dirname_exists');
        }
      }
    }
  }
  
  public function create() {
    $this->sFilePath = self::NEW_DIR_IDENTIFIER;
  }
  
  private static function normalize($sFilePath) {
    return StringUtil::normalize($sFilePath, '_');
  }
  
  public function save() {
    if(Flash::noErrors()) {    
      $this->sFilePath = self::normalize($_POST['file_path']);
      if($_POST['file_path_old'] == self::NEW_DIR_IDENTIFIER) {
        mkdir("$this->sWebdavBaseDirPath/$this->sFilePath");
      } else {
        if($this->sFilePath !== $_POST['file_path_old']) {
          rename($this->sWebdavBaseDirPath."/".$_POST['file_path_old'], "$this->sWebdavBaseDirPath/$this->sFilePath");
        }
      }
      // delete all old groups
      foreach(DirectoryPermissionPeer::getPermissionsByFileName($this->sFilePath) as $oPermission) {
        $oPermission->delete();
      }
      if(isset($_POST['group_ids'])) {
        foreach($_POST['group_ids'] as $sGroupId) {
          $oDirectoryPermisson = new DirectoryPermission();
          $oDirectoryPermisson->setFileName($this->sFilePath);
          $oDirectoryPermisson->setGroupId($sGroupId);
          $oDirectoryPermisson->setCreatedBy(Session::getSession()->getUserId());
          $oDirectoryPermisson->setCreatedAt(date('c'));
          $oDirectoryPermisson->save();
        }
      }
      LinkUtil::redirect($this->link($this->sFilePath));
    }
  }
  
  public function delete() {
    $aDirPermissionsGroups = DirectoryPermissionPeer::getPermissionsByFileName($this->sFilePath);
    foreach($aDirPermissionsGroups as $oDirPermGroup) {
      $oDirPermGroup->delete();
    }
    ResourceFinder::recursiveUnlink("$this->sWebdavBaseDirPath/$this->sFilePath");
    LinkUtil::redirect($this->link());  
  }
  
  public function customCss() {
    return "#detail fieldset.special_info { background-color: #f6f7c3; }";
  }
  
  public function getNewEntryActionParams() {
    return array('action' => $this->link(self::NEW_DIR_IDENTIFIER));
  }
}

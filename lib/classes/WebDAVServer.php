<?php
require_once("HTTP/WebDAV/Server.php");

class WebDAVServer extends HTTP_WebDAV_Server {
	private $sBasePath;
	private $aWebdavPermissions = array();

	public function __construct($sBasePath) {
		$this->sBasePath = $sBasePath;
		parent::__construct();
		$this->path = implode('/', Manager::getRequestPath());
		$this->base_uri = LinkUtil::absoluteLink('');
		$this->uri = LinkUtil::absoluteLink(LinkUtil::link());
		$this->_SERVER['SCRIPT_NAME'] = LinkUtil::link();
	}

	public function checkAuth($sType, $sUserName, $sPassword) {
		$bIsLoggedIn = Session::getSession()->isAuthenticated();
		if(!$bIsLoggedIn) {
			$iLogin = Session::getSession()->loginUsingDigest();
			$bIsLoggedIn = ($iLogin & Session::USER_IS_VALID) === Session::USER_IS_VALID;
		}

		if($bIsLoggedIn) {
			if(!Session::getSession()->getUser()->getIsAdmin()) {
				foreach(Session::getSession()->getUser()->getGroups() as $oGroup) {
					if($oGroup->getName() === Settings::getSetting('webdav', 'privileged_group', 'webdav.*')) {
						$this->aWebdavPermissions = true;
						break;
					} else {
						foreach($oGroup->getDirectoryPermissions() as $oWebdavPermission) {
							if(!isset($this->aWebdavPermissions[$oWebdavPermission->getFilename()])) {
								$this->aWebdavPermissions[$oWebdavPermission->getFilename()] = true;
							}
						}
					}
				}
			} else {
				$this->aWebdavPermissions = true;
			}
		} else {
			Session::startDigest();
			exit;
		}

		return $bIsLoggedIn;
	}

	public function PROPFIND(&$aOptions, &$aFiles) {
		// get absolute fs path to requested resource
		$sFullPath = $this->absolutePath($aOptions["path"]);

		// sanity check
		if (!file_exists($sFullPath)) {
			return false;
		}
				
		// prepare property array
		$aFiles["files"] = array();

		// store information for the requested path itself
		$aFiles["files"][] = $this->fileInfo($aOptions["path"], $sFullPath);

		// information for contained resources requested?
		if (!empty($aOptions["depth"])) { // TODO check for is_dir() first?;
			// make sure path ends with '/'
			$aOptions["path"] = $this->_slashify($aOptions["path"]);

			// try to open directory
			$handle = @opendir($sFullPath);

			if ($handle) {
				// ok, now get all its contents
				while ($filename = readdir($handle)) {
					if($filename === '.' || $filename === '..') {
						continue;
					}
										
					//Add the file
					if(!$this->hasReadAccess($aOptions["path"].$filename)) {
						continue;
					}
					$aFiles["files"][] = $this->fileInfo($aOptions["path"].$filename, "$sFullPath/$filename");
				}
			}
		}

		// ok, all done
		return true;
	}

	/**
	* GET method handler
	*
	* @param	array	 parameter passing array
	* @return bool	 true on success
	*/
	function GET(&$aOptions) {
		// get absolute fs path to requested resource
		$sFullPath = $this->absolutePath($aOptions["path"]);
		
		// sanity check
		if (!file_exists($sFullPath)) return false;
		
		if(!$this->hasReadAccess($aOptions['path'])) {
			return false;
		}
		
		// is this a collection? A WebDAV client should never call this…
		if (is_dir($sFullPath)) {
			return $this->getDir($sFullPath, $aOptions);
		}

		// detect resource type
		$aOptions['mimetype'] = ResourceFinder::mimeTypeOfFile($sFullPath);

		// detect modification time
		// see rfc2518, section 13.7
		// some clients seem to treat this as a reverse rule
		// requiring a Last-Modified header if the getlastmodified header was set
		$aOptions['mtime'] = filemtime($sFullPath);

		// detect resource size
		$aOptions['size'] = filesize($sFullPath);

		// no need to check result here, it is handled by the base class
		$aOptions['stream'] = fopen($sFullPath, "r");

		return true;
	}

	/**
	* PUT method handler
	*
	* @param	array	 parameter passing array
	* @return bool	 true on success
	*/
	public function PUT(&$aOptions) {
		$sFullPath = $this->absolutePath($aOptions["path"]);

		if(!$this->hasWriteAccess($aOptions['path'])) {
			error_log($aOptions['path']);
			return false;
		}
		
		if (!@is_dir(dirname($sFullPath))) {
			return "409 Conflict";
		}

		$aOptions["new"] = !file_exists($sFullPath);
		
		$bIsChunked = strtolower(@$this->_SERVER['HTTP_TRANSFER_ENCODING']) === 'chunked';
		
		$fp = fopen($sFullPath, "w");
		
		if($bIsChunked && isset($this->_SERVER['HTTP_X_EXPECTED_ENTITY_LENGTH'])) {
			$aOptions['content_length'] = $this->_SERVER['HTTP_X_EXPECTED_ENTITY_LENGTH'];
		}
		
		return $fp;
	}

	/**
	 * MKCOL method handler
	 *
	 * @param	 array	general parameter passing array
	 * @return bool		true on success
	 */
	public function MKCOL($aOptions) {
		$sFullPath = $this->absolutePath($aOptions["path"]);
		$parent = dirname($sFullPath);
		$name		= basename($sFullPath);

		if(!$this->hasWriteAccess($aOptions['path'])) {
			return '403 Forbidden';
		}

		if (!file_exists($parent)) {
			return "409 Conflict";
		}

		if (!is_dir($parent)) {
			return "403 Forbidden";
		}

		if (file_exists("$parent/$name")) {
			return "405 Method not allowed";
		}

		if (!empty($this->_SERVER["CONTENT_LENGTH"])) { // no body parsing yet
			return "415 Unsupported media type";
		}

		$stat = mkdir($parent."/".$name, 0777);
		if (!$stat) {
			return "403 Forbidden";
		}

		return ("201 Created");
	}

	/**
	 * DELETE method handler
	 *
	 * @param	 array	general parameter passing array
	 * @return bool		true on success
	 */
	public function DELETE($aOptions) {
		$sFullPath = $this->absolutePath($aOptions["path"]);
		
		//Top-level directories may not be deleted by non-admins
		$bIsTopLevel = count(explode('/', $aOptions['path'])) <= 1;
		if($bIsTopLevel && !$this->hasWriteAccess('')) {
			return '403 Forbidden';
		}
		
		if($bIsTopLevel) {
			$oCriteria = new Criteria();
			$oCriteria->add(DirectoryPermissionPeer::FILENAME, $aOptions['path']);
			DirectoryPermissionPeer::doDelete($oCriteria);
		}

		if(!$this->hasWriteAccess($aOptions['path'])) {
			return '403 Forbidden';
		}
		
		if(!file_exists($sFullPath)) {
			return "404 Not found";
		}

		ResourceFinder::recursiveUnlink($sFullPath);

		return "204 No Content";
	}

	/**
	 * MOVE method handler
	 *
	 * @param	 array	general parameter passing array
	 * @return bool		true on success
	 */
	public function MOVE($aOptions) {
		return $this->COPY($aOptions, true);
	}

	/**
	 * COPY method handler
	 *
	 * @param	 array	general parameter passing array
	 * @return bool		true on success
	 */
	public function COPY($aOptions, $del = false) {
		if (!empty($this->_SERVER["CONTENT_LENGTH"])) { // no body parsing yet
			return "415 Unsupported media type";
		}

		// no copying to different WebDAV Servers yet
		if (isset($aOptions["dest_url"])) {
			return "502 bad gateway";
		}

		$sFullPath = $this->absolutePath($aOptions["path"]);
		if (!file_exists($sFullPath)) return "404 Not found";

		if(!$this->hasWriteAccess($aOptions['path']) || !$this->hasWriteAccess($aOptions['dest'])) {
			return '403 Forbidden';
		}
		
		$dest = $this->absolutePath($aOptions["dest"]);
		$new			= !file_exists($dest);
		$existing_col = false;

		if (!$new) {
			if ($del && is_dir($dest)) {
				if (!$aOptions["overwrite"]) {
					return "412 precondition failed";
				}
				$dest .= basename($sFullPath);
				if (file_exists($dest)) {
					$aOptions["dest"] .= basename($sFullPath);
				} else {
					$new			= true;
					$existing_col = true;
				}
			}
		}

		if (!$new) {
			if ($aOptions["overwrite"]) {
				$stat = $this->DELETE(array("path" => $aOptions["dest"]));
				if (($stat{0} != "2") && (substr($stat, 0, 3) != "404")) {
					return $stat;
				}
			} else {
				return "412 precondition failed";
			}
		}

		if (is_dir($sFullPath) && ($aOptions["depth"] != "infinity")) {
			// RFC 2518 Section 9.2, last paragraph
			return "400 Bad request";
		}

		if ($del) {
			if (!rename($sFullPath, $dest)) {
				return "500 Internal server error";
			}
			$destpath = $this->_unslashify($aOptions["dest"]);
		} else {
			if (is_dir($sFullPath)) {
				$aFiles = System::find($sFullPath);
				$aFiles = array_reverse($aFiles);
			} else {
				$aFiles = array($sFullPath);
			}

			if (!is_array($aFiles) || empty($aFiles)) {
				return "500 Internal server error";
			}

			foreach ($aFiles as $file) {
				if (is_dir($file)) {
					$file = $this->_slashify($file);
				}

				$destfile = str_replace($sFullPath, $dest, $file);

				if (is_dir($file)) {
					if (!is_dir($destfile)) {
						// TODO "mkdir -p" here? (only natively supported by PHP 5)
						if (!@mkdir($destfile)) {
							return "409 Conflict";
						}
					}
				} else {
					if (!@copy($file, $destfile)) {
						return "409 Conflict";
					}
				}
			}
		}

		return ($new && !$existing_col) ? "201 Created" : "204 No Content";
	}

	/**
	 * PROPPATCH method handler
	 *
	 * @param	 array	general parameter passing array
	 * @return bool		true on success
	 */
	public function PROPPATCH(&$aOptions) {
		$msg	= "";
		$path = $aOptions["path"];
		$dir	= dirname($path)."/";
		$base = basename($path);

		if(!$this->hasWriteAccess($aOptions['path'])) {
			return '403 Forbidden';
		}
		
		foreach ($aOptions["props"] as $key => $prop) {
			if ($prop["ns"] == "DAV:") {
				$aOptions["props"][$key]['status'] = "403 Forbidden";
			} else {
				return false;
			}
		}

		return "";
	}

	/**
	 * LOCK method handler
	 *
	 * @param	 array	general parameter passing array
	 * @return bool		true on success
	 */
	function LOCK(&$aOptions) {
		// get absolute fs path to requested resource
		$sFullPath = $this->absolutePath($aOptions["path"]);

		// TODO recursive locks on directories not supported yet
		if (is_dir($sFullPath) && !empty($aOptions["depth"])) {
			return "409 Conflict";
		}
		
		if(!$this->hasWriteAccess($aOptions['path'])) {
			return '403 Forbidden';
		}
		
		$aOptions["timeout"] = time()+300; // 5min. hardcoded
		
		$aOptions['owner'] = Session::getSession()->getUser()->getUsername();

		if (isset($aOptions["update"])) { // Lock Update
			$oCriteria = new Criteria();
			$oCriteria->add(WebdavLockPeer::PATH, $aOptions[path]);
			$oCriteria->add(WebdavLockPeer::TOKEN, $aOptions[update]);
			$oLock = WebdavLockPeer::doSelectOne($oCriteria);
			
			if($oLock === null) {
				return false;
			}
			
			$oLock->setExpiresAt($aOptions['timeout']);

			$aOptions['owner'] = $oLock->getUser()->getUsername();
			$aOptions['scope'] = $oLock->getIsExclusive() ? "exclusive" : "shared";
			$aOptions['type']	 = $oLock->getIsExclusive() ? "write"	 : "read";

			$oLock->save();
			
			return true;
		}
		
		$oLock = new WebdavLock();
		$oLock->setToken($aOptions['locktoken']);
		$oLock->setPath($aOptions['path']);
		$oLock->setExpiresAt($aOptions['timeout']);
		$oLock->setIsExclusive($aOptions['scope'] === "exclusive");
		try {
			$oLock->save();
		} catch (Exception $e) {
			return "409 Conflict";
		}
		
		return "200 OK";
	}

	/**
	 * UNLOCK method handler
	 *
	 * @param	 array	general parameter passing array
	 * @return bool		true on success
	 */
	function UNLOCK(&$aOptions) {
		if(!$this->hasWriteAccess($aOptions['path'])) {
			return '403 Forbidden';
		}
		$oCriteria = new Criteria();
		$oCriteria->add(WebdavLockPeer::PATH, $aOptions['path']);
		$oCriteria->add(WebdavLockPeer::TOKEN, $aOptions['token']);
		$iCount = WebdavLockPeer::doDelete($oCriteria);

		return $iCount > 0 ? "204 No Content" : "409 Conflict";
	}

	/**
	 * checkLock() helper
	 *
	 * @param	 string resource path to check for locks
	 * @return bool		true on success
	 */
	function checkLock($path) {
		WebdavLockPeer::removeOutdatedLocks();
		$oCriteria = new Criteria();
		$oCriteria->add(WebdavLockPeer::PATH, $path);
		$oLock = WebdavLockPeer::doSelectOne($oCriteria);
		if($oLock === null) {
			return false;
		}

		return array("type" => $oLock->getIsExclusive() ? "write" : 'read', "scope" => $oLock->getIsExclusive() ? "exclusive" : "shared", "depth" => 0, "owner" => $oLock->getUser()->getUsername(), "token" => $oLock->getToken(), "created" => $oLock->getCreatedAt('U'), "modified" => $oLock->getUpdatedAt('U'), "expires" => $oLock->getExpiresAt('U'));
	}

	/**
	 * GET method handler for directories
	 *
	 * This is a very simple mod_index lookalike.
	 * See RFC 2518, Section 8.4 on GET/HEAD for collections
	 *
	 * @param	 string	 directory path
	 * @return void	 function has to handle HTTP response itself
	 */
	private function getDir($sFullPath, &$aOptions) {
		if(!StringUtil::endsWith(Manager::getOriginalPath(), '/')) {
			LinkUtil::redirect($this->_slashify(LinkUtil::link($aOptions["path"])));
		}
		
		// fixed width directory column format
		$format = "%15s	 %-19s	%-s\n";
		
		$handle = @opendir($sFullPath);
		if (!$handle) {
			return false;
		}

		echo "<html><head><title>Index of ".htmlspecialchars($aOptions['path'])."</title></head>\n";

		echo "<h1>Index of ".htmlspecialchars($aOptions['path'])."</h1>\n";

		echo "<pre>";
		printf($format, "Size", "Last modified", "Filename");
		echo "<hr>";
		
		while ($filename = readdir($handle)) {
			if($filename === '.') {
				continue;
			}
			if(!$this->hasReadAccess($aOptions["path"].'/'.$filename)) {
				continue;
			}
			//Add the file
			$sSubPath = $sFullPath."/".$filename;
			$name = htmlspecialchars($filename);
			$name_href = htmlspecialchars(rawurlencode($filename));
			printf($format, number_format(filesize($sSubPath)), strftime("%Y-%m-%d %H:%M:%S", filemtime($sSubPath)), "<a href='$name_href'>$name</a>");
		}

		echo "</pre>";

		closedir($handle);

		echo "</html>\n";

		exit;
	}

	private function absolutePath($sPath) {
		if($sPath[0] == '/') {
			return "$this->sBasePath$sPath";
		}
		return $this->_slashify($this->sBasePath).$sPath;
	}

	/* Get properties for a single file/resource
	*
	* @param	string	resource path
	* @return array		resource properties
	*/
	private function fileInfo($sPath, $sFullPath) {
		// create result array
		$aInfo = array();
		// TODO remove slash append code when base class is able to do it itself
		$aInfo["path"] = str_replace(rawurlencode('/'), '/', rawurlencode($sPath));
		$aInfo["path"] = is_dir($sFullPath) ? $this->_slashify($aInfo["path"]) : $aInfo["path"];
		$aInfo["props"] = array();

		// no special beautified displayname here ...
		$aInfo["props"][] = $this->mkprop("displayname", rawurlencode(basename($sPath)));

		// creation and modification time
		$aInfo["props"][] = $this->mkprop("creationdate",	 filectime($sFullPath));
		$aInfo["props"][] = $this->mkprop("getlastmodified", filemtime($sFullPath));

		// type and size (caller already made sure that path exists)
		if (is_dir($sFullPath)) {
			// directory (WebDAV collection)
			$aInfo["props"][] = $this->mkprop("resourcetype", "collection");
			$aInfo["props"][] = $this->mkprop("getcontenttype", "application/x-directory");
		} else {
			// plain file (WebDAV resource)
			$aInfo["props"][] = $this->mkprop("resourcetype", "");
			if (is_readable($sFullPath)) {
				$aInfo["props"][] = $this->mkprop("getcontenttype", ResourceFinder::mimeTypeOfFile($sFullPath));
			} else {
				$aInfo["props"][] = $this->mkprop("getcontenttype", "application/x-non-readable");
			}
			$aInfo["props"][] = $this->mkprop("getcontentlength", filesize($sFullPath));
		}

		return $aInfo;
	}
	
	private function hasReadAccess($mResourcePath) {
		if($this->aWebdavPermissions === true) {
			return true;
		}
		if(count($this->aWebdavPermissions) === 0) {
			return false;
		}
		if(!is_array($mResourcePath)) {
			$mResourcePath = explode('/', $mResourcePath);
		}
		
		$aResourcePath = array();
		foreach($mResourcePath as $iResourceKey => $sResourceValue) {
			if($sResourceValue !== '') {
				$aResourcePath[] = $sResourceValue;
			}
		}
		
		if(count($aResourcePath) === 0) {
			//Root directory is readable by anyone
			return true;
		}
		return isset($this->aWebdavPermissions[$aResourcePath[0]]);
	}
	
	private function hasWriteAccess($mResourcePath) {
		if($this->aWebdavPermissions === true) {
			return true;
		}
		if(count($this->aWebdavPermissions) === 0) {
			return false;
		}
		if(!is_array($mResourcePath)) {
			$mResourcePath = explode('/', $mResourcePath);
		}
		
		$aResourcePath = array();
		foreach($mResourcePath as $iResourceKey => $sResourceValue) {
			if($sResourceValue !== '') {
				$aResourcePath[] = $sResourceValue;
			}
		}
		
		if(count($aResourcePath) === 0) {
			//Root directory is only accessible by an admin
			return false;
		}
		return isset($this->aWebdavPermissions[$aResourcePath[0]]);
	}

}
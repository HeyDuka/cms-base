<?php
/**
 * @package utils
 */

class LinkUtil {

	public static function redirectToManager($mPath="", $mManager=null, $aParameters=array(), $bIncludeLanguage=null) {
		self::redirect(LinkUtil::link($mPath, $mManager, $aParameters, $bIncludeLanguage));
	}

	//redirectToLanguage can only be used if language attribute is still in REQUEST_PATH (if it ever was)
	public static function redirectToLanguage($sLanguageId = null) {
		if($sLanguageId == null) {
			$sLanguageId = Session::language();
		}
		$oLanguage = LanguageQuery::language($sLanguageId)->findOne();
		$sLanguageInPathId = null;
		if(Manager::hasNextPathItem()) {
			$oLanguageInPath = LanguageQuery::language(Manager::peekNextPathItem(), true)->findOne();
			if($oLanguageInPath && $oLanguageInPath->getIsActive()) {
				Manager::usePath();
				$sLanguageInPathId = $oLanguageInPath->getId();
			}
		}
		$sManager = Manager::getManagerClassNormalized(null);
		if($sManager::shouldIncludeLanguageInLink()) {
			if($sLanguageInPathId === $sLanguageId) {
				return;
			}
		} else if($sLanguageInPathId === null) {
			//Did not include language in link and should not
			return;
		}
		self::redirectToManager(Manager::getRequestPath(), $sManager, array(), $oLanguage);
	}

	/**
	* Redirects (locally by default).
	* Use with LinkUtil::link()ed URLs (because this redirect does not add the base path/context MAIN_DIR_FE).
	* Discards all buffered output and exits
	* Pass $sHost = false to mark $sLocation as absolute URL
	*/
	public static function redirect($sLocation, $sHost = null, $sProtocol = null, $bPermanent = true) {
		while(ob_get_level() > 0) {
			ob_end_clean();
		}
		if($bPermanent) {
			self::sendHTTPStatusCode(301, "Moved Permanently");
		} else {
			self::sendHTTPStatusCode(302, "Found");
		}
		if($sHost !== false) {
			$sLocation = self::absoluteLink($sLocation, $sHost, $sProtocol);
		}
		if(StringUtil::startsWith($sLocation, '//')) {
			$sLocation = (self::isSSL() ? 'https:' : 'http:').$sLocation;
		}
		$sRedirectString = "Location: $sLocation";
		header($sRedirectString);exit;
	}
	
	public static function sendHTTPStatusCode($iCode, $sName) {
		$sProtocol = isset($_SERVER["SERVER_PROTOCOL"]) ? $_SERVER["SERVER_PROTOCOL"] : 'HTTP/1.1';
		header("$sProtocol $iCode $sName", true, $iCode);
	}
	
	public static function absoluteLink($sLocation, $sHost = null, $sProtocol = null) {
		if($sProtocol === null) {
			$sProtocol = '//';
		} else if($sProtocol === true) {
			$sProtocol = 'https://';
		} else if($sProtocol === false) {
			$sProtocol = 'http://';
		}
		if($sHost === null && isset($_SERVER['HTTP_HOST'])) {
			$sHost = $_SERVER['HTTP_HOST'];
		}
		if($sHost === null) {
			$sHost = Settings::getSetting('domain_holder', 'domain', null);
		}
		return "$sProtocol$sHost$sLocation";
	}
	
	public static function isSSL() {
		// http://stackoverflow.com/questions/7304182/detecting-ssl-with-php
		if(isset($_SERVER['HTTPS'])) {
			if('on' == strtolower($_SERVER['HTTPS'])) {
				return true;
			}
			if('1' == $_SERVER['HTTPS']) {
				return true;
			}
		} elseif (isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'])) {
			return true;
		}
		return false;
	}

	public static function linkToSelf($mPath=null, $aParameters=null, $bIgnoreRequest = false) {
		$aRequestPath = Manager::getUsedPath();
		if($aParameters === null) {
			$aParameters = array();
		}
		if($mPath !== null) {
			if(!is_array($mPath)) {
				$mPath = explode("/", $mPath);
			}
			$aPath = array_merge($aRequestPath, $mPath);
		} else {
			$aPath = $aRequestPath;
		}
		if(!$bIgnoreRequest) {
			$aParameters = self::getRequestedParameters($aParameters);
		}
		return self::link($aPath, null, $aParameters, false);
	}
	
	public static function getRequestedParameters($aOverrideParameters = array()) {
		foreach(array_diff_assoc($_REQUEST, $_COOKIE) as $sName => $sValue) {
			if($sName === 'path') {
				continue;
			}
			if(!isset($aOverrideParameters[$sName])) {
				$aOverrideParameters[$sName] = $sValue;
			}
		}
		return $aOverrideParameters;
	}

	public static function link($mPath=array(), $mManager=null, $aParameters=array(), $mLanguage=null, $bIncludeLanguage=null) {
		if(!is_array($mPath)) {
			$mPath = explode("/", $mPath);
		}

		$mManager = Manager::getManagerClassNormalized($mManager);
		$sPrefix = Manager::getPrefixForManager($mManager);
		
		if($mLanguage === true || $mLanguage === false) {
			$bIncludeLanguage = $mLanguage;
			$mLanguage = null;
		}
		if($bIncludeLanguage === null) {
			$bIncludeLanguage = $mManager::shouldIncludeLanguageInLink();
		}
		if($bIncludeLanguage) {
			if($mLanguage === null) {
				$mLanguage = Session::language(true);
			}
			if($mLanguage instanceof Language) {
				array_unshift($mPath, $mLanguage->getPathPrefix());
			} else if(is_string($mLanguage)) {
				$mLanguage = LanguageQuery::create()->findPk($mLanguage)->getPathPrefix();
				array_unshift($mPath, $mLanguage);
			}
		}

		foreach($mPath as $iKey => $sValue) {
			if($sValue === null || $sValue === "") {
				unset($mPath[$iKey]);
			} else {
				$mPath[$iKey] = rawurlencode($sValue);
			}
		}
		
		if($sPrefix !== null && $sPrefix !== "") {
			$sPrefix .= "/";
		} else {
			$sPrefix = '';
		}
		
		return MAIN_DIR_FE.$sPrefix.implode('/', $mPath).self::prepareLinkParameters($aParameters);
	}
	
	/**
	* @todo: check use of http_build_query()
	*/
	public static function prepareLinkParameters($aParameters) {
		$sParameters = '';
		foreach($aParameters as $sKey => $sValue) {
			if(is_array($sValue)) {
				foreach($sValue as $sKeyKey => $sValueValue) {
					$sParameters .= "&".rawurlencode($sKey)."[".rawurlencode($sKeyKey)."]".($sValueValue ? "=".rawurlencode($sValueValue) : '');
				}
			} else {
				$sParameters .= "&".rawurlencode($sKey).($sValue ? "=".rawurlencode($sValue) : '');
			}
		}
		$sParameters = substr($sParameters, 1);
		if($sParameters !== false && $sParameters !== "") {
			$sParameters = "?".$sParameters;
		}
		return $sParameters;
	}

	public static function getHostName() {
		return Settings::getSetting('domain_holder', 'name', $_SERVER['HTTP_HOST']);
	}

	public static function getDomainHolderEmail($sDefaultSender = 'info') {
		return Settings::getSetting('domain_holder', 'email', $sDefaultSender.'@'.$_SERVER['HTTP_HOST']);
	}
	
	public static function getUrlWithProtocolIfNotSet($sUrl) {
		if($sUrl != '') {
			return self::getPrefixIfNotSet($sUrl);
		}
		return '';
	}

	/**
	* @todo find a better more appropriate solution
	*/
	public static function getPrefixIfNotSet($sString, $sDefaultPrefix = 'http://') {
		$sPattern = '/^\w+:/';
		if(preg_match($sPattern, $sString) === 1) {
			return $sString;
		}
		return $sDefaultPrefix.$sString;
	}
	
}

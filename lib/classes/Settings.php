<?php
/**
 * class Settings
 */
class Settings {
	
	private $aSettings;
	private static $INSTANCES = array();
	
	/**
	 * __construct()
	 */
	private function __construct($sFileName) {
		require_once("spyc/Spyc.php");
		$oSpyc = new Spyc();
		$oSpyc->setting_use_syck_is_possible = true;
		$aConfigPaths = ResourceFinder::findAllResourcesByExpressions(array(DIRNAME_CONFIG, array(ErrorHandler::getEnvironment()), $sFileName), ResourceFinder::SEARCH_BASE_FIRST);
		$this->aSettings = array();
		foreach($aConfigPaths as $sConfigPath) {
			foreach($oSpyc->loadFile($sConfigPath) as $sSection => $aSection) {
				foreach($aSection as $sKey => $mValue) {
					if(!isset($this->aSettings[$sSection])) {
						$this->aSettings[$sSection] = array();
					}
					$this->aSettings[$sSection][$sKey] = $mValue;
				}
			}
		}
	}
		
	/**
	 * getConfigurationSetting()
	 * @param string config.yml section name
	 * @param string section var key
	 * @param mixed default value
	 * @return mixed value
	 */
	public function _getSetting($sSection, $sKey, $mDefaultValue) {
		if(isset($_REQUEST["setting-override-$sSection/$sKey"]) && Session::getSession()->isBackendAuthenticated()) {
			return $_REQUEST["setting-override-$sSection/$sKey"];
		}
		$aSettingsPart = $this->aSettings;
		if($sSection !== null) {
			if(!isset($aSettingsPart[$sSection])) {
				return $mDefaultValue;
			}
			$aSettingsPart = $aSettingsPart[$sSection];
		}
		if(!isset($aSettingsPart[$sKey])) {
			return $mDefaultValue;
		}
		return $aSettingsPart[$sKey];
	}
	
	public function &getSettingsArray() {
		return $this->aSettings;
	}
	
	public static function getSetting($sSection, $sKey, $mDefaultValue, $sPath = null) {
		return self::getInstance($sPath)->_getSetting($sSection, $sKey, $mDefaultValue);
	}
	
	public function _getSettingIf($mCondition, $sSection, $sKey, $mDefaultValue) {
		if($mCondition !== null) {
			return $mCondition;
		}
		return $this->_getSetting($sSection, $sKey, $mDefaultValue);
	}

	public static function getSettingIf($mCondition, $sSection, $sKey, $mDefaultValue, $sPath = null) {
		return self::getInstance($sPath)->_getSettingIf($mCondition, $sSection, $sKey, $mDefaultValue);
	}

	public static function getInstance($sFileName=null) {
		if($sFileName === null) {
			$sFileName = "config";
		}
		$sFileName = "$sFileName.yml";
		$sCacheKey = "$sFileName-".ErrorHandler::getEnvironment();
		if(!isset(self::$INSTANCES[$sFileName])) {
			$oCache = new Cache($sCacheKey, DIRNAME_CONFIG);
			if($oCache->cacheFileExists() && !$oCache->isOutdated(ResourceFinder::findAllResourcesByExpressions(array(DIRNAME_CONFIG, array(ErrorHandler::getEnvironment()), $sFileName), ResourceFinder::SEARCH_BASE_FIRST))) {
				self::$INSTANCES[$sCacheKey] = $oCache->getContentsAsVariable();
			} else {
				self::$INSTANCES[$sCacheKey] = new Settings($sFileName);
				$oCache->setContents(self::$INSTANCES[$sCacheKey]);
			}
		}
		return self::$INSTANCES[$sCacheKey];
	}
	
}

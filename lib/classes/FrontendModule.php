<?php
abstract class FrontendModule extends Module {
	protected static $MODULE_TYPE = 'frontend';
	
	protected $oLanguageObject;
	protected $oData;
	protected $aPath;
	protected $iId;
	
	public function __construct($oLanguageObject = null, $aPath = null, $iId = 1) {
		if($oLanguageObject instanceof LanguageObject) {
			$this->oLanguageObject = $oLanguageObject;
		} else {
			$this->oData = $oLanguageObject;
		}
		$this->aPath = $aPath;
		$this->iId = $iId;
	}

	public abstract function renderFrontend();

	public function getSaveData() {}

	public function getCssForFrontend() {
		return null;
	}

	public function getJsForFrontend() {
		return null;
	}

	public function getWords() {
		return StringUtil::getWords($this->renderFrontend(), true);
	}
	
	protected function constructTemplate($sTemplateName = "main", $bUseGlobalTemplatesDir = false) {
		return self::constructTemplateForModuleAndType($this->getType(), $this->getModuleName(), $sTemplateName, $bUseGlobalTemplatesDir);
	}
	
	protected function getData() {
		if($this->oLanguageObject !== null && $this->oLanguageObject->getData() !== null) {
			return stream_get_contents($this->oLanguageObject->getData());
		}
		return $this->oData;
	}
	
	public static function listContentModules($bIncludeEmpty = false) {
		$aResult = array();
		$aModules = self::listModules();
		// list modules except empty [if there is no inherit=true] and tag [if none exist]
		foreach($aModules as $sModuleName => $aModulePath) {
			if(!$bIncludeEmpty && $sModuleName === 'empty'
			|| ($sModuleName === 'tag' && TagPeer::doCount(new Criteria()) == 0)) {
				continue;
			}
			$sClassName = self::getClassNameByName($sModuleName);
			$aResult[$sModuleName] = self::getDisplayNameByName($sModuleName);
		}
		asort($aResult);
		return $aResult;
	}
	
	protected function getModuleSetting($sName, $sDefaultValue) {
		return Settings::getSetting($this->getModuleName(), $sName, $sDefaultValue, 'modules');
	}
	
	public static function getDirectoryForModule($sModuleName) {
		$aModules = FrontendModule::listModules();
		$sPath = $aModules[$sModuleName];
		return $sPath;
	}
	
	public static function getConfigDirectoryForModule($sModuleName) {
		return self::getDirectoryForModule($sModuleName)."/config";
	}
	
	public static function isDynamic() {
		return false;
	}
	
	public function getLanguageObject() {
	    return $this->oLanguageObject;
	}
	
	/**
	 * @param object language object with the data
	 * description: should return some helpful information in page_detail filled_module, displaying filtered unserialized language object data
	 * mainly for custom modules with options
	 * @return string/Template object/null
 */
	public static function getContentInfo($oLanguageObject) {
		if(!$oLanguageObject) {
			return null;
		}
		$mData = @unserialize(stream_get_contents($oLanguageObject->getData()));
		if(!$mData) {
			return null;
		}
		return var_export($mData, true);
	}

	public function __sleep() {
		if($this->oLanguageObject !== null) {
			$this->oLanguageObject = $this->oLanguageObject->getId();
		}
		return array_keys(get_object_vars($this));
	}
	
	public function __wakeup() {
		if($this->oLanguageObject !== null) {
			$sId = explode('_', $this->oLanguageObject);
			$this->oLanguageObject = LanguageObjectPeer::retrieveByPK($this->oLanguageObject);
			if($this->oLanguageObject === null) {
				$this->oLanguageObject = new LanguageObject();
				$this->oLanguageObject->setObjectId($sId[0]);
				$this->oLanguageObject->setLanguageId($sId[1]);
			}
		}
	}

}

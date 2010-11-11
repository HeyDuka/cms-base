<?php
class TagEditWidgetModule extends PersistentWidgetModule {
	private $oFrontendModule;
	private $sDisplayMode;
	
	public function __construct($sSessionKey, $oFrontendModule) {
		parent::__construct($sSessionKey);
		$this->oFrontendModule = $oFrontendModule;
		$this->sDisplayMode = $this->oFrontendModule->widgetData();
	}
	
	public function setDisplayMode($sDisplayMode) {
		$this->sDisplayMode = $sDisplayMode;
	}

	public function getDisplayMode($sKey=null) {
		if($sKey === null) {
			return $this->sDisplayMode;
		}
		if(isset($this->sDisplayMode[$sKey])) {
			return $this->sDisplayMode[$sKey];
		}
		return null;
	}
	
	public function allTagedItems() {
	}
	
	public function getConfigurationModes() {
		$aResult = array();
		$aResult['template'] = AdminManager::getSiteTemplatesForListOutput();
		$aResult['tags'] = array();
		foreach(TagPeer::doSelect(new Criteria()) as $oTag) {
			$aResult['tags'] = $oTag->getName();
		}
		$aResult['types'] = self::getTaggedModels();
		foreach(TagFrontendModule::$DISPLAY_OPTIONS as $sName) {
      $aResult['types'][$sName] = StringPeer::getString('module.backend.'.$sName);
    }
		return $aResult;
	}
	
 /** getTaggedModels()
	* to be used in TagsAdminModule
	*/
	public static function getTaggedModels($bCount=false) {
		$oCriteria = new Criteria();
		$oCriteria->clearSelectColumns()->addSelectColumn(TagInstancePeer::MODEL_NAME);
		$oCriteria->setDistinct();
		if($bCount) {
			return TagInstancePeer::doCount($oCriteria);
		}
		$oCriteria->addAscendingOrderByColumn(TagInstancePeer::MODEL_NAME);
		$aResult = array();
		foreach(TagInstancePeer::doSelect($oCriteria) as $oInstance) {
			$sTableName = constant($oInstance->getModelName().'Peer::TABLE_NAME');
			$sName = StringPeer::getString('module.backend.'.$sTableName);
			$aResult[$oInstance->getModelName()] = $sName;
		}
		return $aResult;
	}
	
	public function saveData($mData) {
		return $this->oFrontendModule->widgetSave($mData);
	}
	
	public function getElementType() {
		return 'form';
	}
}
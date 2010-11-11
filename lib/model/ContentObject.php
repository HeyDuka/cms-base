<?php
require_once 'model/om/BaseContentObject.php';

/**
 * classname ContentObject
 * @package model
 */ 
class ContentObject extends BaseContentObject {
	
	public function getLanguageObject($sLanguageId = null) {
		if($sLanguageId === null) {
			$sLanguageId = Session::language();
		}
		$oResult = $this->getLanguageObjectsByLanguage($sLanguageId);
		if(count($oResult) === 0) {
			return null;
		}
		return $oResult[0];
	}
		
	public function getLanguageObjectsByLanguage($sLanguage) {
		$oCriteria = new Criteria();
		$oCriteria->add(LanguageObjectPeer::LANGUAGE_ID, $sLanguage);
		return $this->getLanguageObjects($oCriteria);
	}
	
	public function hasLanguage($sLanguageId) {
		return $this->getLanguageObjectsByLanguage($sLanguageId) !== null;
	}
	
	public function hasLanguageFilled($sLanguageId) {
		foreach($this->getLanguageObjectsByLanguage($sLanguageId) as $oLangObj) {
			var_dump($oLangObj);
		}
	}
	
	public function getObjectTypeName($sLanguageId=null) {
		return FrontendModule::getDisplayNameByName($this->getObjectType(), $sLanguageId);
	}
	
	// public function delete(PropelPDO $con = null) {
	// 	parent::delete($con);
	// }
}

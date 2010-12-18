<?php
/**
 * @package modules.widget
 */
class LanguageListWidgetModule extends WidgetModule {
	
	private $oListWidget;
	
	public function __construct() {
		$this->oListWidget = new ListWidgetModule();
		$oDelegateProxy = new CriteriaListWidgetDelegate($this, "Language");
		$this->oListWidget->setDelegate($oDelegateProxy);
	}
	
	public function doWidget() {
		$aTagAttributes = array('class' => 'language_list');
		$oListTag = new TagWriter('table', $aTagAttributes);
		$this->oListWidget->setListTag($oListTag);
		return $this->oListWidget->doWidget();
	}
	
	public function toggleIsActive($aRowData) {
		$oLanguage = LanguagePeer::retrieveByPK($aRowData['id']);
		if($oLanguage) {
			$oLanguage->setIsActive(!$oLanguage->getIsActive());
			$oLanguage->save();
		}
	}
	
	public function getColumnIdentifiers() {
		return array('id', 'language_id', 'name', 'is_default', 'is_active', 'delete');
	}
	
	public function getMetadataForColumn($sColumnIdentifier) {
		$aResult = array();
		switch($sColumnIdentifier) {
			case 'id':
				$aResult['display_type'] = ListWidgetModule::DISPLAY_TYPE_DATA;
				break;
			case 'language_id':
				$aResult['heading'] = StringPeer::getString('widget.language_id');
				$aResult['field_name'] = 'id';
				break;
			case 'name':
				$aResult['heading'] = StringPeer::getString('widget.name');
				$aResult['field_name'] = 'language_name';
				break;
			case 'is_default':
				$aResult['heading'] = StringPeer::getString('widget.language.is_default');
				break;
			case 'is_active':
				$aResult['heading'] = StringPeer::getString('widget.is_active');
				break;
			case 'delete':
				$aResult['heading'] = ' ';
				$aResult['display_type'] = ListWidgetModule::DISPLAY_TYPE_ICON;
				$aResult['field_name'] = 'trash';
				break;
		}
		return $aResult;
	}
}
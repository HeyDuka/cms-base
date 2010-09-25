<?php
/**
 * @package modules.widget
 */
class StringListWidgetModule extends WidgetModule {
	private $oListWidget;
	private $sNameSpace;
	public $oDelegateProxy;
	
	public function __construct() {
		$this->oListWidget = new ListWidgetModule();
		$this->oDelegateProxy = new CriteriaListWidgetDelegate($this, "String", 'string_key');
		$this->oListWidget->setDelegate($this->oDelegateProxy);
	}
	
	public function doWidget() {
		$aTagAttributes = array('class' => 'string_list');
		$oListTag = new TagWriter('table', $aTagAttributes);
		$this->oListWidget->setListTag($oListTag);
		return $this->oListWidget->doWidget();
	}
	
	public function getColumnIdentifiers() {
		return array('id', 'string_key', 'text', 'languages_available', 'delete');
	}
	
	public function getMetadataForColumn($sColumnIdentifier) {
		$aResult = array('is_sortable' => true);
		switch($sColumnIdentifier) {
			case 'id':
				$aResult['heading'] = false;
				$aResult['field_name'] = 'string_key';
				$aResult['display_type'] = ListWidgetModule::DISPLAY_TYPE_DATA;
				break;
			case 'string_key':
				$aResult['heading'] = StringPeer::getString('widget.string.string_key');
				break;
			case 'text':
				$aResult['heading'] = StringPeer::getString('widget.string.string_text').' ('.AdminManager::getContentLanguage().')';
				break;
			case 'languages_available':
				$aResult['heading'] = StringPeer::getString('widget.languages_filled');
				break;
			case 'delete':
				$aResult['heading'] = ' ';
				$aResult['display_type'] = ListWidgetModule::DISPLAY_TYPE_ICON;
				$aResult['field_name'] = 'trash';
				$aResult['is_sortable'] = false;
				break;
		}
		return $aResult;
	}
	
	public function deleteRow($aRowData, $oCriteria) {
		$bResult = false;
		$sNameSpace = StringPeer::getNameSpaceFromStringKey($aRowData['string_key']);
		if(StringPeer::doDelete($oCriteria) && $sNameSpace !== null) {
			$bResult = !StringPeer::nameSpaceExists($sNameSpace);
		}
		return array(StringDetailWidgetModule::SIDEBAR_CHANGED => $bResult);
	}
	
	public function getDatabaseColumnForDisplayColumn($sDisplayColumn) {
		if($sDisplayColumn === 'name_space') {
			return StringPeer::STRING_KEY;
		}
		return null;
	}
	
	public function getFilterTypeForColumn($sColumnName) {
		if($sColumnName === 'name_space') {
			return CriteriaListWidgetDelegate::FILTER_TYPE_BEGINS;
		}
		return null;
	}

	public function getCriteria() {
		$oCriteria = new Criteria();
    // $oCriteria->setDistinct();
    // $oCriteria->add(StringPeer::LANGUAGE_ID, AdminManager::getContentLanguage());
		if($this->oDelegateProxy->getNameSpace() !== CriteriaListWidgetDelegate::SELECT_ALL) {
			$oCriteria->add(StringPeer::STRING_KEY, "{$this->oDelegateProxy->getNameSpace()}.%", Criteria::LIKE);
		}
		$oCriteria->addGroupByColumn(StringPeer::STRING_KEY);
		return $oCriteria;
	}
}
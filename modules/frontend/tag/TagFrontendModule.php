<?php
class TagFrontendModule extends DynamicFrontendModule implements WidgetBasedFrontendModule {
	
	public function __construct($oLanguageObject, $aRequestPath = null) {
		parent::__construct($oLanguageObject, $aRequestPath);
	}
	
	public function renderFrontend() {
		$aData = unserialize($this->getData());
		$oTemplate = new Template($aData['template']);
		$oItemTemplatePrototype = new Template($aData['template'].'_item');
		$bItemFound = false;
		
		// FIXME: Keep track of output $oCorrespondingItems and refuse output if already done
		foreach($aData['tags'] as $iTagID) {
			$oTag = TagPeer::retrieveByPK($iTagID);
			if($oTag === null) {
				continue;
			}
			$aCorrespondingItems = $oTag->getAllCorrespondingDataEntries($aData['types']);
			foreach($aCorrespondingItems as $i => $oCorrespondingItem) {
				if(!method_exists($oCorrespondingItem, 'renderListItem')) {
					return;
				}
				if(!$oCorrespondingItem->shouldBeIncludedInList(Session::language(), FrontendManager::$CURRENT_PAGE)) {
					continue;
				}
				$bItemFound = true;
				$oItemTemplate = clone $oItemTemplatePrototype;
				$oItemTemplate->replaceIdentifier('model', get_class($oCorrespondingItem));
				$oItemTemplate->replaceIdentifier('counter', $i+1);
				$oCorrespondingItem->renderListItem($oItemTemplate);
				$oTemplate->replaceIdentifierMultiple("items", $oItemTemplate);
			}
		}
		if(!$bItemFound) {
			return null;
		}
		return $oTemplate;
	}

	public function widgetData() {
		return @unserialize($this->getData());	
	}
	
	public function widgetSave($mData) {
		$this->oLanguageObject->setData(serialize($mData));
		return $this->oLanguageObject->save();
	}
	
	public function getWidget() {
		$oWidget = new TagFrontendConfigWidgetModule(null, $this);
		return $oWidget;
	}
}
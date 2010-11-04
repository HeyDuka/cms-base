<?php
/**
 * @package modules.frontend
 */

class LinkListFrontendModule extends DynamicFrontendModule implements WidgetBasedFrontendModule {
	
	const LIST_ITEM_POSTFIX = '_item';
	const SORT_OPTION_BY_NAME = 'by_name';
	const SORT_OPTION_BY_SORT = 'by_sort';
	
	public function renderFrontend() {
		$aOptions = @unserialize($this->getData());
		$oCriteria = LinkQuery::create();
		$bOneTagnameOnly = false;
		if(isset($aOptions['tags']) && is_array($aOptions['tags']) && (count($aOptions['tags']) > 0)) {
			$aLinks = LinkPeer::getLinksByTagName($aOptions['tags']);
			$bOneTagnameOnly = count($aOptions['tags']) === 1;
		} else {
			if(isset($aOptions['link_category_option']) && $aOptions['link_category_option'] != null) {
				$oCriteria->add(LinkPeer::LINK_CATEGORY_ID, $aOptions['link_category_option']);
			}
			if(isset($aOptions['sort_option']) && $aOptions['sort_option'] === self::SORT_OPTION_BY_SORT) {
				$oCriteria->addAscendingOrderByColumn(LinkPeer::SORT);
			} else {
				$oCriteria->addAscendingOrderByColumn(LinkPeer::NAME);
			}
		}
		try {
			$oListTemplate = new Template($aOptions['template_option']);
			if($bOneTagnameOnly) {
        $oListTemplate->replaceIdentifier('tag_name', StringPeer::getString('tagname.'.$aOptions['tags'][0], null, $aOptions['tags'][0]));
			}
			foreach($oCriteria->find() as $i => $oLink) {
				$oItemTemplate = new Template($aOptions['template_option'].self::LIST_ITEM_POSTFIX);
				$oItemTemplate->replaceIdentifier('model', 'Link');
				$oItemTemplate->replaceIdentifier('name', $oLink->getName());
				$oItemTemplate->replaceIdentifier('description', $oLink->getDescription());
				$oItemTemplate->replaceIdentifier('url', $oLink->getUrl());
				$oListTemplate->replaceIdentifierMultiple('items', $oItemTemplate);
			}
		} catch(Exception $e) {
			$oListTemplate = new Template("", null, true);
		}
		return $oListTemplate;
	}

	public function widgetData() {
		return @unserialize($this->getData());	
	}
	
	public function widgetSave($mData) {
		$this->oLanguageObject->setData(serialize($mData));
		return $this->oLanguageObject->save();
	}
	
	public function getWidget() {
		$aOptions = @unserialize($this->getData());	
		$oWidget = new LinkEditWidgetModule(null, $this);
		$oWidget->setDisplayMode($aOptions);
		return $oWidget;
	}
	
	public static function getTemplateOptions() {
		return AdminManager::getSiteTemplatesForListOutput(self::LIST_ITEM_POSTFIX);	
	}
	
	public static function getSortOptions() {
		$aResult[self::SORT_OPTION_BY_NAME] = StringPeer::getString('widget.order.by_name');
		$aResult[self::SORT_OPTION_BY_SORT] = StringPeer::getString('widget.order.by_sort');
		return $aResult;
	}	
	
	public static function getCategoryOptions() {
		$oCriteria = LinkCategoryQuery::create();
		$oCriteria->orderByName();
		$oCriteria->clearSelectColumns()->addSelectColumn(LinkCategoryPeer::ID)->addSelectColumn(LinkCategoryPeer::NAME);
		$aResult = array();
		foreach(LinkCategoryPeer::doSelectStmt($oCriteria)->fetchAll(PDO::FETCH_ASSOC) as $aCategory) {
			$aResult[$aCategory['ID']] = $aCategory['NAME'];
		}
		return $aResult;
	}
}

<?php
/**
 * @package modules.widget
 */
class LinkListWidgetModule extends WidgetModule {

	private $oListWidget;
	private $iLinkCategoryId;
	public $oDelegateProxy;
	
	public function __construct() {
		$this->oListWidget = new ListWidgetModule();
		$this->oDelegateProxy = new CriteriaListWidgetDelegate($this, "Link", "name", "asc");
		$this->oListWidget->setDelegate($this->oDelegateProxy);
		$this->oListWidget->setSetting('row_model_drag_and_drop_identifier', 'id');
	}
	
	public function doWidget() {
		$aTagAttributes = array('class' => 'link_list');
		$oListTag = new TagWriter('table', $aTagAttributes);
		$this->oListWidget->setListTag($oListTag);
		return $this->oListWidget->doWidget();
	}
	
	public function toggleIsInactive($aRowData) {
		$oLink = LinkPeer::retrieveByPK($aRowData['id']);
		if($oLink) {
			$oLink->setIsInactive(!$oLink->getIsInactive());
			$oLink->save();
		}
	}
	
	public function getColumnIdentifiers() {
		return array('id', 'name', 'sort', 'url', 'description', 'category_name', 'language_name', 'updated_at_formatted', 'delete');
	}
	
	public function getMetadataForColumn($sColumnIdentifier) {
		$aResult = array('is_sortable' => true);
		switch($sColumnIdentifier) {
			case 'sort':
				$aResult['heading'] = StringPeer::getString('wns.sort');
				$aResult['display_type'] = ListWidgetModule::DISPLAY_TYPE_REORDERABLE;
				break;
			case 'name':
				$aResult['heading'] = StringPeer::getString('wns.name');
				break;
			case 'url':
				$aResult['heading'] = StringPeer::getString('wns.url');
				$aResult['display_type'] = ListWidgetModule::DISPLAY_TYPE_URL;
				break;
			case 'description':
				$aResult['heading'] = StringPeer::getString('wns.description');
				break;
			case 'category_name':
				$aResult['heading'] = StringPeer::getString('wns.link_category_list');
				break;
			case 'language_name':
				$aResult['heading'] = StringPeer::getString('wns.language');
				break;
			case 'updated_at_formatted':
				$aResult['heading'] = StringPeer::getString('wns.updated_at');
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

	public function getFilterTypeForColumn($sColumnIdentifier) {
		if($sColumnIdentifier === 'link_category_id') {
			return CriteriaListWidgetDelegate::FILTER_TYPE_IS;
		}
		return null;
	}

	public function getDatabaseColumnForColumn($sColumnIdentifier) {
		if($sColumnIdentifier === 'category_name') {
			return LinkPeer::LINK_CATEGORY_ID;
		}
		if($sColumnIdentifier === 'updated_at_formatted') {
			return LinkPeer::UPDATED_AT;
		}
		if($sColumnIdentifier === 'language_name') {
			return LinkPeer::LANGUAGE_ID;
		}
		return null;
	}
	
	public function getLinkCategoryName() {
		$oLinkCategory = LinkCategoryPeer::retrieveByPK($this->oDelegateProxy->getLinkCategoryId());
		if($oLinkCategory) {
			return $oLinkCategory->getName();
		}
		if($this->oDelegateProxy->getLinkCategoryId() === CriteriaListWidgetDelegate::SELECT_WITHOUT) {
			return StringPeer::getString('wns.links.without_category');
		}
		return $this->oDelegateProxy->getLinkCategoryId();
	}
	
  public function allowSort($sSortColumn) {
		return $this->oDelegateProxy->getLinkCategoryId() !== CriteriaListWidgetDelegate::SELECT_ALL;
	}
	
	public function doSort($sColumnIdentifier, $oLinkToSort, $oRelatedLink, $sPosition = 'before') {
		$iNewPosition = $oRelatedLink->getSort() + ($sPosition === 'before' ? 0 : 1);
		if($oLinkToSort->getSort() < $oRelatedLink->getSort()) {
			$iNewPosition--;
		}
		$oLinkToSort->setSort($iNewPosition);
		$oLinkToSort->save();
		$oQuery = $this->oDelegateProxy->getCriteria();
		$oQuery->filterById($oLinkToSort->getId(), Criteria::NOT_EQUAL);
		$oQuery->orderBySort();
		$i = 1;
		foreach($oQuery->find() as $oLink) {
			if($i == $iNewPosition) {
				$i++;
			}
			$oLink->setSort($i);
			$oLink->save();
			$i++;
		}
	}
	
	public function getCriteria() {
		return LinkQuery::create()->excludeExternallyManaged();
	}
}
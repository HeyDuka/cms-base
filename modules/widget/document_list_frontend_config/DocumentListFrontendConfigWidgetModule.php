<?php
class DocumentListFrontendConfigWidgetModule extends FrontendConfigWidgetModule {
	public function allDocuments($aOptions = array()) {
		$oCriteria = DocumentQuery::create();
		$aCategories = array();
		if(count($aOptions) === 0) {
			$aOptions = $this->configData();
		}
		if(isset($aOptions['document_categories'])) {
			if(is_array($aOptions['document_categories']) ) {
				$aCategories = $aOptions['document_categories'];
			} else {
				$aCategories = array($aOptions['document_categories']);
			}
		}
		if(isset($aOptions['document_kind']) && $aOptions['document_kind']) {
			$oCriteria->filterByDocumentKind($aOptions['document_kind']);
		}
		if(count($aCategories) > 0) {
			if(count($aCategories > 1)) {
				$oCriteria->add(DocumentPeer::DOCUMENT_CATEGORY_ID, $aOptions['document_categories'], Criteria::IN);
			} else {
				$oCriteria->add(DocumentPeer::DOCUMENT_CATEGORY_ID, $aCategories[0]);
			}
		}
		if(isset($aOptions['sort_by']) && $aOptions['sort_by'] === DocumentListFrontendModule::SORT_BY_SORT) {
			$oCriteria->orderBySort();
		}
		$oCriteria->orderByName()->filterByDisplayLanguage(AdminManager::getContentLanguage());
		$oCriteria->clearSelectColumns()->addSelectColumn(DocumentPeer::ID)->addSelectColumn(DocumentPeer::NAME);
		return DocumentPeer::doSelectStmt($oCriteria)->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function getConfigurationModes() {
		$aResult = array();
		$aDocumentCategories = DocumentListFrontendModule::getCategoryOptions();
		$aResult['document_categories'] = $aDocumentCategories;
		$aResult['document_kind'] = array('' => StringPeer::getString('wns.document_kind.all')) + DocumentTypePeer::getDocumentKindsAssoc();
		$aResult['list_template'] = array_keys(DocumentListFrontendModule::getTemplateOptions());
		if(count($aDocumentCategories) > 0) {
		  $aResult['sort_by'] = DocumentListFrontendModule::getSortOptions();
		}
		return $aResult;
	}
}
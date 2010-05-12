<?php
/**
 * @package modules.widget
 */
class DocumentListWidgetModule extends WidgetModule {

	private $oListWidget;
	private $iDocumentCategoryId;
	private $sDocumentKind;
	private $oDocumentKindFilter;
	
	public function __construct() {
		$this->oListWidget = new ListWidgetModule();
		$oDelegateProxy = new CriteriaListWidgetDelegate($this, "Document", "name", "asc");
		$this->oListWidget->setDelegate($oDelegateProxy);
		$this->iDocumentCategoryId = null;
		$this->oDocumentKindFilter = WidgetModule::getWidget('document_kind_input', null, true);
	}

	public function doWidget() {
		$aTagAttributes = array('class' => 'document_list');
		$oListTag = new TagWriter('table', $aTagAttributes);
		$this->oListWidget->setListTag($oListTag);
		return $this->oListWidget->doWidget();
	}
	
	public function getColumnIdentifiers() {
		return array('id', 'name', 'document_kind', 'file_info', 'category_name', 'language_id', 'is_protected', 'updated_at_formatted', 'edit', 'delete');
	}
	
	public function getMetadataForColumn($sColumnIdentifier) {
		$aResult = array('is_sortable' => true);
		switch($sColumnIdentifier) {
			case 'name':
				$aResult['heading'] = StringPeer::getString('name');
				break;
				break;
			case 'document_kind':
				$aResult['display_type'] = ListWidgetModule::DISPLAY_TYPE_ICON;
				$aResult['has_data'] = true;
				$aResult['heading'] = '';
				$aResult['heading_filter'] = array('document_kind_input', $this->oDocumentKindFilter->getSessionKey());
				$aResult['is_sortable'] = false;
				break;			
			case 'file_info':
				$aResult['heading'] = StringPeer::getString('file.info');
				break;
			case 'category_name':
				$aResult['heading'] = StringPeer::getString('document_category_id_label_list');
				break;
			case 'language_id':
				$aResult['heading'] = StringPeer::getString('label_list.language_id');
				break;
			case 'is_protected':
				$aResult['heading'] = StringPeer::getString('file.is_protected');
				$aResult['icon_false'] = 'radio-on';
				$aResult['icon_true'] = 'key';
				break;
			case 'updated_at_formatted':
				$aResult['heading'] = StringPeer::getString('updated_at');
				break;
			case 'edit':
				$aResult['heading'] = ' ';
				$aResult['display_type'] = ListWidgetModule::DISPLAY_TYPE_ICON;
				$aResult['field_name'] = 'pencil';
				$aResult['is_sortable'] = false;
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

	public function toggleIsInactive($aRowData) {
		$oDocument = DocumentPeer::retrieveByPK($aRowData['id']);
		if($oDocument) {
			$oDocument->setIsInactive(!$oDocument->getIsInactive());
			$oDocument->save();
		}
	}

	public function toggleIsProtected($aRowData) {
		$oDocument = DocumentPeer::retrieveByPK($aRowData['id']);
		if($oDocument) {
			$oDocument->setIsProtected(!$oDocument->getIsProtected());
			$oDocument->save();
		}
	}
		
	public function setDocumentKind($sDocumentKind) {
		$this->sDocumentKind = $sDocumentKind;
		$this->oDocumentKindFilter->setSelectedDocumentKind($sDocumentKind);
	}
	
	public function getDocumentKind() {
		return $this->sDocumentKind;
	}
	
	public function setDocumentCategoryId($iDocumentCategoryId = null) {
		$this->iDocumentCategoryId = $iDocumentCategoryId;
	}
	
	public function getDocumentCategoryId() {
		if($this->iDocumentCategoryId === null) {
			return CriteriaListWidgetDelegate::SELECT_ALL;
		}
		return $this->iDocumentCategoryId;
	}
	
	public function getSortColumnForDisplayColumn($sDisplayColumn) {
		if($sDisplayColumn === 'category_name') {
			return DocumentPeer::DOCUMENT_CATEGORY_ID;
		}
		if($sDisplayColumn === 'file_info') {
			return "OCTET_LENGTH(DATA)";
		}
		if($sDisplayColumn === 'updated_at_formatted') {
			return DocumentPeer::UPDATED_AT;
		}		
		if($sDisplayColumn === 'document_kind') {
			return DocumentTypePeer::MIMETYPE;
		}
		return null;
	}
	
	public function getCriteria() {
		$oCriteria = new Criteria();
		// addJoin to Document Types for sort order of ducment kinds
	  $oCriteria->addJoin(DocumentPeer::DOCUMENT_TYPE_ID, DocumentTypePeer::ID);
		if($this->iDocumentCategoryId !== null) {
			if($this->iDocumentCategoryId === CriteriaListWidgetDelegate::SELECT_WITHOUT) {
				$oCriteria->add(DocumentPeer::DOCUMENT_CATEGORY_ID, null, Criteria::EQUAL);
			} elseif(is_int($this->iDocumentCategoryId)) {
				$oCriteria->add(DocumentPeer::DOCUMENT_CATEGORY_ID, $this->iDocumentCategoryId);
			}
			// do not handle all
		}
		if($this->sDocumentKind) {
		  $oCriteria->addJoin(DocumentPeer::DOCUMENT_CATEGORY_ID, DocumentCategoryPeer::ID, Criteria::LEFT_JOIN);
		        $oCriteria->add(DocumentPeer::DOCUMENT_TYPE_ID, array_keys(DocumentTypePeer::getDocumentTypeAndMimetypeByDocumentKind($this->sDocumentKind, true)), Criteria::IN);
		}
		return $oCriteria;
	}
}
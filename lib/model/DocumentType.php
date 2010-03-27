<?php

require_once 'model/om/BaseDocumentType.php';

/**
 * @package model
 */	
class DocumentType extends BaseDocumentType {
  public function isImageType($sType = '') {
    return StringUtil::startsWith($this->getMimetype(), "image/$sType");
  }
  
  public function getDocumentKind() {
    $aResult = explode('/', $this->getMimeType());
    return $aResult[0];
  }
  
  public function getDocumentKindDetail() {
    return $this->getDocumentKind();
  }

	public function getDocumentCount() {
		return $this->countDocuments();
	}
  
}


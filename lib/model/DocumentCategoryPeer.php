<?php

  // include base peer class
  require_once 'model/om/BaseDocumentCategoryPeer.php';

  // include object class
  include_once 'model/DocumentCategory.php';

/**
 * @package model
 */	
class DocumentCategoryPeer extends BaseDocumentCategoryPeer {

  public static function getDocumentCategoriesSorted($bInactiveOnly = false, $bExternallyManaged=false) {
    $oCriteria = new Criteria();
    if($bExternallyManaged !== null) {
      $oCriteria->add(self::IS_EXTERNALLY_MANAGED, $bExternallyManaged);
    } else {
      $oCriteria->addAscendingOrderByColumn(self::IS_EXTERNALLY_MANAGED);
    }
    if($bInactiveOnly) {
      $oCriteria->add(self::IS_INACTIVE, true);
    }
    $oCriteria->addAscendingOrderByColumn(self::NAME);
    return self::doSelect($oCriteria);
  }
  
  public static function getExternallyManagedDocumentCategories() {
    $oCriteria = new Criteria();
    $oCriteria->add(self::IS_EXTERNALLY_MANAGED, true);
    return self::doSelect($oCriteria);
  }
  
  public static function getExternallyManagedDocumentCategoryIds() {
    $aResult = array();
    foreach(self::getExternallyManagedDocumentCategories() as $oDocumentCategory) {
      $aResult[] = $oDocumentCategory->getId();
    }
    return $aResult;
  }
  
  public static function getDocumentCategoriesBackend($bInactiveOnly = false, $bIsExternallyManaged=false) {
    return self::getDocumentCategoriesSorted($bInactiveOnly, $bIsExternallyManaged);
  }
  
  public static function hasDocumentCategories($bInactiveOnly = false, $bIsExternallyManaged=false) {
    return count(self::getDocumentCategoriesSorted($bInactiveOnly, $bIsExternallyManaged)) > 0;
  }
  
  public static function getCategoryNameById($iCategoryId) {
    $oCriteria = new Criteria();
    $oDocumentCategory = self::retrieveByPK($iCategoryId);
    if($oDocumentCategory) {
      return $oDocumentCategory->getName();
    }
    return null;
  }
}

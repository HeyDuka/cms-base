<?php

require_once 'model/om/BaseLanguageObjectHistory.php';


/**
 * Skeleton subclass for representing a row from the 'language_object_history' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    model
 */
class LanguageObjectHistory extends BaseLanguageObjectHistory {

  public function getName() {
    return 'Backup '.$this->getRevision().', '.$this->getCreatedAt('Y-m-d').' / '.$this->getUser()->getInitials();
  }
  
  private function getNextRevision() {
    $oLanguageObjectHistory = LanguageObjectHistoryPeer::doSelectOne(LanguageObjectHistoryPeer::getHistoryByObjectAndLanguageIdCriteria($this->getObjectId(), $this->getLanguageId()));
    if($oLanguageObjectHistory !== null) {
      return ($oLanguageObjectHistory->getRevision()+1);
    }
    return 1;
  }
  
  public function save($oConnection = null) {
    if($this->isNew()) {
      if(Session::getSession()->isAuthenticated()) {
        $this->setCreatedBy(Session::getSession()->getUserId());
      }
      $this->setCreatedAt(date('c'));
      $this->setRevision($this->getNextRevision());
    }
    
    return parent::save($oConnection);
  }

} // LanguageObjectHistory

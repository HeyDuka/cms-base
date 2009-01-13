<?php

require_once 'model/om/BaseTagInstance.php';


/**
 * Skeleton subclass for representing a row from the 'tag_instances' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package model
 */	
class TagInstance extends BaseTagInstance {
  public function getCorrespondingDataEntry() { 
    if($this->getModelName() != '') {
      $sModelPeerName = $this->getModelName()."Peer";
      return call_user_func(array($sModelPeerName, 'retrieveByPk'), $this->getTaggedItemId());
    }
    return null;
  }
  
  //Returns the OBJECT's name. call getTag()->getName() to get the tag name
  public function getName() {
    $oDataEntry = $this->getCorrespondingDataEntry();
    if($oDataEntry === null) {
      return "";
    }
    return Util::nameForObject($oDataEntry);
  }
} // TagInstance

<?php

  // include base peer class
  require_once 'model/om/BaseRightPeer.php';
  
  // include object class
  include_once 'model/Right.php';


/**
 * Skeleton subclass for performing query and update operations on the 'rights' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package model
 */	
class RightPeer extends BaseRightPeer {
  // public static function getRightForGroupAndPage($mGroup, $mPage) {
  //   if($mGroup instanceof Group) {
  //     $mGroup = $mGroup->getId();
  //   }
  //   if($mPage instanceof Page) {
  //     $mPage = $mPage->getId();
  //   }
  //   $oCriteria = new Criteria();
  //   $oCriteria->add(self::GROUP_ID, $mGroup);
  //   $oCriteria->add(self::PAGE_ID, $mPage);
  //   return self::doSelectOne($oCriteria);
  // }
} // RightPeer

<?php
/**
 * classname:   Util
 */
class Util {
  private static $SQL_ENCODINGS = array("utf-8" => "utf8",
                                        "iso-8859-1" => "latin1",
                                        "iso-8859-2" => "latin2");

  private static $COMMON_DESCRIPTION_METHODS = array("getDescription", "getText", "getTitle", "__toString");
  private static $COMMON_NAME_METHODS = array("getName", "getFullName", "getTitle", "getExtension", "getStringKey", "getId");

  public static function convertEncodingNameToSql($sEncoding) {
    if(isset(self::$SQL_ENCODINGS[$sEncoding])) {
      return self::$SQL_ENCODINGS[$sEncoding];
    }
    return $sEncoding;
  }

  public static function equals($mFirst, $mSecond, $sKeyMethod = null) {
    if($mFirst === $mSecond) {
      return true;
    }
    if($mFirst instanceof BaseObject && $mSecond instanceof BaseObject) {
      return ($mFirst->getPeer() === $mSecond->getPeer()) && ($mFirst->getPrimaryKey() === $mSecond->getPrimaryKey());
    }
    if(is_object($mFirst)) {
      if($sKeyMethod === null) {
        $mFirst = self::idForObject($mFirst);
      } else {
        $mFirst = $mFirst->$sKeyMethod();
      }
    }
    if(is_object($mSecond)) {
      if($sKeyMethod === null) {
        $mSecond = self::idForObject($mSecond);
      } else {
        $mSecond = $mFirst->$sKeyMethod();
      }
    }
    return $mFirst === $mSecond;
  }

  public static function nameForObject($oObject) {
    if(!is_object($oObject)) {
      return $oObject;
    }
    foreach(self::$COMMON_NAME_METHODS as $sMethodName) {
      if(method_exists($oObject, $sMethodName)) {
        return $oObject->$sMethodName();
      }
    }
    return "";
  }

  // used in BackendModule only, could be used from somewhere else?
  public static function idForObject($oObject) {
    if(!is_object($oObject)) {
      return $oObject;
    }
    if(method_exists($oObject, 'getIdMethodName')) {
      $sMethodName = $oObject->getIdMethodName();
      return $oObject->$sMethodName();
    }
    foreach(array_reverse(self::$COMMON_NAME_METHODS) as $sMethodName) {
      if(method_exists($oObject, $sMethodName)) {
        return $oObject->$sMethodName();
      }
    }
    return "";
  }

  // used in Template only, could be used from somewhere else?
  public static function descriptionForObject($oObject) {
    if(is_string($oObject)) {
      return $oObject;
    }
    foreach(array_merge(self::$COMMON_DESCRIPTION_METHODS, self::$COMMON_NAME_METHODS) as $sMethodName) {
      if(method_exists($oObject, $sMethodName)) {
        return $oObject->$sMethodName();
      }
    }
    return "";
  }

  public static function dumpAll() {
    $aArgs = func_get_args();
    ob_clean();
    header("Content-Type: text/plain;charset=".Settings::getSetting('encoding', 'browser', 'utf-8'));
    call_user_func_array('var_dump', $aArgs);
    exit();
  }

  public static function fireDump() {
    $aArgs = func_get_args();
    require_once("FirePHPCore/fb.php");
    if(count($aArgs) === 1) {
      $aArgs = $aArgs[0];
    }
    fb($aArgs);
  }

  public static function addSortColumn($oCriteria, $sSortColumn, $sSortOrder = 'asc') {
    if($sSortOrder === true) {
      $sSortOrder = 'asc';
    } else if($sSortOrder === false) {
      $sSortOrder = 'desc';
    }
    $sMethod = 'add'.ucfirst(strtolower($sSortOrder)).'endingOrderByColumn';
    $oCriteria->$sMethod($sSortColumn);
  }
}
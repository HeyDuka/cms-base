<?php
/**
 * @package utils
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
	
	public static function uuid() {
	 // The field names refer to RFC 4122 section 4.1.2
	return sprintf('%04x%04x-%04x-%03x4-%04x-%04x%04x%04x',
			mt_rand(0, 65535), mt_rand(0, 65535), // 32 bits for "time_low"
			mt_rand(0, 65535), // 16 bits for "time_mid"
			mt_rand(0, 4095),	 // 12 bits before the 0100 of (version) 4 for "time_hi_and_version"
			bindec(substr_replace(sprintf('%016b', mt_rand(0, 65535)), '01', 6, 2)),
			// 8 bits, the last two of which (positions 6 and 7) are 01, for "clk_seq_hi_res"
			// (hence, the 2nd hex digit after the 3rd hyphen can only be 1, 5, 9 or d)
			// 8 bits for "clk_seq_low"
			mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535) // 48 bits for "node"
		 );
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
	
		/**
	 * hasBitsSet()
	 *
	 * @param int bitmap of bits to be checked (needle)
	 * @param int bitmap to check against (haystack)
	 * @return bolean, true if _ALL_ bits of needle are set in haystack, false otherwise
	 */
	public static function hasBitsSet($iBits, $iBitmap) {
		return (($iBitmap & $iBits) === ($iBits));
	}
	
	public static function bitmapToBitsArray($iBitmap) {
		$aBitsDissected = array();
		for ($iBitToCheck = 1; $iBitToCheck <= $iBitmap; $iBitToCheck *= 2) {
			if (self::hasBitsSet($iBitToCheck, $iBitmap)) {
				$aBitsDissected[] = $iBitToCheck;
			}
		}
		return $aBitsDissected;
	}
	
	public static function formatCreatedInfo($oGameEpisode) {
		return self::formatCreatedAtForAdmin($oGameEpisode).' / '.self::getCreatedByIfSet($oGameEpisode);
	}
	
	public static function formatUpdatedInfo($oGameEpisode) {
		return self::formatUpdatedAtForAdmin($oGameEpisode).' / '.self::getUpdatedByIfSet($oGameEpisode);
	}

	public static function formatCreatedAtForAdmin($oObject, $sTimeFormat = 'h:m') {
		if($oObject->getCreatedAt() != null) {
	  	return $oObject->getCreatedAtFormatted().', '.$oObject->getCreatedAt($sTimeFormat);
		}
	}
	
	public static function formatUpdatedAtForAdmin($oObject, $sTimeFormat = 'h:m') {
		if($oObject->getUpdatedAt() != null) {
	  return $oObject->getUpdatedAtFormatted().', '.$oObject->getUpdatedAt($sTimeFormat);
		}
	}

	public static function getCreatedByIfSet($oObject) {
	  if($oObject->getUserRelatedByCreatedBy()) {
	    return $oObject->getUserRelatedByUpdatedBy()->getInitials();
	  }
	}
	
	public static function getUpdatedByIfSet($oObject) {
	  if($oObject->getUserRelatedByUpdatedBy()) {
	    return $oObject->getUserRelatedByUpdatedBy()->getInitials();
	  }
	}
}
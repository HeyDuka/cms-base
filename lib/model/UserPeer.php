<?php

	// include base peer class
	require_once 'model/om/BaseUserPeer.php';
	
	// include object class
	include_once 'model/User.php';
	
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'admin');

class UserPeer extends BaseUserPeer {
	
	const BACKEND_USER = '1';
	const BACKEND_ADMINISTRATOR = '2';
	const BACKEND_USER_WITH_RIGHTS = '3';
	const BACKEND_USER_OTHER = '4'; // is_inactive or no rights at all
	const FRONTEND_USER = '5';
	
	private static $USER_OPTIONS = array(
																				'1' => 'backend_user',
																				'2' => 'backend_administrator',
																				'3' => 'backend_user_with_rights',
																				'4' => 'backend_user_other',
																				'5' => 'frontend_user',
																				);

	public static function getUserOptions() {
		$aUserOptions = array();
		foreach(self::$USER_OPTIONS as $iKey => $iValue) {
			switch($iKey) {
				case '2': $sCheckMethod = 'hasBackendAdministrators'; break;
				case '3': $sCheckMethod = 'hasBackendUsersWithRights'; break;
				case '4': $sCheckMethod = 'hasBackendUsersOther'; break;
				case '5': $sCheckMethod = 'hasFrontendUsers'; break;
				default:	$sCheckMethod = 'hasBackendUsers';
			}
			if(self::$sCheckMethod()) {
				$aUserOptions[$iKey] = StringPeer::getString('user.'.$iValue);
			}
		}
		return $aUserOptions;
	}
		
	public static function hasBackendUsers() {
		return self::getBackendUsers(null, null, true) > 0;
	}
	
	public static function hasBackendAdministrators() {
		return self::getBackendAdministrators(null, null, true) > 0;
	}
	
	public static function hasBackendUsersOther() {
		return self::getBackendUsersOther(null, null, true) > 0;
	}
	
	public static function hasBackendUsersWithRights() {
		return self::getBackendUsersWithRights(null, null, true) > 0;
	}
	
	public static function hasFrontendUsers() {
		return self::getFrontendUsers(null, true) > 0;
	}
	
	public static function getBackendAdministrators($sSearch=null, $iUserId=null, $bCountOnly=false) {
		$oCriteria = new Criteria();
		$oCriteria = self::getUsersCriteria($sSearch, true, $iUserId, $oCriteria);
		$oCriteria->add(UserPeer::IS_ADMIN, true);
		if($bCountOnly === true) {
			return self::doCount($oCriteria);
		}
		return self::doSelect($oCriteria);
	}
	
	public static function getBackendUsers($sSearch=null, $iUserId=null, $bCountOnly=false, $oCriteria=null) {
		$oCriteria = self::getUsersCriteria($sSearch, true, $iUserId, $oCriteria);
		if($bCountOnly === true) {
			return self::doCount($oCriteria);
		}
		return self::doSelect($oCriteria);	
	}
	
	public static function getBackendUsersWithRights($sSearch=null, $iUserId=null, $bCountOnly=false) {
		$oCriteria = new Criteria();
		$oCriteria->add(UserPeer::IS_ADMIN, false);
		$oCriteria->addJoin(UserPeer::ID, UserGroupPeer::USER_ID, Criteria::INNER_JOIN);
		$oCriteria = self::getUsersCriteria($sSearch, true, $iUserId, $oCriteria);
		if($bCountOnly === true) {
			return self::doCount($oCriteria);
		}
		return self::doSelect($oCriteria);
	}
	
	public static function getUsersWithRights($mRightIds) {
		if(!is_array($mRightIds)) {
			$mRightIds = array($mRightIds);
		}
		$oCriteria = new Criteria();
		$oCriteria->add(UserPeer::IS_INACTIVE, false);
		$oCriteria->addJoin(UserPeer::ID, UserGroupPeer::USER_ID, Criteria::INNER_JOIN);
		$oCriteria->add(UserGroupPeer::GROUP_ID, $mRightIds, Criteria::IN);
		return self::doSelect($oCriteria);
	}
	
	public static function getBackendUsersOther($sSearch=null, $iUserId=null, $bCountOnly=false) {
		$oCriteria = new Criteria();
		$oCriteria->add(self::IS_ADMIN, false);
		$aBackendUsers = self::getBackendUsers($sSearch, $iUserId, $bCountOnly, $oCriteria);
		$aBackendUsersWithRights = self::getBackendUsersWithRights($sSearch, $iUserId, $bCountOnly);
		if($bCountOnly) {
			return ($aBackendUsers - $aBackendUsersWithRights) > 0;
		}
		foreach($aBackendUsersWithRights as $i => $oBackendUser) {
			if(ArrayUtil::inArray($oBackendUser, $aBackendUsers)) unset($aBackendUsers[$i]);
		}
		return $aBackendUsers;
	}
	
	public static function getFrontendUsers($sSearch, $bCountOnly=false) {
		$oCriteria = self::getUsersCriteria($sSearch, false);
		if($bCountOnly === true) {
			return self::doCount($oCriteria);
		}
		return self::doSelect($oCriteria);
	}

	public static function getUsersCriteria($sSearch=null, 
																	$bIsBackendUserEnabled=null, 
																	$iUserId=null, 
																	$oCriteria=null,
																	$sSortField='last_name', 
																	$sSortOrder='ASC') {
		$oCriteria = $oCriteria !== null ? $oCriteria : new Criteria();
		if($sSearch !== null) {
			self::addSearchToCriteria($sSearch, $oCriteria);
		}
		if($bIsBackendUserEnabled !== null) {
			$oCriteria->add(UserPeer::IS_BACKEND_LOGIN_ENABLED, $bIsBackendUserEnabled);
		}
		if ($iUserId !== null) {
			$oCriteria->add(UserPeer::ID, $iUserId);
		}
		Util::addSortColumn($oCriteria, constant('UserPeer::'.strtoupper($sSortField)), $sSortOrder);
		if($sSortField != 'last_name') {
			$oCriteria->addAscendingOrderByColumn(UserPeer::LAST_NAME);
		}
		return $oCriteria;
	}

	public static function addSearchToCriteria($sSearch, $oCriteria) {
		$oSearchCriterion = $oCriteria->getNewCriterion(UserPeer::FIRST_NAME, 'CONCAT(' . UserPeer::FIRST_NAME . '," ",' . UserPeer::LAST_NAME.') LIKE ("%' . $sSearch. '%")', Criteria::CUSTOM);
		$oSearchCriterion->addOr($oCriteria->getNewCriterion(UserPeer::USERNAME, "%$sSearch%", Criteria::LIKE));
		$oSearchCriterion->addOr($oCriteria->getNewCriterion(UserPeer::EMAIL, "%$sSearch%", Criteria::LIKE));
		$oCriteria->add($oSearchCriterion);
	}

	public static function initializeFirstUserIfEmpty($sUsername = null, $sPassword = null) {
		if (self::doCount(new Criteria()) !== 0) {
			return false;
		}
		$sUsername = $sUsername !== null ? $sUsername : ADMIN_USERNAME;
		$sPassword = $sPassword !== null ? $sPassword : ADMIN_PASSWORD;
		$oFirstUser = new User();
		$oFirstUser->setPassword($sPassword);
		$oFirstUser->setUsername($sUsername);
		$oFirstUser->setIsAdmin(true);
		$oFirstUser->setLanguageId(Settings::getSetting("session_default", Session::SESSION_LANGUAGE_KEY, 'en'));
		$oFirstUser->save();
		return true;
	}
		
	public static function getFirstUser() {
		return self::doSelectOne(new Criteria());
	}
	
	public static function userExists($mUser=null) {
		if($mUser === null) {
			return false;
		}
		if($mUser instanceof User) {
			$iUserId = $mUser->getId();
		} else {
			$iUserId = $mUser;
		}
		return self::retrieveByPK($iUserId) !== null;
	}
	
	public static function getUserByEmail($sEmail) {
		$oCriteria = new Criteria();
		$oCriteria->add(UserPeer::EMAIL, $sEmail);
		return UserPeer::doSelectOne($oCriteria);
	}
	
	public static function getUserByUserName($sUserName, $bActiveOnly = false) {
		$oCriteria = new Criteria();
		$oCriteria->add(self::USERNAME, $sUserName);
		if($bActiveOnly) {
			$oCriteria->add(self::IS_INACTIVE, false);
		}
		return self::doSelectOne($oCriteria);
	}
	
}

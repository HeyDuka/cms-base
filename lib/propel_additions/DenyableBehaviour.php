<?php
/**
 * Denies CUD to certain models based on roles/rights.
 *
 * @package    propel.generator.behavior
 */
class DenyableBehaviour extends Behavior {
	///Run denyable as soon as possible so as to not have other behaviors pollute the modified columns
	protected $tableModificationOrder = 10;
	
	protected $parameters = array(
		'mode' => '', //Possible values: ''  (defaults to 'by_role' if 'role_key' given, 'allow' otherwise) 'allow', 'valid_user', 'backend_user', 'admin_user', 'administrator'
		'role_key' => '', //Defaults to the table name but does not cause 'mode' or 'owner_allowed' to default to 'role_key' if not explicit
		'owner_allowed' => '' //Possible values: '' (defaults to 'by_role' if 'role_key' given, 'false' otherwise), 'by_role', 'true', 'false', 'no_delete'
	);

	public function getParameter($sName) {
		$sParam = parent::getParameter($sName);
		if($sName === 'mode' && $sParam === '') {
			$sParam = parent::getParameter('role_key') ? 'by_role' : 'allow';
		}
		if($sName === 'owner_allowed' && $sParam === '') {
			$sParam = $this->getParameter('mode') === 'by_role' ? 'by_role' : 'false';
		}
		if($sName === 'role_key' && $sParam === '') {
			$sParam = $this->getTable()->getCommonName();
		}
		return $sParam;
	}

	/**
	 * This method is automatically called on database behaviors when the database model is finished
	 * Propagate the behavior to the tables of the database
	 * Override this method to have a database behavior do something special
	 */
	public function modifyDatabase() {
		foreach ($this->getDatabase()->getTables() as $oTable) {
			if($oTable->hasBehavior($this->getName())) {
				continue;
			}
			$oTable->addBehavior(clone $this);
		}
	}

	public function preInsert($oBuilder) {
		return $this->addPre('insert', $oBuilder);
	}

	public function preUpdate($oBuilder) {
		return $this->addPre('update', $oBuilder);
	}

	public function preDelete($oBuilder) {
		return $this->addPre('delete', $oBuilder);
	}

	private function addPre($sAction, $oBuilder) {
		$sPeerClassname = $oBuilder->getStubPeerBuilder()->getClassname();
		$sMode = $this->getParameter('mode');
		if($sMode === 'allow') {
			$sMode = 'custom.'.$this->getTable()->getCommonName();
		}
		$sActionModeEscaped = '"'.$sAction.'.'.$sMode.'"';
		$sActionEscaped = '"'.addslashes($sAction).'"';
		$sRoleKeyEscaped = '"'.addslashes($this->getParameter('role_key')).'"';
		return 'if(!('.$sPeerClassname.'::isIgnoringRights() || $this->mayOperate('.$sActionEscaped.'))) {
	throw new PropelException(new NotPermittedException('.$sActionModeEscaped.', array("role_key" => '.$sRoleKeyEscaped.')));
}
';
	}

	public function objectMethods($oBuilder) {
		$sMethods = '';
		$sMethods .= $this->addMayOperate($oBuilder);
		$sMethods .= $this->addConvenienceMayInsert();
		$sMethods .= $this->addConvenienceMayUpdate();
		$sMethods .= $this->addConvenienceMayDelete();
		return $sMethods;
	}

	private function addMayOperate($oBuilder) {
		$sPeerClassname = $oBuilder->getStubPeerBuilder()->getClassname();
		return 'public function mayOperate($sOperation, $oUser = false) {
	if($oUser === false) {
		$oUser = Session::getSession()->getUser();
	}
	if($oUser && ($this->isNew() || $this->getCreatedBy() === $oUser->getId()) && '.$sPeerClassname.'::mayOperateOnOwn($oUser, $this, $sOperation)) {
		return true;
	}
	return '.$sPeerClassname.'::mayOperateOn($oUser, $this, $sOperation);
}
';
	}

	private function addConvenienceMayInsert() {
		return 'public function mayBeInserted($oUser = false) {
	return $this->mayOperate($oUser, "insert");
}
';
	}

	private function addConvenienceMayUpdate() {
		return 'public function mayBeUpdated($oUser = false) {
	return $this->mayOperate($oUser, "update");
}
';
	}

	private function addConvenienceMayDelete() {
		return 'public function mayBeDeleted($oUser = false) {
	return $this->mayOperate($oUser, "delete");
}
';
	}

	public function staticAttributes($oBuilder) {
		$sAttrs = '';
		$sAttrs .= $this->addIgnoreProp();
		return $sAttrs;
	}

	private function addIgnoreProp() {
		return 'private static $IGNORE_RIGHTS = false;';
	}

	public function staticMethods($oBuilder) {
		$sMethods = '';
		$sMethods .= $this->addIgnoreMethod();
		$sMethods .= $this->addIsIgnoringMethod();
		$sMethods .= $this->addMayMethod();
		$sMethods .= $this->addMayOwnMethod();
		return $sMethods;
	}

	private function addIgnoreMethod() {
		return 'public static function ignoreRights($bIgnore = true) {
	self::$IGNORE_RIGHTS = $bIgnore;
}
';
	}
	
	private function addIsIgnoringMethod() {
		return 'public static function isIgnoringRights() {
	return self::$IGNORE_RIGHTS;
}
';
	}

	private function addMayMethod() {
		if($this->getParameter('mode') === 'allow') {
			return $this->addMayMethodForAllow();
		}
		if($this->getParameter('mode') === 'valid_user') {
			return $this->addMayMethodForValidUser();
		}
		if($this->getParameter('mode') === 'backend_user') {
			return $this->addMayMethodForBackendUser();
		}
		if($this->getParameter('mode') === 'admin_user') {
			return $this->addMayMethodForAdminUser();
		}
		if($this->getParameter('mode') === 'administrator') {
			return $this->addMayMethodForAdministrator();
		}

		return $this->addMayMethodForRoleKey($this->getParameter('role_key'));
	}

	private function addMayMethodForRoleKey($sRoleKey) {
		$sRoleKey = '"'.addslashes($sRoleKey).'"';
		return 'public static function mayOperateOn($oUser, $mObject, $sOperation) {
	if($oUser === null) {
		return false;
	}
	if($oUser->getIsAdmin()) {
		return true;
	}
	return $oUser->hasRole('.$sRoleKey.');
}
';
	}
	
	private function addMayMethodForValidUser() {
		return 'public static function mayOperateOn($oUser, $mObject, $sOperation) {
	return $oUser !== null;
}
';
	}
	
	private function addMayMethodForBackendUser() {
		return 'public static function mayOperateOn($oUser, $mObject, $sOperation) {
	if($oUser === null) {
		return false;
	}
	if($oUser->getIsAdmin()) {
		return true;
	}
	return $oUser->getIsBackendLoginEnabled();
}
';
	}
	
	private function addMayMethodForAdminUser() {
		return 'public static function mayOperateOn($oUser, $mObject, $sOperation) {
	if($oUser === null) {
		return false;
	}
	if($oUser->getIsAdmin()) {
		return true;
	}
	return $oUser->getIsAdminLoginEnabled();
}
';
	}
	
	private function addMayMethodForAdministrator() {
		return 'public static function mayOperateOn($oUser, $mObject, $sOperation) {
	if($oUser === null) {
		return false;
	}
	return $oUser->getIsAdmin();
}
';
	}
	
	private function addMayMethodForAllow() {
		return 'public static function mayOperateOn($oUser, $mObject, $sOperation) {
	return true;
}
';
	}
	
	private function addMayOwnMethod() {
		if($this->getParameter('owner_allowed') === 'by_role') {
			return $this->addMayOwnMethodForRoleKey($this->getParameter('role_key'));
		}
		if($this->getParameter('owner_allowed') === 'true') {
			return $this->addMayOwnMethodForAll();
		}
		if($this->getParameter('owner_allowed') === 'no_delete') {
			return $this->addMayOwnMethodForInsertAndUpdate();
		}

		return $this->addMayOwnMethodForNone();
	}

	private function addMayOwnMethodForRoleKey($sRoleKey) {
		$sOwnRoleKey = '"'.addslashes($sRoleKey.'-own').'"';
		return 'public static function mayOperateOnOwn($oUser, $mObject, $sOperation) {
	return $oUser->hasRole('.$sOwnRoleKey.');
}
';
	}

	private function addMayOwnMethodForAll() {
		return 'public static function mayOperateOnOwn($oUser, $mObject, $sOperation) {
	return true;
}
';
	}
	
	private function addMayOwnMethodForInsertAndUpdate() {
		return 'public static function mayOperateOnOwn($oUser, $mObject, $sOperation) {
	return $sOperation !== "delete";
}
';
	}
	
	private function addMayOwnMethodForNone() {
		return 'public static function mayOperateOnOwn($oUser, $mObject, $sOperation) {
	return false;
}
';
	}


}

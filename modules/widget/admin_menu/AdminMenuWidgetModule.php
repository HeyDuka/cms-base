<?php
/**
 * @package modules.widget
 */
class AdminMenuWidgetModule extends WidgetModule {
	public function __construct() {
		$oResourceIncluder = ResourceIncluder::defaultIncluder();
		$oResourceIncluder->addResource('admin/admin_menu.js');
	}
	
	public static function isSingleton() {
		return true;
	}
	
	public function getLoggedInName() {
		if(Session::getSession()->isAuthenticated()) {
			return Session::getSession()->getUser()->getFullName();
		}
	}
	
	public function getModuleConfig() {
		$oUser = Session::getSession()->getUser();
		$aSettings = $oUser->getAdminSettings('admin_menu');
		return $aSettings;
	}
	
	public function doWidget() {
		$oTemplate = $this->constructTemplate('menu_bar');
		if(Session::getSession()->isAuthenticated()) {
			$oTemplate->replaceIdentifier('style', 'block');
		}
		return $oTemplate;
	}
	
	public function getModule($sName) {
		$aResult = array('link' => LinkUtil::link(array($sName), 'AdminManager'), 'title' => AdminModule::getDisplayNameByName($sName), 'may' => Session::getSession()->getUser()->mayUseAdmimModule($sName));
		return $aResult;
	}
}
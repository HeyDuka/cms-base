<?php
/**
 * @package modules.frontend
 * 
 * NOTE
 * if the login page is protected, then the origin redirect does not work 
 * if the login is called from an unprotected page, since origin will be overwritten
 * is there a reason to protect a login page?
 */

class LoginFrontendModule extends DynamicFrontendModule implements WidgetBasedFrontendModule {
	
	private $oPage;
	public static $DISPLAY_OPTIONS = array('login_simple', 'login_with_password_forgotten', 'logout_link');

	const MODE_SELECT_KEY = 'display_mode';

	public function __construct(LanguageObject $oLanguageObject = null, $aRequestPath = null, $iId = 1) {
		parent::__construct($oLanguageObject, $aRequestPath, $iId);
		$this->oPage = FrontendManager::$CURRENT_PAGE;
	}

	public function renderFrontend($sAction = 'login') {
		$aOptions = @unserialize($this->getData());
		if(isset($aOptions[self::MODE_SELECT_KEY]) && $aOptions[self::MODE_SELECT_KEY] === 'logout_link') {
			return $this->renderLogout();
		}
		$sLoginType = isset($aOptions[self::MODE_SELECT_KEY]) ? $aOptions[self::MODE_SELECT_KEY] : 'login_simple';
		if(Session::getSession()->getUser()) {
			$oPage = $this->oPage;
			if(Session::getSession()->hasAttribute('login_referrer_page')) {
				$oPage = Session::getSession()->getAttribute('login_referrer_page');
				Session::getSession()->resetAttribute('login_referrer_page');
			}
			if(!$this->oPage->getIsProtected() || Session::getSession()->getUser()->mayViewPage($this->oPage)) {
				$oTemplate = $this->constructTemplate('logout');
				$oTemplate->replaceIdentifier('fullname', Session::getSession()->getUser()->getFullName());
				$oTemplate->replaceIdentifier('name', Session::getSession()->getUser()->getUsername());
				$oTemplate->replaceIdentifier('action', LinkUtil::link($this->oPage->getFullPathArray(), null, array('logout' => 'true')));
				return $oTemplate;
			} else {
				$oFlash = Flash::getFlash();
				$oFlash->addMessage('login.logged_in_no_access');
			}
		}
		$oTemplate = $this->constructTemplate('login');
		$oTemplate->replaceIdentifier('login_action', $sAction);
		$oTemplate->replaceIdentifier('login_title', StringPeer::getString('login'));
		$oTemplate->doIncludes();
		$sOrigin = isset($_REQUEST['origin']) ? $_REQUEST['origin'] : LinkUtil::linkToSelf();
		$oTemplate->replaceIdentifier('origin', $sOrigin);
		$oLoginPage = $this->oPage->getLoginPage();
		if($oLoginPage === null) {
			throw new Exception('Error in '.__METHOD__.' There is no page with PAGE_TYPE login');
		}
		$oTemplate->replaceIdentifier('action', LinkUtil::link($oLoginPage->getFullPathArray()));

		if($sAction === 'login') {
			$this->renderLogin($oTemplate, $sLoginType);
		}
		return $oTemplate;
	}
 
	private function renderLogin($oTemplate, $sLoginType) {
		if($sLoginType === 'login_with_password_forgotten') {
			$oLoginPage = $this->oPage->getLoginPage();
			// jm had an error because of the missing loginPage, why, thats why I added this code
			if($oLoginPage === null) {
				$oCriteria = new Criteria();
				$oCriteria->add(PagePeer::PAGE_TYPE, 'login');
				$oLoginPage = PagePeer::doSelectOne($oCriteria);
				if(!$oLoginPage || $oLoginPage->getPageType() !== 'login') {
					throw new Exception('Error in '.__METHOD__.' There is no page with PAGE_TYPE login');
				}
			}
			$oTemplate->replaceIdentifier('password_forgotten_action', LinkUtil::link($oLoginPage->getFullPathArray(), null, array('password_forgotten' => 'true')));
		}
	}
	
	public function renderLogout() {
		if(Session::getSession()->getUser() === null) {
			return null;
		}
		$oTemplate = $this->constructTemplate('logout');
		$oTemplate->replaceIdentifier('fullname', Session::getSession()->getUser()->getFullName());
		$oTemplate->replaceIdentifier('action', LinkUtil::link($this->oPage->getFullPathArray(), null, array('logout' => 'true')));
		return $oTemplate;
	}

	public function widgetData() {
		return @unserialize($this->getData());	
	}
	
	public function widgetSave($mData) {
		$this->oLanguageObject->setData(serialize($mData));
		return $this->oLanguageObject->save();
	}
	
	public function getWidget() {
		$aOptions = @unserialize($this->getData());	
		$oWidget = new LoginEditWidgetModule(null, $this);
		$oWidget->setDisplayMode($aOptions[self::MODE_SELECT_KEY]);
		return $oWidget;
	}
}

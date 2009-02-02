<?php
/**
 * class BackendManager
 * @package manager
 */
class BackendManager extends Manager {
  
  private $oTemplate;
  private $sModuleName;
  private $iId = null;
  private $oBackendModule = null;
  public static $MODULES = array();
  const CONTENT_EDIT_LANGUAGE_SESSION_KEY = 'content_edit_language';

  public function __construct() {
    parent::__construct();
    
    if(!Session::getSession()->isAuthenticated() || !Session::getSession()->getUser()->getIsBackendLoginEnabled()) {
      if(Session::getSession()->isAuthenticated() && !Session::getSession()->getUser()->getIsBackendLoginEnabled()) {
        Flash::getFlash()->addMessage('backend_login_denied');
        Flash::getFlash()->stick();
      }
      unset($_REQUEST["logout"]);
      unset($_REQUEST["be_logout"]);
      Session::getSession()->setAttribute('login_referrer', Util::linkToSelf(Manager::getRequestedPath()));
      Util::redirectToManager('', 'LoginManager');
    }
    if(isset($_REQUEST['be_logout'])) {
      Cache::clearAllCaches();
      $bLogoutBackToLogin = Settings::getSetting('general', 'logout_to_login', false, 'backend');
      $aLogoutParam = array('logout' => 'true');
      if($bLogoutBackToLogin) {
        Util::redirect(Util::linkToSelf(null, $aLogoutParam));
      } else {
        $oPage = isset($_REQUEST['page_id']) ? PagePeer::retrieveByPk($_REQUEST['page_id']) : null;
        Util::redirect(self::getWebLink(($oPage !== null) ? $oPage->getFullPathArray() : null, $aLogoutParam));
      }
    }
    if(Session::getSession()->getUser()->getLanguageId() != null) {
      Session::getSession()->setLanguage(Session::getSession()->getUser()->getLanguageId());
    } else {
      if(!in_array(Session::language(), Util::getBackendLanguages())) {
        Session::getSession()->setLanguage("de");
      }
    }
    
    if(isset($_REQUEST['content_language'])) {
      self::setContentEditLanguage($_REQUEST['content_language']);
    }
    if(self::hasNextPathItem()) {
      $this->sModuleName = self::usePath();
    } else {
      $aModuleNames = array_keys(self::$MODULES['direct_links']);
      Util::redirectToManager($aModuleNames[0]);
    }
    if(isset($_REQUEST['select_link'])) {
      Util::redirectToManager($_REQUEST['select_link']);
    }
  }

  private function createTemplate() {
    if($this->oTemplate !== null) {
      return;
    }
    $sTemplateName = Settings::getSetting("backend", "main_template", "index");
    $oOutput = new XHTMLOutput('transitional');
    $oOutput->render();
    $this->oTemplate = new Template($sTemplateName, null, false, true);
  }

  public function render() {
    try {
      $this->fillBackend();
    } catch (PropelException $pe) {
      $this->createTemplate();
      if($pe->getCause() instanceof Exception) {
        $this->fillException($pe->getCause());
      } else {
        $this->fillException($pe, $pe->getCause());
      }
    } catch (Exception $e) {
      $this->createTemplate();
      $this->fillException($e);
    }
    try {
      $this->fillBackendNavigation();
    } catch (Exception $e) {
      $this->fillException($e);
    }
    try {
      $this->fillAttributes();
    } catch (Exception $e) {
      $this->fillException($e);
    }
    
    $this->oTemplate->render();
  }

  private function fillException($e, $sMessage = null) {
    $this->oTemplate->replaceIdentifierMultiple("detail", "<pre>", null, Template::NO_HTML_ESCAPE);
    if($sMessage !== null) {
      $this->oTemplate->replaceIdentifierMultiple("detail", $sMessage, null);
    }
    if($e instanceof SQLException) {
      $this->oTemplate->replaceIdentifierMultiple("detail", $e->getNativeError(), null);
    }
    $this->oTemplate->replaceIdentifierMultiple("detail", $e->getMessage(), null);
    $this->oTemplate->replaceIdentifierMultiple("detail", "In File '".$e->getFile()."' On Line ".$e->getLine(), null);
    if($e->getCode() !== 0) {
      $this->oTemplate->replaceIdentifierMultiple("detail", "Code: ".$e->getCode(), null);
    }
    $this->oTemplate->replaceIdentifierMultiple("detail", $e->getTraceAsString(), null);
    $this->oTemplate->replaceIdentifierMultiple("detail", "</pre>", null, Template::NO_HTML_ESCAPE);
    $this->oTemplate->replaceIdentifier("chooser", "An error occured in '".$this->sModuleName."'", null, Template::NO_HTML_ESCAPE);
  }
 
  private function fillBackendNavigation() {
    foreach (self::$MODULES['direct_links'] as $sModuleName => $aConfig) {
      if(isset($aConfig['disabled']) || !Module::isModuleEnabled('backend', $sModuleName)) {
        continue;
      }
      $sClassName = '';
      if($this->sModuleName == $sModuleName) {
        $sClassName = 'active';
      }
      $oTemplate = new Template('admin_navigation', array(DIRNAME_TEMPLATES, DIRNAME_BACKEND));
      
      /**
       * @todo: implement better solution for this
       */
      $aUrlParameters = array($sModuleName);
      if(($sModuleName === "pages" || $sModuleName === "content") && Manager::getCurrentPage()) {
        array_push($aUrlParameters, Manager::getCurrentPage()->getId());
      }
      $sUrl = Util::link($aUrlParameters);
      
      $oTemplate->replaceIdentifier('status', $sClassName);
      $oTemplate->replaceIdentifier('title', BackendModule::getDisplayNameByName($sModuleName));
      
      $oTemplate->replaceIdentifier('link', $sUrl);
      $this->oTemplate->replaceIdentifierMultiple("admin_links", $oTemplate);
    }
    
    $aSelectOptions = array('' => '-------');
    $sSelected = '';
    
    $aFrontendModules = BackendModule::listModules();
    foreach (self::$MODULES['site_links'] as $sModuleName => $bIsEnabled) {
      if(!$bIsEnabled || !Module::isModuleEnabled('backend', $sModuleName)) {
        continue;
      }
      
      $aSelectOptions[$sModuleName] = BackendModule::getDisplayNameByName($sModuleName);
      if($this->sModuleName == $sModuleName) {
        $sSelected = $sModuleName;
      }
    }
    $this->oTemplate->replaceIdentifier("site_select_class", $sSelected != '' ? 'active_select' : '');
    $this->oTemplate->replaceIdentifier("available_site_links", Util::optionsFromArray($aSelectOptions, $sSelected));
    
    $aAdminOptions = array('' => '-------');
    $sSelected = '';
    foreach (self::$MODULES['admin_links'] as $sModuleName => $aConfig) {
      if(!isset($aConfig['requires_right']) || ($aConfig['requires_right'] == 'is_admin' && !Session::getSession()->getUser()->getIsAdmin()) || !Module::isModuleEnabled('backend', $sModuleName)) {
        continue;
      }
      $aAdminOptions[$sModuleName] = BackendModule::getDisplayNameByName($sModuleName);
      if($this->sModuleName == $sModuleName) {
        $sSelected = $sModuleName;
      }
    }
    if(count($aAdminOptions) > 1) {
      $this->oTemplate->replaceIdentifier("admin_select_class", $sSelected != '' ? 'active_select' : '');
      $this->oTemplate->replaceIdentifier("available_admin_links", Util::optionsFromArray($aAdminOptions, $sSelected));
    }
    $this->oTemplate->replaceIdentifier("page_url", Util::link(Manager::getUsedPath(), null, array(), false));

    if(!Manager::isPost()) {
      $this->oTemplate->replaceIdentifier("available_language_options", Util::optionsFromArray(LanguagePeer::getLanguagesAssoc(), self::getContentEditLanguage(), '', array()));
      foreach(array_diff_assoc($_REQUEST, $_COOKIE) as $sName=>$sValue) {
        if($sName === 'path' || $sName === 'content_language') {
          continue;
        }
        $oHiddenInputTemplate = new Template("hidden_input", array(DIRNAME_TEMPLATES, DIRNAME_BACKEND));
        $oHiddenInputTemplate->replaceIdentifier("name", $sName);
        $oHiddenInputTemplate->replaceIdentifier("value", $sValue);
        $this->oTemplate->replaceIdentifierMultiple("request_values", $oHiddenInputTemplate);
      }
      $this->oTemplate->replaceIdentifier("method", strtolower($_SERVER['REQUEST_METHOD']));
    } else {
      if(LanguagePeer::retrieveByPk(self::getContentEditLanguage()) !== null) 
      $this->oTemplate->replaceIdentifier("static_language", LanguagePeer::retrieveByPk(self::getContentEditLanguage())->getLanguageName());
    }
    
    $this->oTemplate->replaceIdentifier("domain_name", Util::getHostName());
    $sUserName = (Session::getSession()->getUser() !== null && trim(Session::getSession()->getUser()->getFullName()) != '') ? Session::getSession()->getUser()->getFullName() : Session::getSession()->getUser()->getUsername();
    $sUserName = Session::getSession()->getUser() ? $sUserName: 'Hans Muster';
    $this->oTemplate->replaceIdentifier('user_name', $sUserName);
    $this->oTemplate->replaceIdentifier('link_to_user', Util::link(array('users', Session::getSession()->getUserId())));
  }

  private function fillBackend() {
    $this->oBackendModule = BackendModule::getModuleInstance($this->sModuleName);
    if(Manager::isPost()) {
      Util::trimStringsInArray($_POST);
      Util::runFunctionOnArrayValues($_POST, array('Util', "encode"), Settings::getSetting("encoding", "browser", "utf-8"), Settings::getSetting("encoding", "db", "utf-8"));
      if(isset($_POST['action'])) {
        $sMethod = Util::camelize($_POST['action']);
        if(method_exists($this->oBackendModule, $sMethod) || method_exists($this->oBackendModule, "__call")) {
          $this->oBackendModule->$sMethod();
        } else {
          $this->oBackendModule->doSave();
        }
      } else {
        $this->oBackendModule->doSave();
      }
    } else if (isset($_GET['action'])) {
      $sMethod = Util::camelize($_GET['action']);
      if(method_exists($this->oBackendModule, $sMethod)) {
        $this->oBackendModule->$sMethod();
      }
    }
    
    $this->createTemplate();
    $this->oTemplate->replaceIdentifier("module_name", BackendModule::getDisplayNameByName($this->sModuleName));
    $this->oTemplate->replaceIdentifier("chooser", $this->oBackendModule->getChooser(), null, Template::NO_HTML_ESCAPE);
    $mNewEntryActionParams = $this->oBackendModule->getNewEntryActionParams();
    if ($mNewEntryActionParams !== null) {
      if (!is_array($mNewEntryActionParams)) {
        throw new Exception ('BackendModule->getNewEntryActionParams() expects null or array() of params');
      }
      if(isset($mNewEntryActionParams['action'])) {
        $this->oTemplate->replaceIdentifier("new_entry_action", $mNewEntryActionParams['action']);
      }
      if(isset($mNewEntryActionParams['custom'])) {
        $this->oTemplate->replaceIdentifier("custom_select", $mNewEntryActionParams['custom']);
      }
    }
    $this->oTemplate->replaceIdentifier("detail", $this->oBackendModule->getDetail(), null, Template::NO_HTML_ESCAPE);
    $this->oTemplate->replaceIdentifierMultiple("custom_css", $this->oBackendModule->customCss(), null, Template::NO_HTML_ESCAPE);
    $this->oTemplate->replaceIdentifierMultiple("custom_js", $this->oBackendModule->customJs(), null, Template::NO_HTML_ESCAPE);
    $this->oTemplate->replaceIdentifier("custom_backend_css", Settings::getSetting('backend', 'custom_backend_css', null));
    $this->fillPanels();
  }
  
  private function fillPanels() {
    $this->fillTagPanel();
    $this->fillSearchPanel();
  }
  
  private function fillTagPanel() {
    $sModelName = $this->oBackendModule->getModelName();
    $iCurrentId = $this->oBackendModule->getCurrentId();
    if($iCurrentId === null || $sModelName === null) {
      return;
    }
    $oTagPanelTemplate = new Template('tag_panel', array(DIRNAME_TEMPLATES, DIRNAME_BACKEND));
    $oTagPanelTemplate->replaceIdentifier('model_name', $sModelName);
    $oTagPanelTemplate->replaceIdentifier('count_tags', TagInstancePeer::countByModelNameAndIdCriteria($sModelName, $iCurrentId));
    $oTagPanelTemplate->replaceIdentifier('tagged_item_id', $iCurrentId);
    $this->oTemplate->replaceIdentifier('tag_panel', $oTagPanelTemplate);
  }
  
  private function fillSearchPanel() {
    if(!$this->oBackendModule->hasSearch()) {
      return;
    }
    $oSession = Session::getSession();
    $aSearchPanelValues = $oSession->getAttribute("backend_search_panel");
    if($aSearchPanelValues === null) {
      $aSearchPanelValues = array();
    }
    if(!isset($aSearchPanelValues[$this->oBackendModule->getModuleName()])) {
      $aSearchPanelValues[$this->oBackendModule->getModuleName()] = "";
    }
    if(isset($_REQUEST['search'])) {
      $aSearchPanelValues[$this->oBackendModule->getModuleName()] = $_REQUEST['search'];
    }
    $oSession->setAttribute("backend_search_panel", $aSearchPanelValues);
    $oSearchPanelTemplate = new Template('search_panel', array(DIRNAME_TEMPLATES, DIRNAME_BACKEND));
    $oSearchPanelTemplate->replaceIdentifier('clear_link', Util::linkToSelf(null, array('search' => '')));
    $oSearchPanelTemplate->replaceIdentifier('search_value', $aSearchPanelValues[$this->oBackendModule->getModuleName()]);
    $this->oTemplate->replaceIdentifier('search_panel', $oSearchPanelTemplate);
  }
  
  public static function setCurrentPage($oPage) {
    Manager::setCurrentPage($oPage);
  }

  private function fillAttributes() {
    if(self::getCurrentPage() instanceof Page) {
      $this->oTemplate->replaceIdentifier("web_link", self::getWebLink(self::getCurrentPage()->getFullPathArray()));
      $this->oTemplate->replacePstring("preview_title", array("link_title" => self::getCurrentPage()->getLinkText()));
      $this->oTemplate->replaceIdentifier("logout_link", Util::linkToSelf(null, array("be_logout" => 'true', 'page_id' => self::getCurrentPage()->getId())));
    } else {
      $this->oTemplate->replaceIdentifier("web_link", self::getWebLink());
      $this->oTemplate->replaceIdentifier("logout_link", Util::linkToSelf(null, array("be_logout" => 'true')));
    }
    $this->oTemplate->replaceIdentifier("title", Settings::getSetting('backend', 'title', 'no title set in config cms.yml for backend'));
  }
  
  private static function getWebLink($aPath = array(), $aParameters = array()) {
    $bIsMultilingual = Settings::getSetting('general', 'multilingual', true);
    if(!$bIsMultilingual) {
      return Util::link($aPath, "FrontendManager", $aParameters);
    }
    $sLanguageId = self::getContentEditLanguage();
    $oLanguage = LanguagePeer::retrieveByPk($sLanguageId);
    if($oLanguage === null || $oLanguage->getIsActive() === false) {
      $sLanguageId = Settings::getSetting("session_default", Session::SESSION_LANGUAGE_KEY, null);
    }
    array_unshift($aPath, $sLanguageId);
    return Util::link($aPath, "FrontendManager", $aParameters, false);
  }
  
  public static function setContentEditLanguage($sLanguageId) {
    Session::getSession()->setAttribute(self::CONTENT_EDIT_LANGUAGE_SESSION_KEY, $sLanguageId);
  }
  
  public static function getContentEditLanguage() {
    $sLanguageId = Session::getSession()->getAttribute(self::CONTENT_EDIT_LANGUAGE_SESSION_KEY);
    if($sLanguageId === null) {
      $sLanguageId = Session::language();
    }
    if($sLanguageId === null) {
      $sLanguageId = Settings::getSetting("session_default", Session::SESSION_LANGUAGE_KEY, 'en');
    }
    // hack that should be removed in a future version of installManager()
    if(!LanguagePeer::languageExists($sLanguageId) && LanguagePeer::hasNoLanguage()) {
      $oLanguage = new Language();
      $oLanguage->setId($sLanguageId);
      $oLanguage->setIsActive(true);
      $oLanguage->save();
    }
    return $sLanguageId;
  }
  
  public static function shouldIncludeLanguageInLink() {
    return false;
  }
} // class BackendManager

BackendManager::$MODULES['direct_links'] = Settings::getSetting('modules', 'direct_links', 1, 'backend');
BackendManager::$MODULES['site_links'] = Settings::getSetting('modules', 'site_links', 1, 'backend');
BackendManager::$MODULES['admin_links'] = Settings::getSetting('modules', 'admin_links', 1, 'backend');
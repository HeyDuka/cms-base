<?php
/**
 * class FrontendManager
 * @package manager
 */
class FrontendManager extends Manager {
	public static $CURRENT_PAGE = null;
	public static $CURRENT_NAVIGATION_ITEM = null;
	
	protected $oTemplate;
	private $aPathRequestParams;
	protected $bIsNotFound;
	protected $oPageType;
	private $oRootNavigationItem;
	/**
	 * __construct()
	 */
	public function __construct() {
		parent::__construct();
		$this->aPathRequestParams = array();
		$this->bIsNotFound = false;
		$this->initLanguage();

		// Find requested page
		$oRootPage = PagePeer::getRootPage();
		if($oRootPage === null) {
			throw new Exception("No root node exists in the database. Use the admin tool to create one.");
		}
		$this->oRootNavigationItem = PageNavigationItem::navigationItemForPage($oRootPage);
		$oMatchingNavigationItem = $this->oRootNavigationItem;

		while(self::hasNextPathItem()) {
			$oNextNavigationItem = $oMatchingNavigationItem->namedChild(self::usePath(), Session::language(), false, true);
			if($oNextNavigationItem !== null) {
				$oMatchingNavigationItem = $oNextNavigationItem;
			} else {
				self::unusePath();
				break;
			}
		}
		
		self::$CURRENT_NAVIGATION_ITEM = $oMatchingNavigationItem;
		$oParent = $oMatchingNavigationItem;
		while(!($oParent instanceof PageNavigationItem)) {
			$oParent = $oParent->getParent();
		}
		if($oParent !== $oMatchingNavigationItem) {
			$oParent->setCurrent(false);
		}
		self::$CURRENT_PAGE = $oParent->getMe();
		// See if the filter(s) changed anything
		FilterModule::getFilters()->handleNavigationPathFound($this->oRootNavigationItem, $oMatchingNavigationItem);
		
		// There may now be new, virtual navigation items. Follow them.
		while(self::hasNextPathItem()) {
			$oNextNavigationItem = $oMatchingNavigationItem->namedChild(self::usePath(), Session::language(), false, true);
			if($oNextNavigationItem !== null) {
				$oMatchingNavigationItem = $oNextNavigationItem;
			} else {
				self::unusePath();
				break;
			}
		}
		
		// See if anything has changed
		if(self::$CURRENT_NAVIGATION_ITEM !== $oMatchingNavigationItem && self::$CURRENT_NAVIGATION_ITEM instanceof PageNavigationItem) {
			self::$CURRENT_NAVIGATION_ITEM->setCurrent(false); //It is, however, still active
		}
		self::$CURRENT_NAVIGATION_ITEM = $oMatchingNavigationItem;
		
		$iTimesUsed = 0;
		while(self::hasNextPathItem()) {
			$sKey = self::usePath();
			$iTimesUsed++;
			$sValue = null;
			if(self::hasNextPathItem()) {
				$sValue = self::usePath();
				$iTimesUsed++;
			}
			$this->aPathRequestParams[] = $sKey;
			if(!isset($_REQUEST[$sKey])) {
				$_REQUEST[$sKey] = $sValue;
			}
		}
		for($i=1;$i<=$iTimesUsed;$i++) {
			self::unusePath();
		}
		
		if($oMatchingNavigationItem->isFolder()) {
			$oFirstChild = $oMatchingNavigationItem->getFirstChild(Session::language(), false, true);
			if($oFirstChild !== null) {
				LinkUtil::redirectToManager($oFirstChild->getLink());
			} else {
				$this->bIsNotFound = true;
			}
		}
		
		if($oMatchingNavigationItem->isProtected()) {
			if(!$oMatchingNavigationItem->isAccessible()) {
				$oLoginPage = self::$CURRENT_PAGE->getLoginPage();
				if($oLoginPage !== self::$CURRENT_PAGE) {
					Session::getSession()->setAttribute('login_referrer_page', self::$CURRENT_PAGE);
					Session::getSession()->setAttribute('login_referrer', LinkUtil::link($oMatchingNavigationItem->getLink(), "FrontendManager"));
				}
				if($oLoginPage === null) {
					LinkUtil::redirect(LinkUtil::link('', "LoginManager"));
				}
				self::$CURRENT_PAGE = $oLoginPage;
			}
		}
		
		FilterModule::getFilters()->handlePageHasBeenSet(self::$CURRENT_PAGE, $this->bIsNotFound, self::$CURRENT_NAVIGATION_ITEM);
	}
	
	protected function initLanguage() {
		if(self::hasNextPathItem() && LanguagePeer::languageIsActive(self::peekNextPathItem(), true)) {
			$oLanguage = LanguagePeer::retrieveByPath(self::usePath());
			Session::getSession()->setLanguage($oLanguage);
		} else {
			if(!LanguagePeer::languageIsActive(Session::language())) {
				Session::getSession()->resetAttribute(Session::SESSION_LANGUAGE_KEY);
			}
			if(!LanguagePeer::languageIsActive(Session::language())) {
				LinkUtil::redirectToManager('', "AdminManager");
			}
			LinkUtil::redirectToLanguage();
		}
	}
	
	/**
	 * render()
	 */
	public function render() {
		FilterModule::getFilters()->handleRequestStarted();
		$bIsDynamic = false;
		$aAllowedParams = array('container' => array(), 'navigation' => array());
		
		$bIsAjaxRequest = Manager::isPost() && Manager::isXMLHttpRequest();
		$aAjaxSections = array();
		///@todo remove legacy support when the need fades
		$bIsLegacyAjaxRequest = $bIsAjaxRequest && isset($_REQUEST['container_only']);
		if($bIsAjaxRequest) {
			if($bIsLegacyAjaxRequest) {
				$_REQUEST['ajax_containers'] = array($_REQUEST['container_only']);
			}
			if(isset($_REQUEST['ajax_containers'])) {
				sort($_REQUEST['ajax_containers']);
				$aAjaxSections['container'] = $_REQUEST['ajax_containers'];
			}
			if(isset($_REQUEST['ajax_navigations'])) {
				sort($_REQUEST['ajax_navigations']);
				$aAjaxSections['navigation'] = $_REQUEST['ajax_navigations'];
			}
			if(isset($_REQUEST['ajax_title'])) {
				$aAjaxSections = array_merge($aAjaxSections, array('page_title' => true, 'link_text' => true, 'title' => true));
			}
			asort($aAjaxSections);
		}
		
		
		$sPageType = self::$CURRENT_PAGE->getPageType();
		$this->oPageType = PageTypeModule::getModuleInstance($sPageType, self::$CURRENT_PAGE, self::$CURRENT_NAVIGATION_ITEM);
		$this->oPageType->setIsDynamicAndAllowedParameterPointers($bIsDynamic, $aAllowedParams, ($bIsAjaxRequest ? $aAjaxSections['container'] : null));
		
		$bIsDynamic = $bIsDynamic || !$this->useFullPageCache();
		$bParamsNotAllowed = count(array_intersect($this->aPathRequestParams, $aAllowedParams)) !== count($this->aPathRequestParams);
		
		$this->bIsNotFound = $this->bIsNotFound || $bParamsNotAllowed;
		FilterModule::getFilters()->handlePageNotFoundDetectionComplete($this->bIsNotFound, self::$CURRENT_PAGE, self::$CURRENT_NAVIGATION_ITEM, array(&$this->bIsNotFound));
		
		if($this->bIsNotFound) {
			FilterModule::getFilters()->handlePageNotFound();
			LinkUtil::sendHTTPStatusCode(404, 'Not Found');
			$sErrorPageName = Settings::getSetting('error_pages', 'not_found', null);
			$oPage = null;
			if($sErrorPageName) {
				$oPage = PagePeer::getPageByName($sErrorPageName);
			}
			if($oPage === null) {
				die(StringPeer::getString('wns.page.not_found'));
			}
			self::$CURRENT_PAGE = $oPage;
			self::$CURRENT_NAVIGATION_ITEM = PageNavigationItem::navigationItemForPage($oPage);
			
			//Set correct page type of 404 page
			$sPageType = self::$CURRENT_PAGE->getPageType();
			$this->oPageType = PageTypeModule::getModuleInstance($sPageType, self::$CURRENT_PAGE);
		}
		
		if(!$bIsAjaxRequest) {
			$oOutput = $this->getXHTMLOutput();
			$oOutput->render();
		} else if(!$bIsLegacyAjaxRequest) {
			header("Content-Type: application/json;charset=utf-8");
		}
		
		$sPageIdentifier = implode('/', self::$CURRENT_NAVIGATION_ITEM->getLink()).'_'.Session::language();
		if($bIsAjaxRequest) {
			$sPageIdentifier .= '_'.serialize($aAjaxSections);
		}
		
		$oCache = null;
		
		$bIsCached = false;
		if(!$bIsDynamic && !Session::getSession()->isAuthenticated() && !$this->bIsNotFound) {
			$oCache = new Cache($sPageIdentifier, DIRNAME_FULL_PAGE);
		
			$bIsCached = $oCache->cacheFileExists();
			$bIsOutdated = false;
		
			if($bIsCached) {
				$bIsOutdated = $oCache->isOlderThan(self::$CURRENT_PAGE->getUpdatedAtTimestamp());
			}
			if($bIsCached && !$bIsOutdated) {
				return print $oCache->getContentsAsString();
			}
		}
		
		// Init the template
		if($bIsLegacyAjaxRequest) {
			$this->oTemplate = new Template(TemplateIdentifier::constructIdentifier('container', $_REQUEST['container_only']), null, true, true);
		} else if($bIsAjaxRequest) {
			$this->oTemplate = new AjaxTemplate($aAjaxSections, true);
		} else {
			$this->oTemplate = self::$CURRENT_PAGE->getTemplate(true);
		}

		FilterModule::getFilters()->handleBeforePageFill(self::$CURRENT_PAGE, $this->oTemplate);
		if(!$bIsLegacyAjaxRequest) {
			$this->fillAttributes();
			$this->fillNavigation();
		}
		$this->fillContent();
		
		$this->renderTemplate();
		
		if($oCache !== null) {
			$oCache->setContents($this->oTemplate->getSentOutput());
		}

		while(ob_get_level() > 0) {
			ob_end_flush();
		}
		FilterModule::getFilters()->handleRequestFinished(array(self::$CURRENT_PAGE, $bIsDynamic, $bIsAjaxRequest, $bIsCached));
	}
	
	protected function getXHTMLOutput() {
		return new XHTMLOutput();
	}
	
	protected function renderTemplate() {
		$this->oTemplate->render();
	}
	
	protected function useFullPageCache() {
		return Settings::getSetting('general', 'use_full_page_cache', true);
	}

	/**
	 * fillNavigation()
	 */
	private function fillNavigation() {
		$aNavigations = $this->oTemplate->listValuesByIdentifier("navigation");
		if(count($aNavigations) > 0) {
			foreach($aNavigations as $sNavigationName) {
				$oNavigation = new Navigation($sNavigationName);
				$this->oTemplate->replaceIdentifier("navigation", $oNavigation->parse($this->oRootNavigationItem), $sNavigationName);
			}
		}
	}
	
	/**
	 * fillContent()
	 */
	protected function fillContent() { 
		$this->oPageType->display($this->oTemplate, false);
	}
			
	/**
	 * fillAttributes()
	 */
	private function fillAttributes() { 
		FilterModule::getFilters()->handleFillPageAttributes(self::$CURRENT_PAGE, $this->oTemplate);
		$this->oTemplate->replaceIdentifier("meta_keywords", self::$CURRENT_PAGE->getConsolidatedKeywords());
		$this->oTemplate->replaceIdentifier("meta_description", self::$CURRENT_PAGE->getDescription());
		$this->oTemplate->replaceIdentifier("description", self::$CURRENT_NAVIGATION_ITEM->getDescription());
		$this->oTemplate->replaceIdentifier("link_text", self::$CURRENT_NAVIGATION_ITEM->getLinkText());
		$this->oTemplate->replaceIdentifier("title", self::$CURRENT_NAVIGATION_ITEM->getTitle());
		$this->oTemplate->replaceIdentifier("page_name", self::$CURRENT_NAVIGATION_ITEM->getName());
		$this->oTemplate->replaceIdentifier("page_title", self::$CURRENT_PAGE->getPageTitle());
		foreach(self::$CURRENT_PAGE->getPageProperties() as $oPageProperty) {
			$this->oTemplate->replaceIdentifier('pageProperty', $oPageProperty->getValue(), $oPageProperty->getName());
		}
		$this->oTemplate->replaceIdentifier("page_id", self::$CURRENT_PAGE->getId());
		$this->oTemplate->replaceIdentifierCallback("page_link", 'FrontendManager', 'replacePageLinkIdentifier');
		if(Settings::getSetting('general', 'multilingual', false) && $this->oTemplate->hasIdentifier('language_chooser')) {
			$this->oTemplate->replaceIdentifier("language_chooser", Navigation::getLanguageChooser(), null, Template::NO_HTML_ESCAPE);
		}
		FilterModule::getFilters()->handleFillAttributesFinished(self::$CURRENT_PAGE, $this->oTemplate);
	}

	/**
	 * replacePageLinkIdentifier()
	 * @param TemplateIdentifier $oTemplateIdentifier The TemplateIdentifier whose name is “page_link”
	 * @return Template the Template containing a link
	 * used in fillAttributes to replace page_link identifiers
	 * - get a page by name
	 * - get a page by id
	 * - get a page by identifier string
	 * - get nearest neighbor page {@link PagePeer::getPageByName()}
	 */
	public static function replacePageLinkIdentifier($oTemplateIdentifier) {
		$oPage = null;
		$iIdentifier = $oTemplateIdentifier->getParameter('identifier');
		if($iIdentifier !== null) {
			$oPage = PagePeer::getPageByIdentifier($iIdentifier);
		} else {
			$iId = $oTemplateIdentifier->getParameter('id');
			if($iId !== null) {
				$oPage = PagePeer::retrieveByPK($iId);
			} else {
				$sName = $oTemplateIdentifier->getParameter('name');
				if($sName !== null) {
					if($oTemplateIdentifier->hasParameter('nearest_neighbour')) {
						$oPage = self::$CURRENT_PAGE->getPageOfName($sName);
					} else {
						$oPage = PagePeer::getPageByName($sName);
					}
				}
			}
		}
		if($oPage === null) {
			return null;
		}
		// hack to be able to display another name than the page_name, thanks to sl
		if($oTemplateIdentifier->hasParameter('href_only')) {
			return LinkUtil::link($oPage->getLink());
		}
		if(self::$CURRENT_PAGE !== null && self::$CURRENT_PAGE->getId() == $oPage->getId()) {
			return TagWriter::quickTag('span', array('class' => "meta_navigation {$oPage->getName()}", 'title' => $oPage->getPageTitle()), $oPage->getLinkText());
		}
		return TagWriter::quickTag('a', array('class' => "meta_navigation {$oPage->getName()}", 'href' => LinkUtil::link($oPage->getLink()), 'title' => $oPage->getPageTitle()), $oPage->getLinkText());
	}
	
	public static function shouldIncludeLanguageInLink() {
		return Settings::getSetting('general', 'multilingual', true);
	}
}

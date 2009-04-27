<?php
class SpecialTemplateIdentifierActions {
  private $oTemplate;
  
  public function __construct($oTemplate) {
    $this->oTemplate = $oTemplate;
  }
  
  public function writeSessionAttribute($oTemplateIdentifier) {
    return Session::getSession()->getAttribute($oTemplateIdentifier->getValue());
  }
  
  public function writeString($oTemplateIdentifier) {
    return StringPeer::getString($oTemplateIdentifier->getValue(), null, null, null, true, $this->oTemplate->iDefaultFlags);
  }
  
  public function writeParameterizedString($oTemplateIdentifier) {
    return StringPeer::getString($oTemplateIdentifier->getValue(), null, null, $oTemplateIdentifier->getParameters(), true, $this->oTemplate->iDefaultFlags);
  }
  
  public function writeFlashValue($oTemplateIdentifier) {
    return Flash::getFlash()->getMessage($oTemplateIdentifier->getValue());
  }
  
  public function normalize($oTemplateIdentifier) {
    return StringUtil::normalize($oTemplateIdentifier->getValue());
  }
  
  public function truncate($oTemplateIdentifier) {
    $iLength=20;
    if($oTemplateIdentifier->hasParameter('length')) {
      $iLength = $oTemplateIdentifier->getParameter('length');
    }
    
    $sPostfix = "…";
    if($oTemplateIdentifier->hasParameter('postfix')) {
      $sPostfix = $oTemplateIdentifier->getParameter('postfix');
    }
    
    $iTolerance = 3;
    if($oTemplateIdentifier->hasParameter('tolerance')) {
      $iTolerance = $oTemplateIdentifier->getParameter('tolerance');
    }
    return StringUtil::truncate($oTemplateIdentifier->getValue(), $iLength, $sPostfix, $iTolerance);
  }
  
  public function quoteString($oTemplateIdentifier) {
    if(!$oTemplateIdentifier->getValue()) {
      return $oTemplateIdentifier->hasParameter('defaultValue') ? $oTemplateIdentifier->getParameter('defaultValue') : null;
    }
    $sLocale = LocaleUtil::getLocaleId();
    $sStyle = 'double';
    if($oTemplateIdentifier->hasParameter('style')) {
      $sStyle = $oTemplateIdentifier->getParameter('style');
    }
    $bAlternate = $oTemplateIdentifier->hasParameter('alternate') && $oTemplateIdentifier->getParameter('alternate') === 'true';
    if(StringUtil::startsWith($sLocale, 'en_')) {
      if($sStyle === 'single') {
        return "‘{$oTemplateIdentifier->getValue()}’";
      }
      return "“{$oTemplateIdentifier->getValue()}”";
    }
    if(StringUtil::startsWith($sLocale, 'fr_') || $sLocale === 'de_CH') {
      if($sStyle === 'single') {
        return "‹{$oTemplateIdentifier->getValue()}›";
      }
      return "«{$oTemplateIdentifier->getValue()}»";
    }
    if(StringUtil::startsWith($sLocale, 'de_')) {
      if($bAlternate) {
        if($sStyle === 'single') {
          return "›{$oTemplateIdentifier->getValue()}‹";
        }
        return "»{$oTemplateIdentifier->getValue()}«";
      }
      if($sStyle === 'single') {
        return "‚{$oTemplateIdentifier->getValue()}‘";
      }
      return "„{$oTemplateIdentifier->getValue()}“";
    }
    if($sStyle === 'single') {
      return "'{$oTemplateIdentifier->getValue()}'";
    }
    return '"'.$oTemplateIdentifier->getValue().'"';
  }
  
  public function writeLink($oTemplateIdentifier) {
    $sDestination = $oTemplateIdentifier->getValue();
    $aParameters = $oTemplateIdentifier->getParameters();
    if($sDestination === "to_self") {
      $bIgnoreRequest = $oTemplateIdentifier->getParameter('ignore_request') === 'true';
      unset($aParameters['ignore_request']);
      return LinkUtil::linkToSelf(null, $aParameters, $bIgnoreRequest);
    }
    if($sDestination === "base_href") {
      return LinkUtil::absoluteLink(MAIN_DIR_FE);
    }
    $sManager = null;
    if($oTemplateIdentifier->hasParameter('manager')) {
      unset($aParameters['manager']);
      $sManager = $oTemplateIdentifier->getParameter('manager');
    }
    $bIsAbsolute = $oTemplateIdentifier->getParameter('is_absolute') === 'true';
    unset($aParameters['is_absolute']);
    if($bIsAbsolute) {
      return LinkUtil::absoluteLink(LinkUtil::link($sDestination, $sManager, $aParameters));
    } else {
      return LinkUtil::link($sDestination, $sManager, $aParameters);
    }
  }
  
  public function includeTemplate($oTemplateIdentifier, &$iFlags) {
    $oTemplatePath = $this->oTemplate->getTemplatePath();
    if($oTemplateIdentifier->hasParameter('fromBase')) {
      $oTemplatePath = null;
    }
    $oTemplate = new Template($oTemplateIdentifier->getValue(), $oTemplatePath, false, false, null, $this->oTemplate->getTemplateName());
    $iFlags = Template::LEAVE_IDENTIFIERS|Template::NO_RECODE;
    if($oTemplateIdentifier->hasParameter('omitIdentifiers')) {
      $iFlags = Template::NO_RECODE;
    }
    return $oTemplate;
  }
  
  public function writeDate($oTemplateIdentifier) {
    return LocaleUtil::localizeDate(null, null, $oTemplateIdentifier->getValue());
  }
  
  public function writeRequestValue($oTemplateIdentifier) {
    if(isset($_REQUEST[$oTemplateIdentifier->getValue()])) {
      return $_REQUEST[$oTemplateIdentifier->getValue()];
    }
    return null;
  }
  
  public function writeSettingValue($oTemplateIdentifier) {
    if(!$oTemplateIdentifier->hasParameter('section')) {
      return null;
    }
    return Settings::getSetting($oTemplateIdentifier->getParameter('section'), $oTemplateIdentifier->getValue(), null);
  }
  
  public function writeManagerPrefix($oTemplateIdentifier) {
    return Manager::getPrefixForManager($oTemplateIdentifier->getValue());
  }
  
  public function writeConstantValue($oTemplateIdentifier) {
    return constant($oTemplateIdentifier->getValue());
  }
  
  public function writeTemplateName($oTemplateIdentifier) {
    return $this->oTemplate->getTemplateName();
  }
  
  public static function getSpecialIdentifierNames() {
    return array_diff(get_class_methods('SpecialTemplateIdentifierActions'), array('getSpecialIdentifierNames', 'getAlwaysLastNames', '__construct'));
  }
  
  public static function getAlwaysLastNames() {
      return array('writeParameterizedString', 'writeFlashValue', 'writeRequestValue', 'truncate', 'quoteString');
  }
}
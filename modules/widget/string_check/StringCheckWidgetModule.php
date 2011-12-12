<?php
/**
 * @package modules.widget
 */
class StringCheckWidgetModule extends PersistentWidgetModule {

	private $aLogMessages = array();
	private static $CHECK_OPTIONS = array("static_strings");

	const LOG_LEVEL_INFO = 0;
	const LOG_LEVEL_NOTICE = 2;
	const LOG_LEVEL_WARNING = 4;
	const LOG_LEVEL_ERROR = 6;
	
	public function __construct($sWidgetId) {
		parent::__construct($sWidgetId);
	}
	
	public function checkOptions() {
		$aResult = array();
		foreach(self::$CHECK_OPTIONS as $sCheckOption) {
			$aResult[$sCheckOption] = StringPeer::getString('check_option.'.$sCheckOption, null, StringUtil::makeReadableName($sCheckOption));
		}
		return $aResult;
	}
	
	public function check($sCheckName, $sLanguageId = null) {
		switch($sCheckName) {
			case "static_strings":
				return $this->checkStaticStrings($sLanguageId);
			break;
		}
	}
	
	private function checkStaticStrings($sCheckLanguageId = null) {
		$aLanguageFiles = array_merge(ResourceFinder::findResourceByExpressions(array(DIRNAME_LANG, "/^.+\.ini$/"), ResourceFinder::SEARCH_SITE_FIRST), ResourceFinder::findResourceByExpressions(array(DIRNAME_MODULES, ResourceFinder::ANY_NAME_OR_TYPE_PATTERN, ResourceFinder::ANY_NAME_OR_TYPE_PATTERN, DIRNAME_LANG, "/^.+\.ini$/"), ResourceFinder::SEARCH_SITE_FIRST));
		
		$aSortedLanguageFiles = array();
		// for each lang dir the available language files are collected
		foreach($aLanguageFiles as $sRelativePath => $sAbsolutePath) {
			$aPathParts = array();
			preg_match("/(([^\/]+\/)+)(.+)\.ini/", $sRelativePath, $aPathParts);
			$sPathPrefix = $aPathParts[1];
			$sLanguageId = $aPathParts[3];
			if(!isset($aSortedLanguageFiles[$sPathPrefix])) {
				$aSortedLanguageFiles[$sPathPrefix] = array();
			}
			$aSortedLanguageFiles[$sPathPrefix][] = $sLanguageId;
		}
		$aAllStringsAvailable = array();
		foreach($aSortedLanguageFiles as $sPathPrefix => $aLanguageIds) {
			$this->log(StringPeer::getString('wns.check.check_static_strings_title', null, null, array('path_prefix' => $sPathPrefix)));
			$aAllStrings = array();
			$aAllLanguageFileContents = array();
			foreach($aLanguageIds as $sLanguageId) {
				$aLanguageFilePaths = ResourceFinder::findAllResources("$sPathPrefix$sLanguageId.ini", ResourceFinder::SEARCH_SITE_FIRST);
				$aAllLanguageFileContents[$sLanguageId] = parse_ini_file($aLanguageFilePaths[0]);
				if(isset($aLanguageFilePaths[1])) {
					$aAllLanguageFileContents[$sLanguageId] = array_merge($aAllLanguageFileContents[$sLanguageId], parse_ini_file($aLanguageFilePaths[1]));
				}
				$aAllStrings = array_merge($aAllStrings, $aAllLanguageFileContents[$sLanguageId]);
			}
			$aAllStrings = array_keys($aAllStrings);
			$bFileHasErrors = false;
			foreach($aAllStrings as $sStringKey) {
				foreach($aAllLanguageFileContents as $sLanguageId => $aLanguageFileContents) {
					if($sCheckLanguageId !== null && $sLanguageId !== $sCheckLanguageId) {
						continue;
					}
					if(!isset($aLanguageFileContents[$sStringKey])) {
						$sText = StringPeer::getString('wns.check.check_message', null, null, array('string_key' => $sStringKey, 'file_name' => $sPathPrefix.$sLanguageId.'.ini'));
						$this->log($sText, $sLanguageId, self::LOG_LEVEL_WARNING);
						$bFileHasErrors = true;
					}
				}
			}
			if($bFileHasErrors === false) {
				$this->log('ok!', null, 5);
			}
		}
		return $this->aLogMessages;
	}
	
	private function log($sText, $sLanguageId=null, $iLogLevel=0) {
		$aLog = array();
		$aLog['level'] = $iLogLevel;
		$aLog['message'] = $sText;
		$aLog['language_id'] = $sLanguageId;
		$this->aLogMessages[] = $aLog;
	}
}

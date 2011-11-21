<?php
/**
 * @package utils
 */
class BuildHelper {
	const CLASSNAME_PATTERN = "/<table[^>]*phpName=\"(\w+)\"/";
	
	public static $PEER_SUFFIX = "Peer";
	public static $MAP_SUFFIX;
	public static $QUERY_SUFFIX = "Query";
	public static $BASE_PREFIX = "Base";
	
	private static function init() {
		self::$MAP_SUFFIX = defined("Propel::VERSION") && version_compare(Propel::VERSION, "1.3", ">=") ? "TableMap" : "MapBuilder";
	}

	public static function preMigrate() {
		self::preBuild();
		self::generateBuildXml();
	}

	public static function postMigrate() {
		self::init();
		self::deleteUnusedFiles();
		Cache::clearAllCaches();
	}

	public static function consolidateMigrations() {
		foreach(ResourceFinder::create()->addPath('data', 'migrations')->addExpression('/\.php$/')->all()->returnObjects()->find() as $oMigration) {
			print "Copying migration {$oMigration->getFileName()} from {$oMigration->getInstancePrefix()}\n";
			copy($oMigration->getFullPath(), MAIN_DIR.'/'.DIRNAME_GENERATED.'/migrations/'.$oMigration->getFileName());
		}
	}
	
	public static function preBuild($bIsDevVersion = false) {
		self::init();
		Cache::clearAllCaches();
		self::compileSchemaXml();
		self::copyPropelAdditions();
	}
	
	public static function postBuild($bIsDevVersion = false) {
		self::init();
		self::moveModel($bIsDevVersion);
		self::deleteUnusedFiles($bIsDevVersion);
		Cache::clearAllCaches();
	}
	
	private static function compileSchemaXml() {
		$sSchemaTemplate = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<!-- {{comment}} -->
<database name="rapila" defaultIdMethod="native">
	{{schema_content}}
</database>
EOT;
		
		$sSchemaOutputPath = MAIN_DIR.'/'.DIRNAME_GENERATED.'/schema.xml';
		$oSchemaTemplate = new Template($sSchemaTemplate, null, true);
		$oSchemaTemplate->replaceIdentifier('comment', "This file is generated by the generate-model.sh script, edit schema.xml in the config dir or the plugins or site's schema.xml file instead", null, Template::NO_HTML_ESCAPE);
		$aSchemaFiles = ResourceFinder::create(array(DIRNAME_CONFIG, "schema.xml"))->noCache()->all()->find();
		foreach($aSchemaFiles as $sSchemaPath) {
			$oSchemaTemplate->replaceIdentifierMultiple('schema_content', file_get_contents($sSchemaPath), null, Template::NO_HTML_ESCAPE);
		}
		file_put_contents($sSchemaOutputPath, $oSchemaTemplate->render());
	}

	public static function generateBuildXml() {
		$aConfiguration = array('propel' => Propel::getConfiguration());
		$oDoc = new DOMDocument();
		$oRoot = $oDoc->createElement('config');
    $oDoc->appendChild($oRoot);
		self::writeConfiguration($oDoc, $aConfiguration, $oRoot);
		$sConfigOutputPath = MAIN_DIR.'/'.DIRNAME_GENERATED.'/buildtime-conf.xml';
		file_put_contents($sConfigOutputPath, $oDoc->saveXML());
	}

	private static function writeConfiguration($oDoc, &$aConfig, $oElement) {
		foreach($aConfig as $sKey => &$mValue) {
			if(is_array($mValue)) {
				$oInner = null;
				if($oElement->tagName === 'datasources') {
					$oInner = $oDoc->createElement('datasource');
					$oInner->setAttribute('id', $sKey);
				} else {
					$oInner = $oDoc->createElement($sKey);
				}
				$oElement->appendChild($oInner);
				self::writeConfiguration($oDoc, $mValue, $oInner);
			} else {
				if(is_bool($mValue)) {
					$mValue = BooleanParser::stringForBoolean($mValue);
				}
				$oAttr = $oDoc->createElement($sKey);
				$oAttr->appendChild($oDoc->createTextNode((string) $mValue));
				$oElement->appendChild($oAttr);
			}
		}
	}
	
	public static function copyPropelAdditions() {
		$sAdditionsOutputPath = MAIN_DIR.'/'.DIRNAME_GENERATED.'/propel_additions';
		if(!file_exists($sAdditionsOutputPath)) {
			mkdir($sAdditionsOutputPath);
		}
		$aBuildClasses = ResourceFinder::findResourceObjectsByExpressions(array(DIRNAME_LIB, 'propel_additions', '/^[\\w_]+\.php$/'));
		foreach($aBuildClasses as $oAddition) {
			$sNewPath = "$sAdditionsOutputPath/".$oAddition->getFileName();
			print "Copying propel addition ".$oAddition->getFileName()." to $sNewPath\n";
			copy($oAddition->getFullPath(), $sNewPath);
		}
	}
		
	/**
	* Moves the model files of modules to that modules directories. Called by the generate-model.sh script
	*/
	private static function moveModel($bIsDevVersion = false) {
		$aSchemaFiles = ResourceFinder::findAllResources(array(DIRNAME_CONFIG, "schema.xml"), ResourceFinder::SEARCH_PLUGINS_ONLY);
		foreach($aSchemaFiles as $sSchemaPath) {
			self::moveModelInto($sSchemaPath);
		}
		$sSchemaFile = ResourceFinder::findResource(array(DIRNAME_CONFIG, "schema.xml"), ResourceFinder::SEARCH_SITE_ONLY);
		if($sSchemaFile) {
			self::moveModelInto($sSchemaFile);
		}
		
		//Be safe
		if($bIsDevVersion) {
			$sSchemaFile = ResourceFinder::findResource(array(DIRNAME_CONFIG, "schema.xml"), ResourceFinder::SEARCH_BASE_ONLY);
			if($sSchemaFile) {
				self::moveModelInto($sSchemaFile);
			}
		} else {
			foreach(ResourceFinder::findAllResourcesByExpressions(array(DIRNAME_GENERATED, DIRNAME_MODEL, '/.+\.php/'), ResourceFinder::SEARCH_MAIN_ONLY) as $sFilePath) {
				unlink($sFilePath);
			}
		}
	}
	
	private static function moveModelInto($sSchemaPath) {
		$sNewModelDir = dirname(dirname($sSchemaPath))."/".DIRNAME_LIB;
		if(!is_dir($sNewModelDir)) {
			mkdir($sNewModelDir);
		}
		$sNewModelDir = $sNewModelDir."/".DIRNAME_MODEL;
		if(!is_dir($sNewModelDir)) {
			mkdir($sNewModelDir);
		}
		
		$sNewModelBaseDir = "$sNewModelDir/om";
		if(!is_dir($sNewModelBaseDir)) {
			mkdir($sNewModelBaseDir);
		}
		
		$sNewModelMapDir = "$sNewModelDir/map";
		if(!is_dir($sNewModelMapDir)) {
			mkdir($sNewModelMapDir);
		}
		
		$sSchema = file_get_contents($sSchemaPath);
		preg_match_all(self::CLASSNAME_PATTERN, $sSchema, $aMatches);
		$aMatches = $aMatches[1];
		foreach($aMatches as $sClassName) {
			$sClassPath = ResourceFinder::findResource(array(DIRNAME_GENERATED, DIRNAME_MODEL, "$sClassName.php"), ResourceFinder::SEARCH_MAIN_ONLY);
			$sPeerClassPath = ResourceFinder::findResource(array(DIRNAME_GENERATED, DIRNAME_MODEL, "${sClassName}".self::$PEER_SUFFIX.".php"), ResourceFinder::SEARCH_MAIN_ONLY);
			$sQueryClassPath = ResourceFinder::findResource(array(DIRNAME_GENERATED, DIRNAME_MODEL, "${sClassName}".self::$QUERY_SUFFIX.".php"), ResourceFinder::SEARCH_MAIN_ONLY);
			
			$sBaseClassPath = ResourceFinder::findResource(array(DIRNAME_GENERATED, DIRNAME_MODEL, 'om', self::$BASE_PREFIX."$sClassName.php"), ResourceFinder::SEARCH_MAIN_ONLY);
			$sBasePeerClassPath = ResourceFinder::findResource(array(DIRNAME_GENERATED, DIRNAME_MODEL, 'om', self::$BASE_PREFIX."${sClassName}".self::$PEER_SUFFIX.".php"), ResourceFinder::SEARCH_MAIN_ONLY);
			$sBaseQueryClassPath = ResourceFinder::findResource(array(DIRNAME_GENERATED, DIRNAME_MODEL, 'om', self::$BASE_PREFIX."${sClassName}".self::$QUERY_SUFFIX.".php"), ResourceFinder::SEARCH_MAIN_ONLY);
			
			//No editable version of the map class exists
			$sMapClassPath = ResourceFinder::findResource(array(DIRNAME_GENERATED, DIRNAME_MODEL, 'map', "${sClassName}".self::$MAP_SUFFIX.".php"), ResourceFinder::SEARCH_MAIN_ONLY);
			
			//Over-writable by the user
			self::moveOverridableFile($sClassPath, $sClassName, $sNewModelDir);
			self::moveOverridableFile($sPeerClassPath, $sClassName, $sNewModelDir, self::$PEER_SUFFIX);
			self::moveOverridableFile($sQueryClassPath, $sClassName, $sNewModelDir, self::$QUERY_SUFFIX);
			
			//Not over-writable by the user (allow to re-generate)
			self::moveNonOverridableFile($sBaseClassPath, $sClassName, $sNewModelBaseDir);
			self::moveNonOverridableFile($sBasePeerClassPath, $sClassName, $sNewModelBaseDir, self::$PEER_SUFFIX);
			self::moveNonOverridableFile($sBaseQueryClassPath, $sClassName, $sNewModelBaseDir, self::$QUERY_SUFFIX);
			
			self::moveNonOverridableFile($sMapClassPath, $sClassName, $sNewModelMapDir, self::$MAP_SUFFIX);
		}
	}
	
	private static function moveOverridableFile($sPath, $sClassName, $sDestination, $sSuffix = "") {
		$sClassName = "$sClassName$sSuffix.php";
		$sClass = "$sDestination/$sClassName";
		if(!file_exists($sClass)) {
			print "Moving user-modifiable $sClassName to $sClass\n";
			rename($sPath, $sClass);
		} else {
			print "[Deleting generated $sClassName because user-modified version exists]\n";
			unlink($sPath);
		}
	}
	
	private static function moveNonOverridableFile($sPath, $sClassName, $sDestination, $sSuffix = "") {
		if($sPath === null || !file_exists($sPath)) {
			return;
		}
		$sClassName = "".($sSuffix == self::$MAP_SUFFIX ? "" : self::$BASE_PREFIX)."$sClassName$sSuffix.php";
		$sClass = "$sDestination/$sClassName";
		if(file_exists($sClass)) {
			unlink($sClass);
		}
		print "Moving generated $sClassName to $sClass\n";
		rename($sPath, $sClass);
	}
	
	/**
	* Delete temp files only used while running generate-model
	*/
	private static function deleteUnusedFiles($bIsDevVersion = false) {
		if(file_exists(MAIN_DIR.'/'.DIRNAME_GENERATED.'/buildtime-conf.xml')) {
			unlink(MAIN_DIR.'/'.DIRNAME_GENERATED.'/buildtime-conf.xml');
		}
		unlink(MAIN_DIR.'/'.DIRNAME_GENERATED.'/schema.xml');
		unlink(MAIN_DIR.'/'.DIRNAME_GENERATED.'/build.properties');
		$aAdditions = ResourceFinder::findResourceObjectsByExpressions(array(DIRNAME_GENERATED, 'propel_additions', '/^[\\w_]+\.php$/'), ResourceFinder::SEARCH_MAIN_ONLY);
		foreach($aAdditions as $oAddition) {
			$oAddition->unlink();
		}
		if($bIsDevVersion) {
			rename(MAIN_DIR.'/'.DIRNAME_GENERATED.'/sqldb.map', BASE_DIR.'/'.DIRNAME_DATA.'/sql/sqldb.map');
			rename(MAIN_DIR.'/'.DIRNAME_GENERATED.'/schema.sql', BASE_DIR.'/'.DIRNAME_DATA.'/sql/schema.sql');
		} else {
			if(file_exists(MAIN_DIR.'/'.DIRNAME_GENERATED.'/sqldb.map')) {
				unlink(MAIN_DIR.'/'.DIRNAME_GENERATED.'/sqldb.map');
			}
		}
	}
}

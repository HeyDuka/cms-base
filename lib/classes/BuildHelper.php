<?php

/**
* @package helpers
*/
class BuildHelper {
  const CLASSNAME_PATTERN = "/phpName=\"(\w+)\"/";
  
  public static function preBuild($bIsDevVersion = false) {
    Cache::clearAllCaches();
    self::compileSchemaXml();
  }
  
  public static function postBuild($bIsDevVersion = false) {
    self::moveModel($bIsDevVersion);
    self::deleteUnusedFiles($bIsDevVersion);
    Cache::clearAllCaches();
  }
  
  private static function compileSchemaXml() {
    $sSchemaTemplate = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<!-- {{comment}} -->
<database name="mini_cms" defaultIdMethod="native">
	{{schema_content}}
</database>
EOT;
    
    $sSchemaOutputPath = MAIN_DIR.'/'.DIRNAME_GENERATED.'/schema.xml';
    $oSchemaTemplate = new Template($sSchemaTemplate, null, true);
    $oSchemaTemplate->replaceIdentifier('comment', "This file is generated by the mini_cms_generate_model.sh script, edit schema.xml in the config dir or the plugins or site's schema.xml file instead", null, Template::NO_HTML_ESCAPE);
    $aSchemaFiles = ResourceFinder::findAllResources(array(DIRNAME_CONFIG, "schema.xml"));
    foreach($aSchemaFiles as $sSchemaPath) {
      $oSchemaTemplate->replaceIdentifierMultiple('schema_content', file_get_contents($sSchemaPath), null, Template::NO_HTML_ESCAPE);
    }
    file_put_contents($sSchemaOutputPath, $oSchemaTemplate->render());
  }
    
  /**
  * Moves the model files of modules to that modules directories. Called by the mini_cms_generate_model.sh script
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
      $sPeerClassPath = ResourceFinder::findResource(array(DIRNAME_GENERATED, DIRNAME_MODEL, "${sClassName}Peer.php"), ResourceFinder::SEARCH_MAIN_ONLY);
      $sBaseClassPath = ResourceFinder::findResource(array(DIRNAME_GENERATED, DIRNAME_MODEL, 'om', "Base$sClassName.php"), ResourceFinder::SEARCH_MAIN_ONLY);
      $sBasePeerClassPath = ResourceFinder::findResource(array(DIRNAME_GENERATED, DIRNAME_MODEL, 'om', "Base${sClassName}Peer.php"), ResourceFinder::SEARCH_MAIN_ONLY);
      $sBuilderClassPath = ResourceFinder::findResource(array(DIRNAME_GENERATED, DIRNAME_MODEL, 'map', "${sClassName}MapBuilder.php"), ResourceFinder::SEARCH_MAIN_ONLY);
      
      //Over-writable by the user
      if(!file_exists("$sNewModelDir/$sClassName.php")) {
        rename($sClassPath, "$sNewModelDir/$sClassName.php");
      } else {
        unlink($sClassPath);
      }
      
      if(!file_exists("$sNewModelDir/${sClassName}Peer.php")) {
        rename($sPeerClassPath, "$sNewModelDir/${sClassName}Peer.php");
      } else {
        unlink($sPeerClassPath);
      }
      
      //Not over-writable by the user (allow to re-generate)
      if(file_exists("$sNewModelBaseDir/Base$sClassName.php")) {
        unlink("$sNewModelBaseDir/Base$sClassName.php");
      }
      rename($sBaseClassPath, "$sNewModelBaseDir/Base$sClassName.php");
      
      if(file_exists("$sNewModelBaseDir/Base${sClassName}Peer.php")) {
        unlink("$sNewModelBaseDir/Base${sClassName}Peer.php");
      }
      rename($sBasePeerClassPath, "$sNewModelBaseDir/Base${sClassName}Peer.php");
      
      if(file_exists("$sNewModelMapDir/${sClassName}MapBuilder.php")) {
        unlink("$sNewModelMapDir/${sClassName}MapBuilder.php");
      }
      rename($sBuilderClassPath, "$sNewModelMapDir/${sClassName}MapBuilder.php");
    }
  }
  
  /**
  * Delete temp files only used while running generate-model
  */
  private static function deleteUnusedFiles($bIsDevVersion = false) {
    unlink(MAIN_DIR.'/'.DIRNAME_GENERATED.'/schema.xml');
    unlink(MAIN_DIR.'/'.DIRNAME_GENERATED.'/schema-transformed.xml');
    unlink(MAIN_DIR.'/'.DIRNAME_GENERATED.'/build.properties');
    if($bIsDevVersion) {
      rename(MAIN_DIR.'/'.DIRNAME_GENERATED.'/sqldb.map', BASE_DIR.'/'.DIRNAME_DATA.'/sqldb.map');
      rename(MAIN_DIR.'/'.DIRNAME_GENERATED.'/schema.sql', BASE_DIR.'/'.DIRNAME_DATA.'/schema.sql');
    } else {
      unlink(MAIN_DIR.'/'.DIRNAME_GENERATED.'/sqldb.map');
      unlink(MAIN_DIR.'/'.DIRNAME_GENERATED.'/schema.sql');
    }
  }
}
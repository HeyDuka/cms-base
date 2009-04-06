<?php

require_once(BASE_DIR."/".DIRNAME_LIB."/".DIRNAME_CLASSES."/Util.php");

class ResourceFinder {
  const SEARCH_MAIN_ONLY = 0;
  const SEARCH_BASE_ONLY = 1;
  const SEARCH_SITE_ONLY = 2;
  const SEARCH_PLUGINS_ONLY = 5;
  const SEARCH_BASE_FIRST = 3;
  const SEARCH_SITE_FIRST = 4;
  const SEARCH_PLUGINS_FIRST = 6;
  
  const ANY_NAME_OR_TYPE_PATTERN = '/^[\\w_]+$/';
  
  private static $PLUGINS = null;
  
  private static function getDefaultFlag($bFindAll) {
    return $bFindAll ? self::SEARCH_BASE_FIRST : self::SEARCH_SITE_FIRST;
  }
  
  private static function processArguments(&$mRelativePath, &$iFlag, $bFindAll) {
    if($iFlag === null) {
      $iFlag = self::getDefaultFlag($bFindAll);
    }
    
    if(is_array($mRelativePath) && array_key_exists('flag', $mRelativePath)) {
      $iFlag = constant("ResourceFinder::".$mRelativePath['flag']);
      unset($mRelativePath['flag']);
    }
    
    if(is_string($mRelativePath)) {
      $mRelativePath = explode('/', $mRelativePath);
    } else if(!is_array($mRelativePath)) {
      throw new Exception("Exception in ResourceFinder: given path is neither array nor string");
    }
    
    if($mRelativePath[0] === DIRNAME_CLASSES || $mRelativePath[0] === DIRNAME_VENDOR || $mRelativePath[0] === DIRNAME_MODEL) {
      array_unshift($mRelativePath, DIRNAME_LIB);
    }
  }
  
  public static function findResource($mRelativePath, $iFlag = null, $bByExpressions = false, $bFindAll = false) {
    $bWaitForAll = $bByExpressions && $bFindAll;
    if($bByExpressions) {
      $bFindAll = true;
    }
    self::processArguments($mRelativePath, $iFlag, $bFindAll);
    $mResult = array();
    foreach(self::buildSearchPathList($iFlag) as $sSearchPath) {
      $sPath = null;
      if($bByExpressions) {
        $sPath = self::findInPathByExpressions($mRelativePath, $sSearchPath);
        if($bWaitForAll) {
          $sPath = array_values($sPath);
        }
      } else {
        $sPath = self::findInPath($mRelativePath, $sSearchPath);
      }
      if($sPath) {
        if($bFindAll) {
          if(!$bByExpressions) {
            $sPath = array($sPath);
          }
          $mResult = array_merge($mResult, $sPath);
        } else {
          return $sPath;
        }
      }
    }
    
    if($bFindAll) {
      return $mResult;
    }
    
    return null;
  }
  
  public static function findAllResources($mRelativePath, $iFlag = null, $bByExpressions = false) {
    return self::findResource($mRelativePath, $iFlag, $bByExpressions, true);
  }
  
  public static function findResourceByExpressions($aExpressions, $iFlag = null, $bFindAll = false) {
    return self::findResource($aExpressions, $iFlag, true, $bFindAll);
  }
  
  public static function findAllResourcesByExpressions($aExpressions, $iFlag = null) {
    return self::findResourceByExpressions($aExpressions, $iFlag, true);
  }
  
  public static function buildSearchPathList($iFlag) {
    switch($iFlag) {
      case self::SEARCH_MAIN_ONLY: return array(MAIN_DIR);
      case self::SEARCH_BASE_ONLY: return array(BASE_DIR);
      case self::SEARCH_SITE_ONLY: return array(SITE_DIR);
      case self::SEARCH_PLUGINS_ONLY: return self::getPluginPaths();
    }
    $aResult = self::getPluginPaths();
    switch($iFlag) {
      case self::SEARCH_BASE_FIRST:
        array_unshift($aResult, BASE_DIR);
        array_push($aResult, SITE_DIR);
      break;
      case self::SEARCH_SITE_FIRST:
        array_unshift($aResult, SITE_DIR);
        array_push($aResult, BASE_DIR);
      break;
      case self::SEARCH_PLUGINS_FIRST:
        array_push($aResult, SITE_DIR);
        array_push($aResult, BASE_DIR);
      break;
    }
    
    return $aResult;
  }
  
  private static function findInPath($aPath, $sPath) {
    foreach($aPath as $sPathElement) {
      if(file_exists("$sPath/$sPathElement")) {
        $sPath .= "/$sPathElement";
      } else {
        return null;
      }
    }
    return $sPath;
  }
  
  private static function findInPathByExpressions($aExpressions, $sPath, $sRelativePath = null) {
    if(count($aExpressions) === 0) {
      return array();
    }
    
    $aResult = array();
    $sPathExpression = $aExpressions[0];
    $sParentName = null;
    if($sRelativePath !== null) {
      $sParentName = explode('/', $sRelativePath);
      $sParentName = $sParentName[count($sParentName)-1];
    }
    
    if($sParentName !== null) {
      $sPathExpression = str_replace('${parent_name}', $sParentName, $sPathExpression);
      $sPathExpression = str_replace('${parent_name_camelized}', Util::camelize($sParentName, true), $sPathExpression);
    }
    
    if(!Util::startsWith($sPathExpression, "/")) {
      $sPathExpression = '/^'.preg_quote($sPathExpression, '/').'$/';
    }
    
    foreach(Util::getFolderContents($sPath) as $sFileName => $sFilePath) {
      if(preg_match($sPathExpression, $sFileName) !== 0) {
        $sNextRelativePath = $sFileName;
        if($sRelativePath !== null) {
          $sNextRelativePath = "$sRelativePath/$sFileName";
        }
        if(count($aExpressions) > 1) {
          $aNewResult = self::findInPathByExpressions(array_slice($aExpressions, 1), $sFilePath, $sNextRelativePath);
          $aResult = array_merge($aResult, $aNewResult);
        } else {
          $aResult[$sNextRelativePath] = $sFilePath;
        }
      }
    }
    return $aResult;
  }
  
  private static function getPluginPaths() {
    if(self::$PLUGINS === null) {
      self::$PLUGINS = array_values(ResourceFinder::findResourceByExpressions(array(DIRNAME_PLUGINS, self::ANY_NAME_OR_TYPE_PATTERN), self::SEARCH_MAIN_ONLY));
    }
    return self::$PLUGINS;
  }
  
  //Helper function for classes that are given a filename, base path and path name
  public static function parsePathArguments($sBaseDirname = null, $mPath = null, $sFileName = null) {
    if($mPath === null) {
      $mPath = array();
    } else if(is_string($mPath)) {
      $mPath = explode("/", $mPath);
    }
    
    if($sBaseDirname !== null) {
      array_unshift($mPath, $sBaseDirname);
    }
    
    if($sFileName !== null) {
      $mPath = array_merge($mPath, explode('/', $sFileName));
    }
    
    return $mPath;
  }
  
  public static function mimeTypeOfFile($sFile) {
    $sMimeType = null;
    if(function_exists("finfo_open")) {
      $rFinfo = finfo_open(FILEINFO_MIME);
      $sMimeType = finfo_file($rFinfo, $sFile);
      finfo_close($rFinfo);
    } else if(function_exists("mime_content_type")) {
      $sMimeType = mime_content_type($sFile);
    } else {
      $aName = explode(".", $sFile);
      if(count($aName) > 0) {
        $oDocumentType = DocumentTypePeer::getDocumentTypeByExtension($aName[count($aName)-1]);
      }
      if($oDocumentType) {
        $sMimeType = $oDocumentType->getMimetype();
      }
    }
    if($sMimeType === null) {
      $sMimeType = 'application/octet-stream';
    }
    return $sMimeType;
  }
  

}
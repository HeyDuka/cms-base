<?php
//general names for dirs
define('DIRNAME_MODULES',        'modules');
define('DIRNAME_DATA',           'data');
define('DIRNAME_CLASSES',        'classes');
define('DIRNAME_BACKEND',        'backend');
define('DIRNAME_VENDOR',         'vendor');
define('DIRNAME_MODEL',          'model');
define('DIRNAME_TEST',           'test');
define('DIRNAME_LIB',            'lib');
define('DIRNAME_CONFIG',         'config');
define('DIRNAME_IMAGES',         'images');
define('DIRNAME_CACHES',         'caches');
define('DIRNAME_FULL_PAGE',      'full_page');
define('DIRNAME_LANG',           'lang');
define('DIRNAME_WEB',            'web');
define('DIRNAME_TEMPLATES',      'templates');
define('DIRNAME_NAVIGATION',     'navigation');
define('DIRNAME_SITE',           'site');
define('DIRNAME_BASE',           'base');
define('DIRNAME_PLUGINS',        'plugins');
define('DIRNAME_GENERATED',      'generated');

// mai dir constants
define('MAIN_DIR' ,              dirname(dirname(dirname(__FILE__))));
define('SITE_DIR',               MAIN_DIR.'/'.DIRNAME_SITE);
define('BASE_DIR',               MAIN_DIR.'/'.DIRNAME_BASE);
define('PLUGINS_DIR',            MAIN_DIR.'/'.DIRNAME_PLUGINS);

// autoload of classes
require_once(BASE_DIR."/".DIRNAME_LIB."/".DIRNAME_CLASSES."/Autoloader.php");
Autoloader::loadIncludeCache();
spl_autoload_register(array('Autoloader', 'autoload'));

// include path for all classes
$aVendorDirs = ResourceFinder::findAllResources(DIRNAME_VENDOR);
$aLibDirs = ResourceFinder::findAllResources(DIRNAME_LIB);

set_include_path(MAIN_DIR.'/'.DIRNAME_GENERATED.PATH_SEPARATOR.implode(PATH_SEPARATOR, $aLibDirs).PATH_SEPARATOR.implode(PATH_SEPARATOR, $aVendorDirs).PATH_SEPARATOR.get_include_path());

mb_internal_encoding(Settings::getSetting('encoding', 'browser', 'utf-8'));
mb_regex_encoding(mb_internal_encoding());

if(function_exists("date_default_timezone_set")) {
  date_default_timezone_set(Settings::getSetting('general', 'timezone', 'Europe/Zurich'));
}

require_once("propel/Propel.php");
Propel::setConfiguration(array('propel' => Settings::getSetting('general', 'db_config', null)));
Propel::initialize();

//Set MySQL connection charset
$sAdapter = Settings::getSetting('general', 'db_config', null);
$sAdapter = $sAdapter['datasources'][$sAdapter['datasources']['default']]['adapter'];
if(Util::startsWith($sAdapter, 'mysql')) {
  $con = Propel::getConnection();
  $con->executeQuery('SET character_set_client="'.Util::convertEncodingNameToSql(Settings::getSetting("encoding", "db", "utf-8")).'"');
  $con->executeQuery('SET character_set_connection="'.Util::convertEncodingNameToSql(Settings::getSetting("encoding", "db", "utf-8")).'"');
  $con->executeQuery('SET character_set_results="'.Util::convertEncodingNameToSql(Settings::getSetting("encoding", "db", "utf-8")).'"');
}

// frontend dir constants
define('MAIN_DIR_FE',        isset($_SERVER['SHELL']) ? Settings::getSetting('domain_holder', 'root_url', '/') : preg_replace("/^(.*)index\.php$/", '$1', $_SERVER['PHP_SELF']));

define('BASE_DIR_FE',        MAIN_DIR_FE.DIRNAME_BASE);
define('SITE_DIR_FE',        MAIN_DIR_FE.DIRNAME_SITE);
define('INT_WEB_DIR_FE',     BASE_DIR_FE."/".DIRNAME_WEB);
define('EXT_WEB_DIR_FE',     SITE_DIR_FE."/".DIRNAME_WEB);
define('INT_CSS_DIR_FE',     INT_WEB_DIR_FE.'/css');
define('EXT_CSS_DIR_FE',     EXT_WEB_DIR_FE.'/css');
define('INT_JS_DIR_FE',      INT_WEB_DIR_FE.'/js');
define('EXT_JS_DIR_FE',      EXT_WEB_DIR_FE.'/js');
define('INT_MEDIA_DIR_FE',   INT_WEB_DIR_FE.'/media');
define('EXT_MEDIA_DIR_FE',   EXT_WEB_DIR_FE.'/media');
define('INT_IMAGES_DIR_FE',  INT_WEB_DIR_FE.'/images'); 
define('EXT_IMAGES_DIR_FE',  EXT_WEB_DIR_FE.'/images'); 
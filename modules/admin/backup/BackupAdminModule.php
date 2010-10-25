<?php
/**
 * @package modules.admin
 */
class BackupAdminModule extends AdminModule {
		
	private $oBackupOptionsWidget;
	private $sAction;
	
	public function __construct() {
		$this->oBackupOptionsWidget = new ListWidgetModule();
		$this->oBackupOptionsWidget->setDelegate($this);
	}
	
	public function setAction($sAction) {
	  $this->sAction = $sAction;
	}
	
	public function sidebarContent() {
		return $this->oBackupOptionsWidget->doWidget();
	}
	
	public function getColumnIdentifiers() {
		return array('action', 'title');
	}
	
	public function getMetadataForColumn($sColumnIdentifier) {
		$aResult = array();
		switch($sColumnIdentifier) {
			case 'action':
				$aResult['display_type'] = ListWidgetModule::DISPLAY_TYPE_DATA;
				break;
			case 'title':
				$aResult['display_heading'] = false;
				break;
		}
		return $aResult;
	}
	
	public function getListContents($iRowStart = 0, $iRowCount = null) {
		$aResult = array();
		$aBackupOptions = array('load_from_local' => 'Load from local file', 'backup_to_local' => 'Backup to local file');
		foreach($aBackupOptions as $sAction => $sActionName) {
			$aResult[] = array('action' => $sAction, 'title' => $sActionName);
		}
		if($iRowCount === null) {
			$iRowCount = count($aResult);
		}
		return array_splice($aResult, $iRowStart, $iRowCount);
	}

	public function mainContent() {
		return $this->constructTemplate('info');
	}
	
	public function usedWidgets() {
		return array();
	}
}

<?php
/**
 * @package modules.frontend
 */
 
class EmptyFrontendModule extends FrontendModule {

	public function __construct($oLanguageObject, $aRequestPath = null) {
		parent::__construct($oLanguageObject, $aRequestPath);
	}

	public function renderFrontend() {
		return "";
	}

	public function renderBackend() {
		return $this->constructTemplate('backend');
	}

	public function getSaveData() {
		return "";
	}
}

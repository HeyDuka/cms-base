<?php

/**
* @package widget
*/

class TagAreaWidgetModule extends PersistentWidgetModule {
	
	private $sModelName;
	private $mTaggedItemId;
	
	public function listTags() {
		$oQuery = TagQuery::create();
		if($this->sModelName !== null) {
			$oQuery->filterByTaggedModel($this->sModelName);
			if($this->mTaggedItemId !== null) {
				$oQuery->filterByTaggedItem($this->mTaggedItemId);
			}
		}
		return $oQuery->find()->toArray();
	}
	
	public function deleteTag($sTagName) {
		if($this->sModelName === null || $this->mTaggedItemId === null) {
			throw new Exception('Can only delete specific tags');
		}
		$oTag = TagQuery::create()->filterByTaggedModel($this->sModelName)->filterByTaggedItem($this->mTaggedItemId)->filterByName($sTagName)->findOne();
		if(!$oTag) {
			return true;
		}
		$oTag->delete();
		return true;
	}
	
	public function setModelName($sModelName) {
		$this->sModelName = $sModelName;
	}

	public function getModelName() {
		return $this->sModelName;
	}
	
	public function setTaggedItemId($mTaggedItemId) {
		$this->mTaggedItemId = $mTaggedItemId;
	}

	public function getTaggedItemId() {
		return $this->mTaggedItemId;
	}
}
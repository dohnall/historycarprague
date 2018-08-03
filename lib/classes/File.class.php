<?php

class File {

	public $filename = "";

	public function __construct($filename) {
		$this->filename = $filename;
	}

	public function getExt() {
		$return = "";
		$parts = explode(".", $this->filename);
		$return = array_pop($parts);
		return $return;
	}

	public function getSize($unit = 0) {
		return Common::getPCSize(filesize(LOCALFILES.$this->filename), $unit);
	}

	public function getUploadTime() {
		return date("Y-m-d H:i:s", filectime(LOCALFILES.$this->filename));
	}

}

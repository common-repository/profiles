<?php

// WP-Data framework

class WPD_Error {

	public $error;
	public $date;
	public $trace;
	public $level;
	
	function __construct($error_string, $level=1) {
		$this->error = $error_string;
		$this->level = $level;
		$this->date = date('U');
		$this->trace = debug_backtrace();	
	}
	
	public function __toString() {
		return "Level: $this->level. ".$this->error;
	}
}
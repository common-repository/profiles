<?php

// Profiles Options

class profilesError {

	public $error;
	public $date;
	public $trace;
	public $level;
	
	function __construct($error_string, $level) {
		$this->error = $error_string;
		$this->level = $level;
		$this->date = date('U');
		$this->trace = debug_backtrace();	
	}
}

class profilesCategory {
	public $name;
	public $active=false;
	public $data_template="";
	
	function __construct($Name) {
		$this->name = $Name;
	}
}

class profilesOptions {
	
	protected $options = array(
		"load_jquery",
		"use_permalinks",
		"locations",
		"setup_complete",
		"image_width",
		"image_height",
		"watermark",
		"watermark_text",
		"watermark_color_fore",
		"watermakr_color_shadow",
		"watermark_font",
		"watermark_size",
		"database_revision",
		"categories"
	);
	
	private $option_name = 'profiles_options';
	
	protected $data;
	
	private static $instance;
						
	private function __construct () {
		date_default_timezone_set("America/Los_Angeles");
		$this->data = get_option($this->option_name);
	}
	
	public static function getInstance() {
		if(!isset($instance)) {
			$c = __CLASS__;
			$instance = new $c;
		}
		return $instance;
	}
	
	function __get($name) {
		if(in_array($name, $this->options)) {
			return $this->data[$name];
		} else {
			$this->log_error("Tried to retrieve unknown key $name from options.");
			return null;
		}
	}
	
	function __set($name, $value) {
		// Check and sort categories
		if($name=="categories") {
			$this->data[$name] = $value;
			$this->sortCats();
			$this->save();
		}
		if(in_array($name, $this->options)) {
			$this->data[$name] = $value;
			$this->save();
		} else {
			$this->log_error("Tried to set unknown key $name:$value in options.");	
		}	
	}
	
	function save() {
		if(get_option($this->option_name)) {
			update_option($this->option_name,$this->data);
		} else {
			add_option($this->option_name, $this->data);
		}
	}
	
	function log_error($message,$level=1) {
		$this->data['errors'][] = new profilesError($message, $level);
		$this->save();
	}
	
	function log_message($message) {
		$this->log_error($message, 3);
	}
	
	function errors() {
		return $this->data['errors'];	
	}
	
	function clear_errors() {
		$this->data['errors'] = array();
		$this->save();
	}
	
	function hasErrors() {
		if(count($this->data['errors']) > 0)	return true;
		return false;
	}
	
	private function sortCats() {
		function sortPC($a,$b){
			if($a->name == $b->name) return 0;
			return strcmp($a->name,$b->name);
		}
		$cat = $this->data['categories'];
		uasort($cat,'sortPC');
		$this->data['categories'] = $cat;	
	}
}


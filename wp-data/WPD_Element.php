<?php

class WPD_Element {
	
	public $name;
	public $type;
	public $attrs;
	
	function __construct($name,$type="string") {
			$this->name = $name;
			$this->type = $type;
	}
	
}
<?php

// WP-Data Framework

class WPD_Definition {
	
	/**
	 * The name of this dataobject type, e.g. Boats
	 * @var string
	 */
	protected $_name;
	
	/**
	 * The author of this dataobject type
	 * @var string
	 */
	protected $_author;
	
	/**
	 * The url for more information about this dataobject type
	 * @var string
	 */
	protected $_url;
	
	/**
	 * The version of this dataobject type. Must be of format x.x.x
	 * @var string
	 */
	protected $_version;
	
	/**
	 * The signature url, for verifying this dataobject type
	 * @var string
	 */
	protected $_signature_url;
	
	/**
	 * The signature for verifying this dataobject type.
	 * @var string
	 */
	protected $_signature;
	
	function __construct($name,$author,$url,$version,$signature='',$signature_url='') {
		$this->_name = $name;
		$this->_author = $author;
		$this->_url = $url;
		$this->_version = $version;
		$this->_signature = $signature;
		$this->_signature_url = $signature_url;
	}
	
	function __get($name) {
		if($name == "name") return $this->_name;
		if($name == "version") return $this->_version;
		if($name == "author") return $this->_author;
		if($name == "url") return $this->_url;
	}
	
	/**
	 * Returns the definition values in a data array
	 * @return array	The definition values
	 */
	function get_array() {
		$data;
		$data['name'] = $this->_name;
		$data['author'] = $this->_author;
		$data['url'] = $this->_url;
		$data['version'] = $this->_version;
		$data['signature'] = $this->_signature;
		$data['signature_url'] = $this->_signature_url;
		return $data;
	}
}
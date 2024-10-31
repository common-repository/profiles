<?php

// WP-Data framework

class WPD_Template {
	
	/**
	 * The version WPD_Template
	 */
	private $_version = "0.1 Alpha";
	
	/**
	 * Possible attribute values
	 * @var array<string>
	 */
	protected $_attr_types = array(
								'required',
								'min',
								'max',
									);
	
	/**
	 * The definition file for this data object
	 * @var WPD_Definition
	 */
	protected $_definition;
	
	/**
	 * The data pieces
	 * @var array<WPD_Element>
	 */
	protected $_data;
	
	function __construct($args = "none", $xml = false) {
		if ($xml) {
			$this->fromXML($args);
		}
		if ($args == "none") {
			//return new WPD_Error("Cannot define a WPD_Databject without a a definition $none.");
			$this->_definition = new WPD_Definition('Test','Christopher O\'Connell','http://compu.terlicio.us/','0.1.0');
			$this->_data[] = new WPD_Element('name');
			$do = new WPD_Element('age','int');
			$do->attrs['required'] = 'true';
			$do->attrs['min'] = '0';
			$do->attrs['max'] = '150';
			$this->_data[] = $do;
			$do = new WPD_Element('email','email');
			$do->attrs['required'] = 'true';
			$this->_data[] = $do;
		}
	}
	
	function fromXML($document) {
		$xml = new DOMDocument();
		$xml->loadXML($document);
		
		// Load the definitions object
		$defs = $xml->getElementsByTagName('definition');
		
		$this->_definition = new WPD_Definition(
												$defs->item(0)->getElementsByTagName('name')->item(0)->nodeValue,
												$defs->item(0)->getElementsByTagName('author')->item(0)->nodeValue,
												$defs->item(0)->getElementsByTagName('url')->item(0)->nodeValue,
												$defs->item(0)->getElementsByTagName('version')->item(0)->nodeValue,
												$defs->item(0)->getElementsByTagName('signature')->item(0)->nodeValue,
												$defs->item(0)->getElementsByTagName('signature_url')->item(0)->nodeValue);
												
		$data = $xml->getElementsByTagName('data');
		$data_elements = $data->item(0)->getElementsByTagName('data-element');
		
		foreach($data_elements as $element) {
			$dl = new WPD_Element($element->nodeValue,$element->getAttribute('type'));
			foreach($this->_attr_types as $type) {
				if($element->hasAttribute($type)) {
					$dl->attrs[$type] = $element->getAttribute($type);
				}
			}
			$this->_data[] = $dl;	
		}
	}
	
	/**
	 * Turns "this" into an xml document.
	 * 
	 * @return string	The xml document
	 */
	function toXML() {
		$xml = new DOMDocument("1.0");
		$xml->formatOutput = true;
		
		// Create the root node
		$root = $xml->createElement("profile");
		$xml->appendChild($root);
		
		// Create the definitions
		$defs = $xml->createElement("definition");;
		foreach ($this->_definition->get_array() as $name => $value) {
			$dn = $xml->createElement($name);
			$text = $xml->createTextNode($value);
			$dn->appendChild($text);
			$defs->appendChild($dn);		
		}
		$root->appendChild($defs);
		
		$data = $xml->createElement("data");
		foreach ($this->_data as $do) {
			$data_listing = $xml->createElement("data-element");
			$data_listing_type = $xml->createAttribute("type");
			$data_listing_type_value = $xml->createTextNode($do->type);
			$data_listing_type->appendChild($data_listing_type_value);
			$data_listing->appendChild($data_listing_type);
			if (is_array($do->attrs)) {
				foreach ($do->attrs as $attr_name => $attr_value) {
					$data_listing_attr = $xml->createAttribute($attr_name);
					$data_listing_attr_value = $xml->createTextNode($attr_value);
					$data_listing_attr->appendChild($data_listing_attr_value);
					$data_listing->appendChild($data_listing_attr);
				}
			}
			$data_listing->appendChild($xml->createTextNode($do->name));
			$data->appendChild($data_listing);
		}

		$root->appendChild($data);
		
		return $xml->saveXML();
	}
	
	/**
	 * Returns a PHP class based on this Template
	 * @return unknown_type
	 */
	function toPHP() {

		$s = "    ";
		$class_name = ucfirst(strtolower($this->_definition->name));
		
		$types;
		foreach($this->_data as $do) {
			$types[$do->type] = true;
		}

		$class .= "// $class_name Class\n";
		$class .= "// By ".$this->_definition->author.". For more info ".$this->_definition->url."\n";
		$class .= "// Version ".$this->_definition->version."\n";
		$class .= "// Built by WPD_Template ".$this->_version."\n";
		$class .= "// Built ".date('l jS \of F Y h:i:s A')."\n";
		$class .= "// WPD_Template and WP-Data by Christopher O'Connell\n";
		$class .= "// http://compu.terlicio.us/\n\n";
		$class .= "// WARNING: Manually editing this file will cause your application to break if the profile is updated.\n";
		$class .= "// Please see ".$class_name."Functions.php to add custom functions.\n\n";
		$class .= "class $class_name extends WPD_Dataobject {\n";
		$class .= "$s \n$s //Constructor\n";
		$class .= "$s function __construct() {\n";
		$class .= "$s $s parent::__construct();\n";
	foreach ($this->_data as $do) {
		$class .= "$s $s \$this->known_vars['$do->name'] = '$do->type';\n";
	}
		$class .= "$s $s \$this->imports('".$class_name."Functions');\n";
		$class .= "$s }\n";
		$class .= "}\n";
		
		if(!eval("return true;\n".$class)) {
			return new WPD_Error("There was a parse error in creating the $class_name class.");
		}
		
		$functions .= "// ".$class_name."Functions Class\n";
		$functions .= "// Provides plug-in functions for the $class_name Class.\n";
		$functions .= "// Feel free to edit this file.\n\n";
		$functions .= "// Built by WPD_Template ".$this->_version."\n";
		$functions .= "// Built ".date('l jS \of F Y h:i:s A')."\n\n";
		$functions .= "class ".$class_name."Functions {\n";
		$functions .= "\n$s // User Defined Functions\n\n";
		$functions .= WPD_FunctionTemplates::helloWorldTemplate()."\n";
		$functions .= "\n$s // Validator Stubs\n$s // These validators need to be written based on the profile definition\n\n";
		$functions .= "$s // TODO: Validator Stubs\n$s \n";
		$functions .= "\n$s // Default validators\n$s // These validators are based on those included with WP-Data. If you change them, set custom to true or they will be overwritten.\n\n";
	foreach ($types as $type => $value) {
		$t = $type."Template";
		$template = WPD_FunctionTemplates::$t();
		if(is_string($template)) {
			$functions .= "\n".$template."\n";
		}
	}
		$functions .= "}\n";
	
		if(!eval("return true;\n".$functions)) {
			/*$func_lines = split("\n",$functions);
			$func;
			$i = 2;
			foreach($func_lines as $line) {
				$func .= ( ($i<10)? " $i" : $i).": $line\n";
				$i++;
			}
			echo "<pre>".$func."</pre>";*/
			return new WPD_Error("There was a parse error in creating the ".$class_name."Functions class.");
		}
	
	
		return array( "class" => $class, "functions" => $functions, 'object' => $class );
	}
	
	function __get($name) {
		if ($name == 'name') return $this->_definition->name;
	}
}
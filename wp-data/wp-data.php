<?php
// The wp-data framework, for structured data in php.

// includes

require_once(dirname(__FILE__)."/WPD_Template.php");
require_once(dirname(__FILE__)."/WPD_Element.php");
require_once(dirname(__FILE__)."/WPD_Error.php");
require_once(dirname(__FILE__)."/WPD_Definition.php");
require_once(dirname(__FILE__)."/WPD_FunctionTemplates.php");
require_once(dirname(__FILE__)."/WPD_Dataobject.php");

// Data
class WPD_Data {
	
	static protected $_profiles;
	
	/**
	 * PHP 4 style constructor for compatibility
	 * @return unknown_type
	 */
	function WPD_Data() {
		$this->__construct();
	}
	
	function __construct($profile,$directory="") {
		return load($profile, $directory);
	}
	
	public static function load($profile,$directory="") {
		if($directory == "") {
			$directory = dirname(__FILE__);
		}
		
		// Check for multi load
		if (is_array($profile)) {
			foreach ($profile as $name) {
				$lp = self::loadObject($name,$directory."/objects/");
				if($lp instanceof WPD_Error) {
					return $lp;
				}
			}
		} else {
			$lp = self::loadObject($profile,$directory."/objects/");
			if($lp instanceof WPD_Error) {
				return $lp;
			}
		}
	}
	
	protected static function loadObject($profile, $directory) {
		$profile = ucfirst(strtolower($profile));
		$filename = $directory.$profile.".php";
		if(file_exists($filename)) {
			require_once($filename);
		} else {
			return new WPD_Error("Unable to find profile $profile in $directory.");
		}
		self::$_profiles[] = $profile;
		$funcs_file = $directory.$profile."Functions.php";
		if(file_exists($funcs_file)) {
			require_once($funcs_file);
		}
	}
	
	/**
	 * Takes a WPD_Template and creates an xml template file in the templates directory
	 * @param $template	The WPD_Template to create from.
	 * @return string	The name of the new file, WPD_Error on error.
	 */
	public static function createProfile($template,$directory="",$name="") {
		if(! ($template instanceof WPD_Template)) {
			return new WPD_Error("Argument supplied to createProfile was not an instance of WPD_Template");
		}
		if($directory=="") $directory = dirname(__FILE__)."/templates/";
		if($name=="") $name = $template->name;
		$name = ucfirst(strtolower($name));
		
		$filename = $directory.$name.".xml";
		
		$content = $template->toXML();
		// TODO: Write Signatures
		if($content instanceof WPD_Error) {
			return $content;
		}
		$fhandle = fopen($filename,'w');
		$result = fwrite($fhandle,$content);
		fclose($fhandle);
		return $name;
	}
	
	/**
	 * Takes a WPD_Template or the name of an xml template and create data-objects based on them.
	 * @param $template
	 * @param $directory
	 * @return unknown_type
	 */
	public static function createObjects($template,$force_rewrite=false,$object_directory="",$template_directory="") {
		
		// If we got a name instead of an object, find and load the xml profile file.
		if(! ($template instanceof WPD_Template) ) {
			echo "Reading in file\n";
			if(!is_string($template)) return new WPD_Error("createObjects : Don't know what to do with $template");
			if($template_directory=="") $template_directory = dirname(__FILE__)."/templates/";
			// Make sure that they didn't leave the .xml on the $template name.
			str_replace(".xml","",$template);
			$template = ucfirst(strtolower($template));
			$filename = $template_directory.$template.".xml";
			echo $filename;
			if(!file_exists($filename)) return new WPD_Error("createObjects : Couldn't find file $filenam");
			$fhandle = fopen($filename,'r');
			$content = fread($fhandle,filesize($filename));
			fclose($fhandle);
			$template = new WPD_Template($content,true);
		}
		
		echo "Getting php\n";
		// Get the php
		$php = $template->toPHP();
		
		// Take care of the output directory
		if($object_directory=="") $object_directory = dirname(__FILE__)."/objects/";
		
		// Filenames
		$tname = ucfirst(strtolower($template->name));
		$fname = $tname."Functions";
		
		$obj_filename = $object_directory.$tname.".php";
		$func_filename = $object_directory.$fname.".php";
		
		echo "Writing $obj_filename and $func_filename.<br />";
		
		// Write the class files
		$fhandle = fopen($obj_filename,'w');
		$result = fwrite($fhandle, str_replace("\r","","<?php\n".$php['class']) );
		echo $result."<br />";
		fclose($fhandle);
		
		// Check for functions and write
		if(!file_exists($func_filename) || $force_rewrite) {
			echo "Writing $func_filename"."<br />";
			$ffhandle = fopen($func_filename,'w');
			$result = fwrite($ffhandle, str_replace("\r","","<?php\n".$php['functions']) );
			echo $result."<br />";
			fclose($ffhandle);
		}
		
		return $tname;
	}
}
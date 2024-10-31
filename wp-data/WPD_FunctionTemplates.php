<?php

// Contains templates for default functions

class WPD_FunctionTemplates {
	
	static function stringTemplate() {
		return <<<STRING_TEMPLATE
     /**
      * Validates that the input value is a string. Also escapes any code.
      * @param \$string The input string to validate
      * @return string The string if true, a WPD_Error if validation failed.
      * @custom false
      */
     function validateString(\$string) {
          // TODO: Better string escaping
          if ( !is_string(\$string) ) return new WPD_Error("Validation Failed. validateString found not a string.",2);
          return \$string;
     }
STRING_TEMPLATE;
	}
	
	static function intTemplate() {
			return <<<INT_TEMPLATE
     /**
      * Validates that the input value is an int. Casts other values to an int.
      * @param \$int The input int to validate
      * @return int The int, a WPD_Error if validation failed. In most instances, however, an int will be returned
      * @custom false
      */
     function validateInt(\$int) {
          // TODO: Make this a LOT better
          if( !is_int(\$int) ) return new WPD_Error("Validation Failed. validateInt found not an int.",2);
          return \$int;
     }
INT_TEMPLATE;
	}
	
	static function emailTemplate() {
			return <<<EMAIL_TEMPLATE
     /**
      * Validates that the input value is an email.
      * @param \$email The input email to validate.
      * @return string The Email, a WPD_Error if validation failed.
      * @custom false
      */
     function validateEmail(\$email) {
          // TODO: Make this a LOT better
          return \$email;
     }
EMAIL_TEMPLATE;
	}
	
	static function helloWorldTemplate() {
			return <<<HELLO_WORLD_TEMPLATE
     /**
      * Hello world is an example plugin function. Create similar function.
      * To call this function, \$object->helloWorld() from within your php code
      * @return string "Hello World"
      * @custom false
      */
     function helloWorld() {
          return "Hello World";
     }
HELLO_WORLD_TEMPLATE;
	}
}
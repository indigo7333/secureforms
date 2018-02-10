<?php
namespace indigo7;
class Form {

    var $name;
    private $salt;
    private $vars;
    public $fields;
    
    

    function sanitize($field) {
        $this->value = htmlentities(trim(strip_tags(stripslashes($this->field($field)))), ENT_NOQUOTES, "UTF-8");
        return $this; 
    }
    function sanitize_html($field) {
        $this->value = strip_tags(htmlentities(trim(stripslashes($this->field($field))), ENT_NOQUOTES, "UTF-8"));
        return $this;
    }
    function field($name) {
        return isset($this->fields[$name]) ? $this->fields[$name] : $this->get_post($name);
    }                            

    private function Token() {
		$token = hash("sha256", openssl_random_pseudo_bytes(intval(microtime()).HASH_SPECIAL_SALT.$this->salt));   
		$_SESSION[$this->name.'_token'] = $token; 

    	return $token;

    }
    
    function Token_field() {
        return "<input type='hidden' value='".$this->Token()."' name='".$this->Token_field_name()."' />";
    }
    
    function Submit_field() {
        return "<input type='hidden' value='1' name='submit' />";
    }
    
    private function Token_field_name() {
       
        return hash("ripemd160", HASH_SPECIAL_SALT.strtotime(date("Y m d h")).md5(HASH_SPECIAL_SALT.$this->salt, true));
    }
  
    
    function __construct($form) {
        $this->name = $form;
        $this->salt = crc32($form);
    }

    function verify_token() {
        $token_field_name = $this->Token_field_name();
        $token = $this->get_post($token_field_name);
        
        if(!isset($_SESSION[$this->name.'_token'])) { 
		        throw new FM_exception($this, 1, 'Token Form Verification Failed');
        }
	
	      if(!isset($token)) {
		        throw new FM_exception($this, 2, 'Token Form Verification Failed');
        }
	

	      if ($_SESSION[$this->name.'_token'] !== $token) {
		        throw new FM_exception($this, 3, 'Token Form Verification Failed');
        }
	
	      return true;    
    }
    
   
    function get_post($name) {    
         $this->vars[] = $name;
         $this->fields[$name] = isset($_POST[$name]) ? $_POST[$name] : null;  
         return $this->fields[$name];   
    }   
    function is_sent() {
        if($this->get_post("submit")==1) return true;
        return false;
    }


    //validation functions
    function validate($field) {
        $this->vars[]=$field;    
        $this->get_post($field);
        return ESVE::ValidatePostField($field);
    }
    function verify_unknown_post_vars() {
        foreach ($_POST as $key=>$item) {
            if (!in_array($key, $this->vars)) {
                //throw new FM_exception($this, 4, json_encode($_POST)."Form Verification Failed.".json_encode($this->vars));
                throw new FM_exception($this, 4, "Form Verification Failed.");
            }
        } 
    }
}

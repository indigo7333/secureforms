<?php
namespace indigo7;
class ESVE {
    
    var $field_name;
    var $value;
    var $field_value;
    private $required = false;
    
    static function ValidatePostField($field) {
        return new static($field, 'post');        
    }
    function __construct($field,$type) {
        $this->field_name = $field;
        if($type == "post") {
            $this->field_value = $this->get_post($field);    
            $this->value = $this->field_value;
        }       
    }
    static function Validate($field) {
        return new static($field,'local');    
    }
    function SetValue($value) {
        $this->value = $value;
        $this->field_value = $value;
        return $this;
    }
    function get_post($name) {
        return isset($_POST[$name]) ? $_POST[$name] : null;
    }
    
    function isEmail() {
        if($this->no_validation_required()) { return $this; }
        if (!filter_var($this->value, FILTER_VALIDATE_EMAIL)) {
            throw new ESVE_exception($this, 1, 'The email provided is not valid.');
        }
        return $this;    
    }
    function isRequired() {
        $this->required = true;
        if(!$this->field_value) {
            throw new ESVE_EXCEPTION($this, 2, "The {$this->field_name} field is required.");
        }
        return $this;
    }
    function hasLengthBetween($minlength,$maxlength) {
        if($this->no_validation_required()) { return $this; }
        if(mb_strlen($this->field_value,'UTF-8')<$minlength || mb_strlen($this->field_value,'UTF-8')>$maxlength) {
            throw new ESVE_EXCEPTION($this, 3, "The {$this->field_name} field must be between $minlength and $maxlength characters.", ['minlength' => $minlength, 'maxlength' => $maxlength]);
        }
        return $this;                                             
    }
    function hasLettersOnly() {
        if($this->no_validation_required()) { return $this; }
        if (!preg_match('/^[\p{L} ]+$/u', $this->field_value)){
              throw new ESVE_EXCEPTION($this, 4, "The {$this->field_name} field must contain only letters.");    
        }
        return $this;
    }
    function hasLettersNumbersOnly() {
        if($this->no_validation_required()) { return $this; }
        if (!preg_match('/^[\p{L}\d ]+$/u', $this->field_value)){
              throw new ESVE_EXCEPTION($this, 6, "The {$this->field_name} field must contain only letters and numbers.");    
        }
        return $this;  
    }
    function isPhone() {
        if($this->no_validation_required()) { return $this; }  
        if (!preg_match('/^[\p{L}\d +-]+$/u', $this->field_value)){
              throw new ESVE_EXCEPTION($this, 5, "The {$this->field_name} field must be a valid phone number.");    
        }
        return $this;   
    }
    function inList($list) {
        if($this->no_validation_required()) { return $this; }  
        if (!in_array($this->field_value, $list)){
              throw new ESVE_EXCEPTION($this, 7, "{$this->field_name} field is not valid", ["list" => $list]);    
        }
        return $this;
    }
    function inKeylist($list) {
        if($this->no_validation_required()) { return $this; }  
        if(!array_key_exists($this->field_value,$list)) {
            throw new ESVE_EXCEPTION($this, 8, "{$this->field_name} field is not valid", ["list" => $list])
            ; 
        }
        return $this;
    }
    function hasNumbersOnly() {
        if($this->no_validation_required()) { return $this; }   
        if (!preg_match('/^[\d]+$/u', $this->field_value)){
              throw new ESVE_EXCEPTION($this, 5, "The {$this->field_name} field must contain only numbers.");    
        }
        return $this;
    }
    function no_validation_required() {
        if(!$this->required && !$this->field_value) { return true; } 
        return false;    
    }
    function regMatch($exp) {
        if(!$this->required && !$this->field_value) { return true; }
        if(!preg_match("/^{$exp}$/u", $this->field_value)) {
                throw new ESVE_EXCEPTION($this, 11, "The {$this->field_name} field is invalid.");    
        }
            
        return $this;
    } 
    function check_date($format) {
        if(!$this->required && !$this->field_value) { return true; }
        if($format == "MM/DD/YYYY") {
            $date = explode("/", $this->field_value);
            if(!preg_match("/^\d{2}\/\d{2}\/\d{4}$/", $this->field_value) || !checkdate ( intval($date[0]) , intval($date[1]) , intval($date[2]) )) {
                throw new ESVE_EXCEPTION($this, 9, "The {$this->field_name} field has invalid date format. Please use $format.");    
            }
            
        }
        return $this;
    } 
    function DateNotLaterThen($date, $timezone = '') {
        if(!$this->required && !$this->field_value) { return true; } 
        if(!$timezone) { $timezone = date('e'); }
        if(!$this->required && !$this->field_value) { return true; }
        
        $date = new DateTime($date, new DateTimeZone($timezone)); 
        $date1 = $date->format('U');  
        $date1_format = $date->format('Y-m-d');
        
        $date_field = new DateTime($this->field_value, new DateTimeZone($timezone)); 
        $date2 = $date_field->format('U');  
           
        
        if($date2>$date1) {
            throw new ESVE_EXCEPTION($this, 10, "The {$this->field_name} date must be not later then $date1_format.");        
        }
        return $this;   
    }
}

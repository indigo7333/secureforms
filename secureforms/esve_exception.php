<?php
namespace indigo7;
class ESVE_exception extends \Exception {
    public $error_array = [
                          1 => "Email Validation Failed.",
                          2 => "Field is required",
                          3 => "String Length Validation Failed",
                          4 => "Only Letters Validation Failed",
                          5 => "Phone Field validation Failed",
                          6 => "Only Letters Numbers Validation Failed",
                          7 => "inList validation failed",
                          8 => "Key Validation Failed",
                          9 => "Invalid Date Format",
                          10 => "Date is not later then verification failed",
                          11 => "Regex verification failed"
                        ];
    public function __construct($object, $code = 0, $message = "", $info_array = array()) {
        
          
        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
    } 
    public function error_msg() {
        return $this->getMessage();  
    }                   
}
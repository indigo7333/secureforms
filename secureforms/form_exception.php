<?php
namespace indigo7;
class FM_exception extends \Exception {
  public $error_array = [
                          1 => "Token Verification Failed.",
                          2 => "Token Verification Failed.",
                          3 => "Token Verification Failed.",
                          4 => "Form Verification Failed."
                        ];
  public function __construct($object, $code = 0, $message = "") {
        
          
        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
  }
  public function error_msg() {
      //return $this->getMessage();      
      $code = $this->getCode();
      return $this->error_array[$code]; 
  }
     
                   
}

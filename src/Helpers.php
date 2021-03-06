<?php

namespace src;

class Helpers {
  public static function isJson($string) {
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
  }

  public static function getAuthorizationHeader(){
    $headers = null;
    if (isset($_SERVER['Authorization'])) {
        $headers = trim($_SERVER["Authorization"]);
    }
    else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
        $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
    } elseif (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
        $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
        //print_r($requestHeaders);
        if (isset($requestHeaders['Authorization'])) {
            $headers = trim($requestHeaders['Authorization']);
        }
    }
    return $headers;
  }
  /**
  * get access token from header
  * */
  public static function getBearerToken() {
    $headers = self::getAuthorizationHeader();
    // HEADER: Get the access token from the header
    if (!empty($headers)) {
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            return $matches[1];
        }
    }
    return null;
  }

  public static function getRequest(){
    $postdata = file_get_contents('php://input');
    
    if(Helpers::isJson($postdata)){
      $request = json_decode($postdata,true);
    } else {
      parse_str($postdata,$request);
    }
    
    return $request;
  }
  
  public static function getInput($request,$field, $filter){
    if(array_key_exists($field, $request)){
      return filter_var($request[$field],$filter) ?? null;
    }
    
    return null;
  }
}
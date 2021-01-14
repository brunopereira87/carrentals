<?php

namespace src;

class Response {
  public static function send($array){
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
    header("Content-Type: application/json");
    echo json_encode($array);
    exit;
  }
}
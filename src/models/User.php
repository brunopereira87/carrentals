<?php

namespace src\models;

use core\Model;
use Firebase\JWT\JWT;
use src\Response;
use src\Helpers;
use src\Config;

class User extends Model{

  public static function createToken($id,$name, $email){
    $date = new \DateTime("now");
    $payload = [
      'iat' => $date->getTimeStamp(),
      'data' => [
        'id' => $id,
        'name' => $name,
        'email' => $email
        ]
      ];

    return JWT::encode($payload, Config::SECRET);
  }
  public static function decodeToken($token){
    try{
      $decoded = JWT::decode($token,Config::SECRET,['HS256']);
    }catch(\Exception $e){
      $response = [
        'error' => 'Token inválido'
      ];
      Response::send($response);
    }

    return $decoded;
  }

  public static function auth(){
    $token = Helpers::getBearerToken();
    $decoded = User::decodeToken($token);
    $id = $decoded->data->id;
    $users = User::select(['id','name','email'])->where('id',$id)->execute();

    if(count($users) === 0) {
      $response = [
        'error' => 'Não autoriazado'
      ];
      Response::send($response);
    }

    return $users[0];
  }
}
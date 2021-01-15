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
      Response::sendErrorMessage('Token inválido');
    }

    return $decoded;
  }

  public static function auth(){
    $token = Helpers::getBearerToken();
    $decoded = User::decodeToken($token);
    $id = $decoded->data->id;
    $users = User::select(['id','name','email','role'])->where('id',$id)->execute();

    if(count($users) === 0) {
      Response::sendErrorMessage('Token inválido');

      $response = [
        'error' => 'Não autoriazado'
      ];
      Response::send($response);
    }

    return $users[0];
  }

  public static function authorize(){
    $user = self::auth();
    if($user['role'] == 1){
      return true ;
    }

    Response::sendErrorMessage('Você não tem permissão para acessar essa área');
  }
}
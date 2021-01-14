<?php

namespace src\controllers;

use src\Config;
use core\Controller;
use Firebase\JWT\JWT;
use src\Response;
use src\Helpers;
use src\models\User;

class AuthController extends Controller{
  
  public function register(){
    $response = ['error' => ''];
    $request = $this->getRequest();

    $name = filter_var($request['name'],FILTER_SANITIZE_SPECIAL_CHARS) ?? null;
    $email = filter_var($request['email'], FILTER_VALIDATE_EMAIL) ?? null;
    $password = filter_var($request['password'], FILTER_SANITIZE_STRING) ?? null;

    if(!($name && $email && $password)){
      $response['error'] = 'Nome, email e senha são campos obrigatórios';
      Response::send($response);
    }
    $date = new \DateTime("now");

    $user = User::select()->where('email',$email)->execute();
    if(count($user) > 0){
      $response['error'] = 'Este email já está cadastrado';
      Response::send($response);
    }
    
    $id = User::insert([
      'name' => $name,
      'email' => $email,
      'password' => password_hash($password,PASSWORD_DEFAULT)
      ])->execute();

    
    $payload = [
      'iat' => $date,
      'data' => [
        'name' => $name,
        'email' => $email
        ]
      ];
    
    
    $response['data'] = [
      'id' => intval($id),
      'name' => $name,
      'email' => $email
    ];
    $response['token'] = JWT::encode($payload, Config::SECRET);

    Response::send($response);
  }
  public function login(){
    $response = ['error' => ''];
    $request = $this->getRequest();

    $email = filter_var($request['email'], FILTER_VALIDATE_EMAIL) ?? null;
    $password = filter_var($request['password'], FILTER_SANITIZE_STRING) ?? null;

    if(!( $email && $password)){
      $response['error'] = 'Email e senha são campos obrigatórios';
      Response::send($response);
    }
    
    $user = User::select()->where('email',$email)->execute();

    if((count($user) === 0) || password_verify($password, $user[0]['password']) === false){
      $response['error'] = 'Email e/ou senha inválido';     
      Response::send($response);
    }

    $response['data'] = [
      'id' => intval($user[0]['id']),
      'name' => $user[0]['name'],
      'email' => $email
    ];

    $response['token'] = $this->getToken($user[0]['name'], $email);
    Response::send($response);
  }
  public function logout(){
    echo 'logout';
  }
  public function logged(){
    echo 'logged';
  }

  private function getRequest(){
    $postdata = file_get_contents('php://input');
    
    if(Helpers::isJson($postdata)){
      $request = json_decode($postdata,true);
    } else {
      parse_str($postdata,$request);
    }

    return $request;
  }

  private function getToken($name, $email){
    $date = new \DateTime("now");
    $payload = [
      'iat' => $date,
      'data' => [
        'name' => $name,
        'email' => $email
        ]
      ];
    
    return JWT::encode($payload, Config::SECRET);
  }

}
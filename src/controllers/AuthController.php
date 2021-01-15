<?php

namespace src\controllers;

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
    
    $id = intval($id);
    $response['data'] = [
      'id' => $id,
      'name' => $name,
      'email' => $email
    ];
    $response['token'] = User::createToken($id, $name,$email);

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
    
    $users = User::select()->where('email',$email)->execute();

    if((count($users) === 0) || password_verify($password, $users[0]['password']) === false){
      $response['error'] = 'Email e/ou senha inválido';     
      Response::send($response);
    }
    $user = $users[0];
    $response['data'] = [
      'id' => intval($user['id']),
      'name' => $user['name'],
      'email' => $email
    ];

    $response['token'] = User::createToken($user['id'], $user['name'], $email);
    Response::send($response);
  }

  public function logged(){
    $user = User::auth();

    $response = [
      'data' => $user
    ];
    Response::send($response);
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

  

}
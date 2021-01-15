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
    $request = Helpers::getRequest();

    $name = Helpers::getInput($request,'name',FILTER_SANITIZE_SPECIAL_CHARS);
    $email = Helpers::getInput($request,'email', FILTER_VALIDATE_EMAIL);
    $password = Helpers::getInput($request,'password', FILTER_SANITIZE_STRING);

    if(!($name && $email && $password)){
      Response::sendErrorMessage('Nome, email e senha são campos obrigatórios');
    }


    $user = User::select()->where('email',$email)->execute();
    if(count($user) > 0){
      Response::sendErrorMessage('Este email já está cadastrado');
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
    $request = Helpers::getRequest();

    $email = Helpers::getInput($request,'email', FILTER_VALIDATE_EMAIL);
    $password = Helpers::getInput($request,'password', FILTER_SANITIZE_STRING);

    if(!( $email && $password)){
      Response::sendErrorMessage('Email e senha são campos obrigatórios');
    }
    
    $users = User::select()->where('email',$email)->execute();

    if((count($users) === 0) || password_verify($password, $users[0]['password']) === false){
      Response::sendErrorMessage('Email e/ou senha inválido');
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

}
<?php

namespace src\controllers;

use core\Controller;
use src\models\User;
use src\models\Car;
use src\Response;
use src\Helpers;
class CarsController extends Controller{
  public function __construct(){
    User::auth();
  }

  public function create(){
    User::authorize();

    $request = Helpers::getRequest();

    $name = Helpers::getInput($request,'name',FILTER_SANITIZE_SPECIAL_CHARS);
    $plate = Helpers::getInput( $request,'plate', FILTER_SANITIZE_STRING);
    $company = Helpers::getInput($request,'company',FILTER_SANITIZE_SPECIAL_CHARS);
    $rental_price = Helpers::getInput($request,'rental_price',FILTER_VALIDATE_FLOAT);

    if(!($name && $rental_price)){
      Response::sendErrorMessage(
        'Os campos name e rental_price são obrigatório e devem ser preenchidos corretamente'
      );
    }

    $car = [
      'name' => $name,
      'plate' => $plate,
      'company' => $company,
      'rental_price' => $rental_price,
    ];
    
    $lastCarId = Car::insert($car)->execute();
    $car['id'] = $lastCarId;

    $response = ['data' => $car ];
    Response::send($response);
  }
  public function read($args){

    $response = [];
    try{
      if(isset($args['id'])){
        $car = Car::getCar($args['id']);
        $response['data'] = $car;
      }
      else{
        $cars = Car::getCars();
        $response['data'] = $cars;
      }
    }
    catch(\Exception $e){
      Response::sendErrorMessage('Erro ao buscar carros');
    }
    
    Response::send($response);
    
  }
  public function update($args){
    if(!(isset($args['id']))){
      Response::sendErrorMessage('Id do carro não enviado');
    }
    
    $car = Car::getCar($args['id']);
    $request = Helpers::getRequest();

    $name = Helpers::getInput($request,'name',FILTER_SANITIZE_SPECIAL_CHARS);
    $plate = Helpers::getInput( $request,'plate', FILTER_SANITIZE_STRING);
    $company = Helpers::getInput($request,'company',FILTER_SANITIZE_SPECIAL_CHARS);
    $rental_price = Helpers::getInput($request,'rental_price',FILTER_VALIDATE_FLOAT);

    $updateArray = [];
    if($name){
      $updateArray['name'] = $name;
    }
    if($plate){
      $updateArray['plate'] = $plate;
    }
    if($company){
      $updateArray['company'] = $company;
    }
    if($rental_price){
      $updateArray['rental_price'] = $rental_price;
    }

    Car::update()
      ->set($updateArray)
      ->where('id',$args['id'])
      ->execute();
    
    $response = [
      'data'=> Car::getCar($args['id'])
    ];

    Response::send($response);
  }
  public function delete($args){
    if(!(isset($args['id']))){
      Response::sendErrorMessage('Id do carro não enviado');
    }

    Car::delete($args['id']);
    Response::send([]);
  }

  public function rent($args){
    User::authorize();
    if(!(isset($args['id']))){
      Response::sendErrorMessage('Id do carro não enviado');
    }
    
    $car = Car::getCar($args['id']);

    if($car['rented'] == 1) {
      Response::sendErrorMessage('Este caro já está alugado');
    }
    $request = Helpers::getRequest();
    $rental_user_id = Helpers::getInput($request,'rental_user_id',FILTER_VALIDATE_INT);
    
    if(!$rental_user_id){
      Response::sendErrorMessage('O id do usuário que está alugando o carro é obrigatório');
    }

    if(!User::exists($rental_user_id)){
      Response::sendErrorMessage('Usuário não encontrado');
    }
    $updateArray = [
      'rented' => 1,
      'rental_user_id' => $rental_user_id,
    ];

    Car::update()
      ->set($updateArray)
      ->where('id',$args['id'])
      ->execute();
    
    Response::send(['message' => 'Carro alugado com sucesso']);
  }

  public function return($args){
    User::authorize();
    if(!(isset($args['id']))){
      Response::sendErrorMessage('Id do carro não enviado');
    }
    
    $car = Car::getCar($args['id']);

    if($car['rented'] == 0) {
      Response::sendErrorMessage('Este caro não está alugado');
    }
    
    $updateArray = [
      'rented' => 0,
      'rental_user_id' => null,
    ];

    Car::update()
      ->set($updateArray)
      ->where('id',$args['id'])
      ->execute();
    
    Response::send(['message' => 'Carro devolvido com sucesso']);
  }
}
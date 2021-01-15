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
    $day_rental_price = Helpers::getInput($request,'day_rental_price',FILTER_VALIDATE_FLOAT);

    if(!($name && $day_rental_price)){
      Response::sendErrorMessage(
        'Os campos name e day_rental_price são obrigatório e devem ser preenchidos corretamente'
      );
    }

    $car = [
      'name' => $name,
      'plate' => $plate,
      'company' => $company,
      'day_rental_price' => $day_rental_price,
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
        $response['data'] = $cars[0];
      }
      else{
        $cars = Car::select()->execute();
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
    $day_rental_price = Helpers::getInput($request,'day_rental_price',FILTER_VALIDATE_FLOAT);

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
    if($day_rental_price){
      $updateArray['day_rental_price'] = $day_rental_price;
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
}
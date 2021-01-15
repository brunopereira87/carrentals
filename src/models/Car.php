<?php

namespace src\models;

use core\Model;
use src\Response;
use src\Helpers;
use src\models\User;

class Car extends Model{
  public static function getCar($id){
    $cars = Car::select()->where('id',$id)->execute();
    if(count($cars) === 0){
      Response::sendErrorMessage('Carro não encontrado');
    }

    $firstCar = $cars[0]; 
    $car = self::formatCarResponse($firstCar);

    return $car;
  }

  public static function getCars(){
    $rawCars = Car::select()->execute();
    $cars = array_map(fn($rawCar) => self::formatCarResponse($rawCar),$rawCars);

    return $cars;
  }
  public static function formatCarResponse($rawCar){
    if($rawCar['rented'] == 1){
      $user = User::getUser($rawCar['rental_user_id']);
    }
    else {
      $user = null;
    }
    $car = [
      'name' => $rawCar['name'],
      'plate' => $rawCar['plate'],
      'company' => $rawCar['company'],
      'rental_price' => doubleval($rawCar['rental_price']),
      'rented' => intval($rawCar['rented']),
      'rental_user' => $user
    ];

    return $car;
  }
}
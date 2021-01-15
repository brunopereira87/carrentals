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

    return $cars[0];
  }
}
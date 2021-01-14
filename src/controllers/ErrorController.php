<?php
namespace src\controllers;

use \core\Controller;

class ErrorController extends Controller {

    public function index() {
        echo json_encode([
            'error' => 'Endpoint não encontrado'
        ]);
    }

}
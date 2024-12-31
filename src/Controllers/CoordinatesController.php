<?php

namespace Src\Controllers;

use Src\Services\CoordinatesService;

class CoordinatesController
{
    private $requestMethod;
    private $service;

    public function __construct()
    {
        $this->service = new CoordinatesService();
    }

    public function processRequest($userId = null)
    {
        $this->requestMethod = $_SERVER["REQUEST_METHOD"];
        if ($this->requestMethod == 'GET') {
            $response =  $this->service->getCoordinates($userId);
        }
        header('Content-Type: application/json');
        echo $response;
        exit();
    }
}
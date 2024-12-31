<?php

namespace Src\Controllers;

use Src\Services\HomeService;

/**
 * Class HomeController
 *
 * This class provides methods for handling requests related to the 'Home' page.
 */
class HomeController
{
    private $requestMethod;
    private $service;

    /**
     * HomeController constructor.
     *
     * Initializes the HomeController object and processes the request.
     */
    public function __construct()
    {
        $this->service = new HomeService();
        $this->processRequest();
    }

    /**
     * Processes the request.
     *
     * This method determines the request method and calls the appropriate service method.
     * It then sets the response header and, if there is a response body, echoes it.
     */
    public function processRequest()
    {
        $this->requestMethod = $_SERVER["REQUEST_METHOD"];
        if ($this->requestMethod == 'GET') {
            $response = $this->service->getHomePage();
        }
        else{
            $response = [
                'status_code_header' => 'HTTP/1.1 405 Method Not Allowed',
                'body' => null
            ];
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }
}
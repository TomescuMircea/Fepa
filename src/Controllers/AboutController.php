<?php

namespace Src\Controllers;

use Src\Services\AboutService;

/**
 * Class AboutController
 *
 * This class provides methods for handling requests related to the 'About' page.
 */
class AboutController
{
    private $requestMethod;
    private $service;

    /**
     * AboutController constructor.
     *
     * Initializes the AboutController object and processes the request.
     */
    public function __construct()
    {
        $this->service = new AboutService();
        $this->processRequest();
    }

    /**
     * Connects to the database.
     *
     * This method attempts to establish a connection to the database using the configuration settings.
     * If the connection fails, it triggers an error.
     */
    public function processRequest()
    {
        $this->requestMethod = $_SERVER["REQUEST_METHOD"];
        if ($this->requestMethod == 'GET') {
            $response = $this->service->getAboutPage();
        }
        else {
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
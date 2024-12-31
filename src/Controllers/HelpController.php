<?php

namespace Src\Controllers;

use Src\Services\HelpService;

/**
 * Class HelpController
 *
 * This class provides methods for handling requests related to the 'Help' page.
 */
class HelpController
{
    private $requestMethod;
    private $service;

    /**
     * HelpController constructor.
     *
     * Initializes the HelpController object and processes the request.
     */
    public function __construct()
    {
        $this->service = new HelpService();
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
            $response = $this->service->getHelpPage();
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
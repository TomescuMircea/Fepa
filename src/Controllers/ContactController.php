<?php

namespace Src\Controllers;

use Src\Services\ContactService;

/**
 * Class ContactController
 *
 * This class provides methods for handling requests related to the 'Contact' page.
 */
class ContactController
{
    private $requestMethod;
    private $contactService;

    /**
     * ContactController constructor.
     *
     * Initializes the ContactController object and sets the request method.
     *
     * @param string $requestMethod The HTTP request method.
     */
    public function __construct($requestMethod)
    {
        $this->contactService = new ContactService();
        $this->requestMethod = $requestMethod;
    }

    /**
     * Processes the request.
     *
     * This method determines the request method and calls the appropriate service method.
     * It then sets the response header and, if there is a response body, echoes it.
     *
     * @param array|null $data The request data.
     */
    public function processRequest($data = null)
    {
        switch ($this->requestMethod) {
            case 'GET':
                $response = $this->contactService->getContactPage();
                break;
            case 'POST':
                $response = $this->contactService->submitForm($data);
                break;
            default:
                $response = [
                    'status_code_header' => 'HTTP/1.1 405 Method Not Allowed',
                    'body' => null
                ];
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }
}
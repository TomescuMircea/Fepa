<?php

namespace Src\Controllers;

use Db;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Src\Services\ReportAnimalService;

/**
 * Class ReportAnimalController
 *
 * This class provides methods for handling requests related to reporting an animal.
 */
class ReportAnimalController
{
    private $requestMethod;
    private $param;
    private $reportAnimalService;
    private string $secretKey;
    private $userId;

    /**
     * ReportAnimalController constructor.
     *
     * Initializes the ReportAnimalController object and sets the request method and parameter.
     *
     * @param string $requestMethod The HTTP request method.
     * @param string $param The parameter for the request.
     */
    public function __construct($requestMethod, $param)
    {
        $this->requestMethod = $requestMethod;
        $this->param = $param;
        $this->reportAnimalService = new ReportAnimalService();
        $this->secretKey = SECRET_KEY; // secret key for JWT
    }

    /**
     * Processes the request.
     *
     * This method determines the request method and calls the appropriate service method.
     * It then sets the response header and, if there is a response body, echoes it.
     *
     * @param array|null $data The request data.
     * @param array|null $images The images for the request.
     */
    public function processRequest($data, $images = null)
    {
        // Retrieve the JWT token from the cookie
        $jwt = $_COOKIE['jwt'] ?? null;

        // If the JWT token exists
        if ($jwt) {
            $key = new Key($this->secretKey, 'HS256');
            $decoded = JWT::decode($jwt, $key);
            // Extract the user id
            $userId = $decoded->data->id;
        }
        else{
            $response = $this->reportAnimalService->getNotAuthenticatedPage();
            header($response['status_code_header']);
            if ($response['body']) {
                echo $response['body'];
            }
            exit();
        }

        switch ($this->requestMethod) {
            case 'GET':
                if ($this->param == "") {
                    $response = $this->reportAnimalService->getReportPage();
                }
                break;
            case 'POST':
                $response = $this->reportAnimalService->submitForm($data,$images,$userId);
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

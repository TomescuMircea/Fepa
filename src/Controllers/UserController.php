<?php

namespace Src\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Src\Services\UserService;

class UserController
{
    private $requestMethod;
    private $service;

    private $secretKey;

    public function __construct()
    {
        $this->service = new UserService();
        $this->secretKey = SECRET_KEY; // secret key for JWT
    }

    /**
     * Processes the request.
     *
     * This method determines the request method and calls the appropriate service method.
     * It then sets the response header and, if there is a response body, echoes it.
     */
    public function processRequest($userId)
    {
        $jwt = $_COOKIE['jwt'] ?? null;

        $this->requestMethod = $_SERVER["REQUEST_METHOD"];
        if ($this->requestMethod == 'DELETE') {
            if ($jwt){
                $key = new Key($this->secretKey, 'HS256');
                $decoded = JWT::decode($jwt, $key);
                // Extract the user id
                $userRole = $decoded->data->role;
                if ($userRole == 'admin') {
                    $response = $this->service->deleteUserById($userId);
                }
                else {
                    $response = [
                        'status_code_header' => 'HTTP/1.1 403 Forbidden',
                        'body' => json_encode(['message' => 'You are not authorized to access this page'])
                    ];
                }
            }
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }
}
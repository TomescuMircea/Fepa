<?php

namespace Src\Services;

use Src\Repositories\UserRepository;

class UserService{
    private $repository;

    public function __construct()
    {
        $this->repository = new UserRepository();
    }

    /**
     * Deletes a user by ID.
     *
     * This method deletes a user by ID and returns a response with a HTTP status code and a message.
     *
     * @param int $userId The ID of the user to delete.
     * @return array The HTTP response status and body content.
     */
    public function deleteUserById(int $userId): array
    {
        if ($this->repository->deleteUserById($userId)) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(['message' => 'User deleted']);
        } else {
            $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
            $response['body'] = json_encode(['message' => 'User not found']);
        }

        return $response;
    }


}
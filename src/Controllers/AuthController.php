<?php

namespace Src\Controllers;

use Firebase\JWT\JWT;
use Src\Services\AuthService;

/**
 * Class AuthController
 *
 * This class provides methods for handling authentication-related requests.
 */
class AuthController
{
    private $authService;
    private $secretKey;

    /**
     * AuthController constructor.
     *
     * Initializes the AuthController object and sets the secret key for JWT.
     */
    public function __construct()
    {
        $this->authService = new AuthService();
        $this->secretKey = SECRET_KEY; // secret key for JWT
    }

    /**
     * Handles user registration.
     *
     * This method processes a POST request to register a new user. It checks for duplicate usernames or emails,
     * and if none are found, it adds the user and sends a confirmation email.
     */
    public function register()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $input['username'];
            $email = $input['email'];
            $password = password_hash($input['password'], PASSWORD_BCRYPT);

            if ($this->authService->checkDuplicates($username, $email)) {
                http_response_code(400);
                echo json_encode(['error' => true, 'message' => 'Username or email already exists']);
            } else {
                $token = bin2hex(random_bytes(16));
                $this->authService->addUser($username, $email, $password, $token);
                if ($this->authService->sendConfirmationEmail($email, $token))
                    echo json_encode(['success' => true, 'message' => 'Registration successful, please check your email to confirm.']);
            }
        }
    }

    /**
     * Confirms a user.
     *
     * This method processes a GET request to confirm a user's email address. It checks for a valid token,
     * and if found, it confirms the user and redirects to the homepage.
     */
    public function confirm()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['token'])) {
            $token = $_GET['token'];
            if ($this->authService->confirmUser($token)) {
                header('Location: https://localhost/tw-project/index.php');
            } else {
                header('Location: https://localhost/tw-project/index.php/failedConfirmation');
            }
        } else {
            echo json_encode(['error' => true, 'message' => 'Token not provided']);
        }
    }

    /**
     * Handles user login.
     *
     * This method processes a POST request to log in a user. It checks the username and password,
     * and if they are valid, it generates a JWT and sets it as a cookie.
     */
    public function login()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $input['username'];
            $password = $input['password'];

            if (($user = $this->authService->getUserByUsername($username)) != null) {
                if ($user->getConfirmed() == 0) {
                    http_response_code(403);
                    echo json_encode(['message' => 'Account not confirmed']);
                    exit();
                }
                if (password_verify($password, $user->getPassword())) {
                    $payload = [
                        'iss' => 'https://127.0.0.1',
                        'iat' => time(),
                        'exp' => time() + (60 * 60), // 1 hour expiration
                        'data' => [
                            'id' => $user->getId(),
                            'username' => $username,
                            'role' => $user->getRole()
                        ]
                    ];
                    $jwt = JWT::encode($payload, $this->secretKey, 'HS256');
                    setcookie('jwt', $jwt, time() + (60 * 60), '/');

                    http_response_code(200);
                    echo json_encode(['message' => 'Login successful']);
                } else {
                    http_response_code(401);
                    echo json_encode(['message' => 'Invalid credentials']);
                }
            } else {
                http_response_code(401);
                echo json_encode(['message' => 'Invalid credentials']);
            }
        }
    }

    /**
     * Handles user logout.
     *
     * This method logs out a user by clearing the JWT cookie and redirecting to the homepage.
     */
    public function logout()
    {
        // redirect to homepage and clear cookie
        setcookie('jwt', '', time() - 3600, '/');
        ob_clean();
        header('Location: /tw-project/index.php');
    }

    /**
     * Recovers a user's username.
     *
     * This method processes a POST request to recover a user's username. It checks for a valid email,
     * and if found, it sends an email to the user with their username.
     */
    public function recoverUsername()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $input['email'];
            $user = $this->authService->getUserByEmail($email);
            if ($user) {
                $username = $user->getUsername();
                $subject = 'Username Recovery';
                $body = 'Your username is: ' . $username;
                $this->authService->sendEmail($email, $subject, $body);
                http_response_code(200);
                echo json_encode(['message' => 'Username sent to your email']);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'No user found with that email address']);
            }
        }
    }

    /**
     * Recovers a user's password.
     *
     * This method processes a POST request to recover a user's password. It checks for a valid email,
     * and if found, it sends an email to the user with a password reset link.
     */
    public function recoverPassword()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $input['email'];
            $user = $this->authService->getUserByEmail($email);
            if ($user) {
                $token = bin2hex(random_bytes(16));
                $this->authService->storeResetToken($email, $token);
                $resetLink = 'https://localhost/tw-project/index.php/changePassword?token=' . $token;
                $subject = 'Password Reset';
                $body = 'Click the link to reset your password: <a href=' . $resetLink . '>Reset Password</a>';
                $this->authService->sendEmail($email, $subject, $body);
                http_response_code(200);
                echo json_encode(['message' => 'Password reset link sent to your email']);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'No user found with that email address']);
            }
        }
    }

    /**
     * Adds CORS headers.
     *
     * This method adds the necessary CORS headers to allow cross-origin requests.
     */
    private function addCorsHeaders()
    {
        // Allow from any origin
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400'); // cache for 1 day
        }

        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            }
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            }
            exit(0);
        }
    }

    /**
     * Changes a user's password.
     *
     * This method processes a POST request to change a user's password. It checks for a valid reset token and matching passwords,
     * and if all checks pass, it updates the user's password.
     */
    public function changePassword()
    {
        $this->addCorsHeaders();
        $input = json_decode(file_get_contents('php://input'), true);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $input['token'];
            $newPassword = $input['new-password'];
            $confirmPassword = $input['confirm-password'];

            if ($newPassword !== $confirmPassword) {
                http_response_code(400);
                echo json_encode(['message' => 'Passwords do not match']);
                exit;
            }

            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            $user = $this->authService->getUserByResetToken($token);

            if ($user) {
                $token = $user->getResetToken();
                $this->authService->updatePassword($token, $hashedPassword);
                http_response_code(200);
                echo json_encode(['message' => 'Password changed successfully']);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Invalid or expired token']);
            }
        }
    }

    /**
     * Displays the login page.
     *
     * This method processes a GET request to display the login page.
     */
    public function displayLoginPage()
    {
        $this->requestMethod = $_SERVER["REQUEST_METHOD"];
        if ($this->requestMethod == 'GET') {
            $response = $this->authService->getLoginPage();
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    /**
     * Displays the register page.
     *
     * This method processes a GET request to display the register page.
     */
    public function displayRegisterPage()
    {
        $this->requestMethod = $_SERVER["REQUEST_METHOD"];
        if ($this->requestMethod == 'GET') {
            $response = $this->authService->getRegisterPage();
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    /**
     * Displays the recover credentials page.
     *
     * This method processes a GET request to display the recover credentials page.
     */
    public function displayRecoverCredentialsPage()
    {
        $this->requestMethod = $_SERVER["REQUEST_METHOD"];
        if ($this->requestMethod == 'GET') {
            $response = $this->authService->getRecoverCredentialsPage();
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    /**
     * Displays the change password page.
     *
     * This method processes a GET request to display the change password page.
     */
    public function displayChangePasswordPage()
    {
        $this->requestMethod = $_SERVER["REQUEST_METHOD"];
        if ($this->requestMethod == 'GET') {
            $response = $this->authService->getChangePasswordPage();
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    /**
     * Displays the confirmation notice.
     *
     * This method processes a GET request to display the confirmation notice.
     */
    public function displayConfirmationNotice()
    {
        $this->requestMethod = $_SERVER["REQUEST_METHOD"];
        if ($this->requestMethod == 'GET') {
            $response = $this->authService->getConfirmationNotice();
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    /**
     * Displays the confirmation fail page.
     *
     * This method processes a GET request to display the confirmation fail page.
     */
    public function displayConfirmationFail()
    {
        $this->requestMethod = $_SERVER["REQUEST_METHOD"];
        if ($this->requestMethod == 'GET') {
            $response = $this->authService->displayConfirmationFailPage();
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }
}

<?php

namespace Src\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Src\Models\UserModel;
use Src\Repositories\UserRepository;

/**
 * Returns the "About" page.
 *
 * This method retrieves the content of the "About" page and returns it along with a HTTP status code.
 *
 * @return array The HTTP response status and body content.
 */
class AuthService
{
    private $repository;
    private $secretKey = SECRET_KEY; // secret key for JWT

    /**
     * AuthService constructor.
     *
     * Initializes the UserRepository object.
     */
    public function __construct()
    {
        $this->repository = new UserRepository();
    }

    /**
     * Checks for duplicate username or email.
     *
     * @param string $username The username to check.
     * @param string $email The email to check.
     * @return bool Returns true if duplicates are found, false otherwise.
     */
    public function checkDuplicates($username, $email)
    {
        return $this->repository->checkDuplicates($username, $email);
    }

    /**
     * Adds a new user to the database.
     *
     * @param string $username The username of the new user.
     * @param string $email The email of the new user.
     * @param string $password The password of the new user.
     * @param string $token The confirmation token for the new user.
     * @return bool Returns true if the user was added successfully, false otherwise.
     */
    public function addUser($username, $email, $password, $token)
    {
        return $this->repository->addUser($username, $email, $password, $token);
    }

    /**
     * Confirms a user's email address.
     *
     * @param string $token The confirmation token.
     * @return bool Returns true if the user was confirmed successfully, false otherwise.
     */
    public function confirmUser($token)
    {
        return $this->repository->confirmUser($token);
    }

    /**
     * Retrieves a user by their username.
     *
     * @param string $username The username of the user.
     * @return UserModel Returns the user model.
     */
    public function getUserByUsername(string $username) : UserModel
    {
        return $this->repository->getUserByUsername($username);
    }

    /**
     * Retrieves a user by their email.
     *
     * @param string $email The email of the user.
     * @return UserModel Returns the user model.
     */
    public function getUserByEmail(string $email) : UserModel
    {
        return $this->repository->getUserByEmail($email);
    }

    /**
     * Stores a reset token for a user.
     *
     * @param string $email The email of the user.
     * @param string $token The reset token.
     */
    public function storeResetToken(string $email, string $token): void
    {
       $this->repository->storeResetToken($email, $token);
    }

    /**
     * Sends an email.
     *
     * @param string $email The recipient's email address.
     * @param string $subject The subject of the email.
     * @param string $body The body of the email.
     * @throws \Exception Throws an exception if the email could not be sent.
     */
    public function sendEmail(string $email, string $subject, string $body): void
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.sendgrid.net';
            $mail->SMTPAuth = true;
            $mail->Username = 'apikey';
            $mail->Password = '';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('something@gmail.com', 'Fepa.com');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            $mail->send();
        } catch (Exception $e) {
            throw new \Exception('Message could not be sent. Mailer Error: ' . $mail->ErrorInfo);
        }
    }

    /**
     * Returns the login page.
     *
     * This method retrieves the content of the login page and returns it along with a HTTP status code.
     *
     * @return array The HTTP response status and body content.
     */
    public function getLoginPage(): array
    {
        ob_start(); // Start output buffering
        include(__DIR__ . '/../Pages/login.php'); // Include the file using a file path
        $pageContent = ob_get_clean(); // Get the contents of the output buffer and clean (erase) the output buffer

        return [
            'status_code_header' => 'HTTP/1.1 200 OK',
            'body' => $pageContent
        ];
    }

    /**
     * Returns the register page.
     *
     * This method retrieves the content of the register page and returns it along with a HTTP status code.
     *
     * @return array The HTTP response status and body content.
     */
    public function getRegisterPage(): array
    {
        ob_start(); // Start output buffering
        include(__DIR__ . '/../Pages/register.php'); // Include the file using a file path
        $pageContent = ob_get_clean(); // Get the contents of the output buffer and clean (erase) the output buffer

        return [
            'status_code_header' => 'HTTP/1.1 200 OK',
            'body' => $pageContent
        ];
    }

    /**
     * Sends a confirmation email.
     *
     * This method sends a confirmation email to the user with a link to confirm their email address.
     *
     * @param mixed $email The email address of the user.
     * @param string $token The confirmation token.
     * @throws \Exception If the email could not be sent.
     */
    public function sendConfirmationEmail(mixed $email, string $token): bool
    {
        $subject = 'Email Confirmation';
        $body = 'Please click the link below to confirm your email address:<br>';
        $body .= '<a href=https://localhost/tw-project/index.php/confirm?token=' . $token . '>Confirm Email</a>';
        $this->sendEmail($email, $subject, $body);
        return true;
    }

    /**
     * Returns the recover credentials page.
     *
     * This method retrieves the content of the recover credentials page and returns it along with a HTTP status code.
     *
     * @return array The HTTP response status and body content.
     */
    public function getRecoverCredentialsPage(): array
    {
        ob_start(); // Start output buffering
        include(__DIR__ . '/../Pages/recovery.php'); // Include the file using a file path
        $pageContent = ob_get_clean(); // Get the contents of the output buffer and clean (erase) the output buffer

        return [
            'status_code_header' => 'HTTP/1.1 200 OK',
            'body' => $pageContent
        ];
    }

    /**
     * Returns the change password page.
     *
     * This method retrieves the content of the change password page and returns it along with a HTTP status code.
     *
     * @return array The HTTP response status and body content.
     */
    public function getChangePasswordPage(): array
    {
        ob_start(); // Start output buffering
        include(__DIR__ . '/../Pages/change-password.php'); // Include the file using a file path
        $pageContent = ob_get_clean(); // Get the contents of the output buffer and clean (erase) the output buffer

        return [
            'status_code_header' => 'HTTP/1.1 200 OK',
            'body' => $pageContent
        ];
    }

    /**
     * Retrieves a user by their reset token.
     *
     * @param mixed $token The reset token.
     * @return UserModel Returns the user model.
     */
    public function getUserByResetToken(mixed $token): UserModel
    {
        return $this->repository->getUserByResetToken($token);
    }

    /**
     * Updates a user's password.
     *
     * @param string $token The reset token.
     * @param string $hashedPassword The hashed password.
     * @return bool Returns true if the password was updated successfully, false otherwise.
     */
    public function updatePassword(string $token, string $hashedPassword): bool
    {
        return $this->repository->updatePassword($token, $hashedPassword);
    }

    /**
     * Returns the confirmation page.
     *
     * This method retrieves the content of the confirmation page and returns it along with a HTTP status code.
     *
     * @return array The HTTP response status and body content.
     */
    public function displayConfirmationPage(): array
    {
        ob_start(); // Start output buffering
        include(__DIR__ . '/../Pages/confirmation.php'); // Include the file using a file path
        $pageContent = ob_get_clean(); // Get the contents of the output buffer and clean (erase) the output buffer

        return [
            'status_code_header' => 'HTTP/1.1 200 OK',
            'body' => $pageContent
        ];
    }

    /**
     * Returns the confirmation fail page.
     *
     * This method retrieves the content of the confirmation fail page and returns it along with a HTTP status code.
     *
     * @return array The HTTP response status and body content.
     */
    public function displayConfirmationFailPage(): array
    {
        ob_start(); // Start output buffering
        include(__DIR__ . '/../Pages/confirmation-fail.php'); // Include the file using a file path
        $pageContent = ob_get_clean(); // Get the contents of the output buffer and clean (erase) the output buffer

        return [
            'status_code_header' => 'HTTP/1.1 401 OK',
            'body' => $pageContent
        ];
    }

    /**
     * Returns the confirmation notice page.
     *
     * This method retrieves the content of the confirmation notice page and returns it along with a HTTP status code.
     *
     * @return array The HTTP response status and body content.
     */
    public function getConfirmationNotice(): array
    {
        ob_start(); // Start output buffering
        include(__DIR__ . '/../Pages/confirmation-notice.php'); // Include the file using a file path
        $pageContent = ob_get_clean(); // Get the contents of the output buffer and clean (erase) the output buffer

        return [
            'status_code_header' => 'HTTP/1.1 200 OK',
            'body' => $pageContent
        ];
    }
}

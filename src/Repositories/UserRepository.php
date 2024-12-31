<?php

namespace Src\Repositories;

use Exception;
use mysqli_sql_exception;
use Src\Models\UserModel;

use mysqli;

class UserRepository extends Repository
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Checks for duplicate usernames or emails.
     *
     * This method takes a username and an email, queries the 'users' table for matching records, and returns true if a duplicate is found.
     *
     * @param string $username The username to check.
     * @param string $email The email to check.
     * @return bool True if a duplicate is found, false otherwise.
     */
    public function checkDuplicates(string $username, string $email): bool
    {
        try {
            $stmt = $this->db->prepare('select * from users where username = ? or email = ?');
            $stmt->bind_param('ss', $username, $email);
            $stmt->execute();

            if ($stmt->get_result()->num_rows > 0) {
                return true;
            }
        } catch (Exception $e) {
            trigger_error('Error in ' . __METHOD__ . ': ' . $e->getMessage(), E_USER_ERROR);
        }

        return false;
    }

    /**
     * Adds a new user.
     *
     * This method takes a username, an email, a password, and a confirmation token, and attempts to create a new record in the 'users' table.
     *
     * @param string $username The username of the new user.
     * @param string $email The email of the new user.
     * @param string $password The password of the new user.
     * @param string $confirmation_token The confirmation token of the new user.
     */
    public function addUser(string $username, string $email, string $password, string $confirmation_token) : void
    {
        try {
            $stmt = $this->db->prepare('insert into users (username, email, password, confirmation_token) VALUES(?, ?, ?, ?)');
            $stmt->bind_param('ssss', $username, $email, $password, $confirmation_token);
            $stmt->execute();
            $stmt->close();
        } catch (Exception $e) {
            trigger_error('Error in ' . __METHOD__ . ': ' . $e->getMessage(), E_USER_ERROR);
        }
    }

    /**
     * Retrieves a user by username.
     *
     * This method takes a username, queries the 'users' table for a matching record, and returns a UserModel object.
     *
     * @param string $username The username of the user.
     * @return UserModel|null The user object, or null if no matching user is found.
     */
    public function getUserByUsername(string $username): ?UserModel
    {
        $user = new UserModel();

        try {
            $stmt = $this->db->prepare('select * from users where username = ? ');
            $stmt->bind_param('s', $username);
            $stmt->execute();
        } catch (Exception $e) {
            trigger_error('Error in ' . __METHOD__ . ': ' . $e->getMessage(), E_USER_ERROR);
        }

        $result = $stmt->get_result();
        if ($result->num_rows == 0) {
            return null;
        }

        foreach ($result as $row) {
            $user->setUsername($row['username']);
            $user->setEmail($row['email']);
            $user->setPassword($row['password']);
            $user->setRole($row['role']);
            $user->setConfirmed($row['confirmed']);
            $user->setConfirmationToken($row['confirmation_token']);
            $user->setResetToken($row['reset_token']);
            $user->setId($row['id']);
        }

        $stmt->close();

        return $user;
    }

    /**
     * Confirms a user.
     *
     * This method takes a confirmation token, queries the 'users' table for a matching record, and sets the 'confirmed' field to 1.
     *
     * @param string $confirmation_token The confirmation token of the user.
     * @return bool True if the user is confirmed, false otherwise.
     */
    public function confirmUser(string $confirmation_token) : bool
    {
        try {
            $stmt = $this->db->prepare('update users set confirmed = 1, confirmation_token = NULL where confirmation_token = ?');
            $stmt->bind_param('s', $confirmation_token);
            $stmt->execute();
            if ($stmt->affected_rows == 0) {
                return false;
            }
            $stmt->close();
            return true;
        } catch (Exception $e) {
            trigger_error('Error in ' . __METHOD__ . ': ' . $e->getMessage(), E_USER_ERROR);
        }
    }

    /**
     * Checks if a user is confirmed.
     *
     * This method takes a username, queries the 'users' table for a matching record with 'confirmed' set to 1, and returns true if such a user is found.
     *
     * @param string $username The username of the user.
     * @return bool True if the user is confirmed, false otherwise.
     */
    public function isConfirmed(string $username): bool
    {
        try {
            $stmt = $this->db->prepare('select * from users where username = ? and confirmed = 1');
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows == 0) {
                return false;
            }
        } catch (Exception $e) {
            trigger_error('Error in ' . __METHOD__ . ': ' . $e->getMessage(), E_USER_ERROR);
        }

        return true;
    }

    /**
     * Retrieves a user by email.
     *
     * This method takes an email, queries the 'users' table for a matching record, and returns a UserModel object.
     *
     * @param string $email The email of the user.
     * @return UserModel|null The user object, or null if no matching user is found.
     */
    public function getUserByEmail(string $email) : ?UserModel
    {
        $user = new UserModel();

        try {
            $stmt = $this->db->prepare('select * from users where email = ? ');
            $stmt->bind_param('s', $email);
            $stmt->execute();
        } catch (Exception $e) {
            trigger_error('Error in ' . __METHOD__ . ': ' . $e->getMessage(), E_USER_ERROR);
        }

        $result = $stmt->get_result();
        if ($result->num_rows == 0) {
            return null;
        }

        foreach ($result as $row) {
            $user->setUsername($row['username']);
            $user->setEmail($row['email']);
            $user->setPassword($row['password']);
            $user->setRole($row['role']);
            $user->setConfirmed($row['confirmed']);
            $user->setConfirmationToken($row['confirmation_token']);
            $user->setResetToken($row['reset_token']);
        }

        $stmt->close();

        return $user;
    }

    /**
     * Stores a reset token.
     *
     * This method takes an email and a confirmation token, queries the 'users' table for a matching record, and sets the 'reset_token' field to the confirmation token.
     *
     * @param string $email The email of the user.
     * @param string $confirmation_token The confirmation token.
     */
    public function storeResetToken(string $email, string $confirmation_token) : void
    {
        try {
            $stmt = $this->db->prepare('update users set reset_token = ? where email = ?');
            $stmt->bind_param('ss', $confirmation_token, $email);
            $stmt->execute();
            $stmt->close();
        } catch (Exception $e) {
            trigger_error('Error in ' . __METHOD__ . ': ' . $e->getMessage(), E_USER_ERROR);
        }
    }

    /**
     * Updates a user's password.
     *
     * This method takes a reset token and a new password, queries the 'users' table for a matching record, and sets the 'password' field to the new password.
     *
     * @param string $reset_token The reset token of the user.
     * @param string $newPassword The new password of the user.
     * @return bool True if the password is updated, false otherwise.
     */
    public function updatePassword(string $reset_token, string $newPassword) : bool
    {
        $stmt = $this->db->prepare('SELECT email FROM users WHERE reset_token = ?');
        $stmt->bind_param('s', $reset_token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $email = $row['email'];

            $stmt = $this->db->prepare('UPDATE users SET password = ?, reset_token = NULL WHERE email = ?');
            $stmt->bind_param('ss', $newPassword, $email);
            $stmt->execute();
            $stmt->close();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Retrieves a user by reset token.
     *
     * This method takes a reset token, queries the 'users' table for a matching record, and returns a UserModel object.
     *
     * @param mixed $token The reset token of the user.
     * @return UserModel|null The user object, or null if no matching user is found.
     */
    public function getUserByResetToken(mixed $token) : ?UserModel
    {
        try {
            $stmt = $this->db->prepare('select * from users where reset_token = ?');
            $stmt->bind_param('s', $token);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows == 0) {
                return null;
            }
        } catch (Exception $e) {
            trigger_error('Error in ' . __METHOD__ . ': ' . $e->getMessage(), E_USER_ERROR);
        }

        $user = new UserModel();
        foreach ($result as $row) {
            $user->setUsername($row['username']);
            $user->setEmail($row['email']);
            $user->setPassword($row['password']);
            $user->setRole($row['role']);
            $user->setConfirmed($row['confirmed']);
            $user->setConfirmationToken($row['confirmation_token']);
            $user->setResetToken($row['reset_token']);
        }

        return $user;
    }

    /**
     * Retrieves all users.
     *
     * This method queries the 'users' table for all records and returns an array of UserModel objects.
     *
     * @return array The array of user objects.
     */
    public function getUsers() : array
    {
        $users = [];
        $stmt = $this->db->prepare('select * from users');
        $stmt->execute();
        $result = $stmt->get_result();

        foreach ($result as $row) {
            $user = new UserModel();
            $user->setUsername($row['username']);
            $user->setEmail($row['email']);
            $user->setPassword($row['password']);
            $user->setRole($row['role']);
            $user->setConfirmed($row['confirmed']);
            $user->setConfirmationToken($row['confirmation_token']);
            $user->setResetToken($row['reset_token']);
            $user->setId($row['id']);
            $users[] = $user;
        }

        return $users;
    }

    /**
     * Deletes a user by ID.
     *
     * This method takes a user ID, queries the 'users' table for a matching record, and deletes the record.
     *
     * @param int $userId The ID of the user.
     * @return bool True if the user is deleted, false otherwise.
     */
    public function deleteUserById(int $userId) : bool
    {
        $stmt = $this->db->prepare('delete from users where id = ?');
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            $stmt->close();
            return false;
        }
        $stmt->close();
        return true;
    }


    /**
     * Retrieves a user by ID.
     *
     * This method takes a user ID, queries the 'users' table for a matching record, and returns a UserModel object.
     *
     * @param int $id The ID of the user.
     * @return UserModel The user object.
     */
    public function getUser(int $id) : UserModel
    {
        try {
            $stmt = $this->db->prepare('select * from users where id = ?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
        } catch (Exception $e) {
            trigger_error('Error in ' . __METHOD__ . ': ' . $e->getMessage(), E_USER_ERROR);
        }

        $user = new UserModel();

        foreach ($stmt->get_result() as $row) {
            $user->setUsername($row['username']);
            $user->setEmail($row['email']);
            $user->setPassword($row['password']);
            $user->setRole($row['role']);
            $user->setConfirmed($row['confirmed']);
            $user->setConfirmationToken($row['confirmation_token']);
            $user->setResetToken($row['reset_token']);
            $user->setId($row['id']);
        }

        return $user;
    }
}
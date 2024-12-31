<?php

namespace Src\Repositories;

use Exception;
use Src\Models\ModelContact;

/**
 * Class ContactRepository
 *
 * This class provides methods for interacting with the 'contacts' table in the database.
 */
class ContactRepository extends Repository
{
    /**
     * ContactRepository constructor.
     *
     * Initializes the ContactRepository object.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Retrieves all contacts.
     *
     * This method queries the 'contacts' table for all records and returns an array of ModelContact objects.
     *
     * @return array The array of contact objects.
     */
    public function getContacts() : array
    {
        $contacts = [];

        try {
            $statement = $this->db->prepare('select * from contacts ');
            $statement->execute();
        } catch (Exception $e) {
            echo 'Error in ' . __METHOD__ . ': ' . $e->getMessage();        }

        foreach ($statement->get_result() as $row) {
            $contact = new ModelContact();
            $contact->setId($row['id']);
            $contact->setName($row['Name']);
            $contact->setEmail($row['Email']);
            $contact->setMessage($row['Message']);

            $contacts[] = $contact;
        }

        return $contacts;
    }

    /**
     * Class AnimalTypeRepository
     *
     * This class provides methods for interacting with the 'animaltype' table in the database.
     */
    public function createContact($contact)
    {
        try {
            $name = $contact->getName();
            $email = $contact->getEmail();
            $message = $contact->getMessage();

            $statement = $this->db->prepare('insert into Contact (Name, Email, Message) values (?, ?, ?)');
            $statement->bind_param('sss', $name, $email, $message);

            $statement->execute();
            return $statement->insert_id;
        } catch (Exception $e) {
            echo 'Error in ' . __METHOD__ . ': ' . $e->getMessage();        }

        return -1;
    }
}
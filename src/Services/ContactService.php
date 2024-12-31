<?php

namespace Src\Services;

use Src\Models\ModelContact;
use Src\Repositories\ContactRepository;

/**
 * Returns the confirmation notice page.
 *
 * This method retrieves the content of the confirmation notice page and returns it along with a HTTP status code.
 *
 * @return array The HTTP response status and body content.
 */
class ContactService
{
    private $contactRepository;

    /**
     * ContactService constructor.
     *
     * Initializes the ContactRepository object.
     */
    public function __construct()
    {
        $this->contactRepository = new ContactRepository();

    }

    /**
     * Returns the "Contact" page.
     *
     * This method retrieves the content of the "Contact" page and returns it along with a HTTP status code.
     *
     * @return array The HTTP response status and body content.
     */
    public function getContactPage(): array
    {
        ob_start(); // Start output buffering
        include(__DIR__ . '/../Pages/contact.php'); // Include the file using a file path
        $pageContent = ob_get_clean(); // Get the contents of the output buffer and clean (erase) the output buffer

        return [
            'status_code_header' => 'HTTP/1.1 200 OK',
            'body' => $pageContent
        ];
    }

    /**
     * Submits the contact form.
     *
     * This method takes the data from the contact form, creates a new ModelContact object, and attempts to create a new contact in the database.
     * If the contact is created successfully, it returns the "Contact" page. If not, it returns a 500 Internal Server Error status and an error message.
     *
     * @param array $data The data from the contact form.
     * @return array The HTTP response status and body content.
     */
    public function submitForm($data): array
    {
        $name = $data['name'];
        $email = $data['email'];
        $message = $data['message'];

        $contact = new ModelContact();

        $contact->setName($name);
        $contact->setEmail($email);
        $contact->setMessage($message);

        if ($this->contactRepository->createContact($contact) != -1) {
            return $this->getContactPage();
        } else {
            return [
                'status_code_header' => 'HTTP/1.1 500 Internal Server Error',
                'body' => json_encode(['error' => 'Error creating contact'])
            ];
        }

    }
}
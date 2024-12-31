<?php

namespace Src\Services;
/**
 * Submits the contact form.
 *
 * This method takes the data from the contact form, creates a new ModelContact object, and attempts to create a new contact in the database.
 * If the contact is created successfully, it returns the "Contact" page. If not, it returns a 500 Internal Server Error status and an error message.
 *
 * @param array $data The data from the contact form.
 * @return array The HTTP response status and body content.
 */
class HelpService
{
    /**
     * HelpService constructor.
     *
     * Initializes the HelpService object.
     */
    public function __construct()
    {
    }

    /**
     * Returns the "Help" page.
     *
     * This method retrieves the content of the "Help" page and returns it along with a HTTP status code.
     *
     * @return array The HTTP response status and body content.
     */
    public function getHelpPage()
    {
        ob_start(); // Start output buffering
        include(__DIR__ . '/../Pages/help.php'); // Include the file using a file path
        $pageContent = ob_get_clean(); // Get the contents of the output buffer and clean (erase) the output buffer

        return [
            'status_code_header' => 'HTTP/1.1 200 OK',
            'body' => $pageContent
        ];
    }
}
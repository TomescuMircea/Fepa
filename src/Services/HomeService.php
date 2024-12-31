<?php

namespace Src\Services;
/**
 * Class HomeService
 *
 * This class provides methods related to the "Home" page.
 */
class HomeService
{
    /**
     * HomeService constructor.
     *
     * Initializes the HomeService object.
     */
    public function __construct()
    {
    }

    /**
     * Returns the "Home" page.
     *
     * This method retrieves the content of the "Home" page and returns it along with a HTTP status code.
     *
     * @return array The HTTP response status and body content.
     */
    public function getHomePage()
    {
        ob_start(); // Start output buffering
        include(__DIR__ . '/../Pages/home.php'); // Include the file using a file path
        $pageContent = ob_get_clean(); // Get the contents of the output buffer and clean (erase) the output buffer

        return [
            'status_code_header' => 'HTTP/1.1 200 OK',
            'body' => $pageContent
        ];
    }
}
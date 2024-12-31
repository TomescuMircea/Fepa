<?php

namespace Src\Services;
/**
 * Class StatsService
 *
 * This class provides methods to generate various statistics related to reports.
 */
class AboutService
{
    /**
     * AboutService constructor.
     *
     * Initializes the AboutService object.
     */
    public function __construct()
    {
    }

    /**
     * Returns the "About" page.
     *
     * This method retrieves the content of the "About" page and returns it along with a HTTP status code.
     *
     * @return array The HTTP response status and body content.
     */
    public function getAboutPage() : array
    {
        ob_start(); // Start output buffering
        include(__DIR__ . '/../Pages/about.php'); // Include the file using a file path
        $pageContent = ob_get_clean(); // Get the contents of the output buffer and clean (erase) the output buffer

        return [
            'status_code_header' => 'HTTP/1.1 200 OK',
            'body' => $pageContent
        ];
    }
}
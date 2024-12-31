<?php

namespace Src\Services;

use Src\Repositories\UserRepository;
use Src\Repositories\ReportRepository;

class DashboardService
{
    private $userRepository;
    private $reportRepository;
    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->reportRepository = new ReportRepository();
    }

    /**
     * Returns the "Dashboard" page.
     *
     * This method retrieves the content of the "Dashboard" page and returns it along with a HTTP status code.
     *
     * @return array The HTTP response status and body content.
     */
    public function getDashboard()
    {
        $users = $this->userRepository->getUsers();
        $reports = $this->reportRepository->getReports();

        ob_start(); // Start output buffering
        include(__DIR__ . '/../Pages/dashboard.php'); // Include the file using a file path
        $pageContent = ob_get_clean(); // Get the contents of the output buffer and clean (erase) the output buffer

        return [
            'status_code_header' => 'HTTP/1.1 200 OK',
            'body' => $pageContent
        ];
    }
}
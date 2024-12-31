<?php

namespace Src\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Src\Models\ModelReport;
use Src\Models\ModelImage;
use Src\Models\ModelCoordinates;
use Src\Models\ModelReportHasTag;
use Src\Models\ModelTag;
use Src\Models\ModelAnimalType;

use Src\Repositories\ReportRepository;
use Src\Services\ReportsService;

class ReportsController
{
    protected $modelAnimalType;
    protected $modelImage;
    protected $modelCoordinates;
    protected $modelReportHasTags;
    protected $modelReport;
    protected $modelTags;

    protected $service;

    private $secretKey;

    private $reportRepository;

    public function __construct()
    {
        $this->modelAnimalType = new ModelAnimalType();
        $this->modelImage = new ModelImage();
        $this->modelCoordinates = new ModelCoordinates();
        $this->modelReportHasTags = new ModelReportHasTag();
        $this->modelReport = new ModelReport();
        $this->modelTags = new ModelTag();
        $this->reportRepository = new ReportRepository();

        $this->service = new ReportsService();

        $this->secretKey = SECRET_KEY; // secret key for JWT
    }

    /**
     * Checks if the user is authorized to access the report.
     *
     * This method checks if the user is authorized to access the report.
     * If the user is not authorized, it returns a 403 Forbidden response.
     *
     * @param int $reportId The ID of the report.
     * @param string|null $jwt The JWT token.
     * @return bool True if the user is authorized, false otherwise.
     */

    public function checkAuth(int $reportId, $jwt = null): bool
    {
        if ($jwt) {
            $key = new Key($this->secretKey, 'HS256');
            $decoded = JWT::decode($jwt, $key);
            // Extract the user id
            $userId = $decoded->data->id;
            $userRole = $decoded->data->role;
            if ($userRole == 'admin') {
                return true;
            }
            $report = $this->reportRepository->getReport($reportId);
            if ($userId != $report->getUserId()){
                $response = [
                    'status_code_header' => 'HTTP/1.1 403 Forbidden',
                    'body' => json_encode(['message' => 'You are not authorized to modify this report' . $userId . ' ' . $this->modelReport->getUserId()])
                ];
                header($response['status_code_header']);
                if ($response['body']) {
                    echo $response['body'];
                }
                return false;
            }
        }
        else{
            $response = $this->service->getNotAuthenticatedPage();
            header($response['status_code_header']);
            if ($response['body']) {
                echo $response['body'];
            }
            return false;
        }

        return true;
    }

    public function processRequest(string $requestMethod, string $page = null, int $reportId = null, string $ordering = null)
    {
        $jwt = $_COOKIE['jwt'] ?? null;

        switch ($requestMethod) {
            case 'GET':
                switch ($page){
                    case 'reports':
                        $response = $this->service->getReportsPage($ordering);
                        break;
                    case 'myReports':
                        // If the JWT token exists
                        if ($jwt) {
                            $key = new Key($this->secretKey, 'HS256');
                            $decoded = JWT::decode($jwt, $key);
                            // Extract the user id
                            $userId = $decoded->data->id;
                        }
                        else{
                            $response = $this->service->getNotAuthenticatedPage();
                            header($response['status_code_header']);
                            if ($response['body']) {
                                echo $response['body'];
                            }
                            exit();
                        }
                        $response = $this->service->getMyReportsPage($userId);
                        break;
                    case 'report':
                        $response = $this->service->getReportPage($reportId);
                        break;
                    case 'editReport':
                        $response = $this->service->getEditReportPage($reportId);
                        break;
                }
                break;
            case 'DELETE':
                if($reportId) {
                    if($this->checkAuth($reportId, $jwt))
                        $response = $this->service->deleteReport($reportId);
                    else exit();
                }
                else{
                    $response = [
                        'status_code_header' => 'HTTP/1.1 400 Bad Request',
                        'body' => json_encode(['message' => 'Invalid report id'])
                    ];
                }
                break;
            case 'PATCH':
                $data = json_decode(file_get_contents('php://input'), true);
                if($reportId) {
                    if($this->checkAuth($reportId, $jwt))
                        $response = $this->service->updateReportStatus($reportId, $data);
                    else exit();
                }
                else{
                    $response = [
                        'status_code_header' => 'HTTP/1.1 400 Bad Request',
                        'body' => json_encode(['message' => 'Invalid report id'])
                    ];
                }
                break;
            case 'PUT':
                $data = json_decode(file_get_contents('php://input'), true);
                $images = $_FILES;

                if($reportId) {
                    if($this->checkAuth($reportId, $jwt))
                        $response = $this->service->updateForm($reportId, $data, $images);
                    else exit();
                }
                else{
                    $response = [
                        'status_code_header' => 'HTTP/1.1 400 Bad Request',
                        'body' => json_encode(['message' => 'Invalid report id'])
                    ];
                }
                break;
        }

        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }
}

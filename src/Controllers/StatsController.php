<?php

namespace Src\Controllers;

use Exception;
use Src\Services\StatsService;

/**
 * Processes the request.
 *
 * This method determines the request method and calls the appropriate service method.
 * It then sets the response header and, if there is a response body, echoes it.
 *
 * @param array|null $data The request data.
 * @param array|null $images The images for the request.
 */
class StatsController
{
    private $requestMethod;
    private $param;
    private $statsService;

    /**
     * StatsController constructor.
     *
     * Initializes the StatsController object and sets the request method and parameter.
     *
     * @param string $requestMethod The HTTP request method.
     * @param string $param The parameter for the request.
     */
    public function __construct($requestMethod, $param)
    {
        $this->requestMethod = $requestMethod;
        $this->param = $param;
        $this->statsService = new StatsService();
    }

    /**
     * Processes the request.
     *
     * This method determines the request method and calls the appropriate service method.
     * It then sets the response header and, if there is a response body, echoes it.
     */
    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->param == "") {
                    $response = $this->statsService->getStatsPage();
                } elseif ($this->param == "pieChartData") {
                    $response = $this->statsService->getPieChartData();
                } elseif ($this->param == "barChartData") {
                    $response = $this->statsService->getBarChartData();
                } elseif ($this->param == "barPolarAreaData") {
                    $response = $this->statsService->getPolarAreaChartData();
                } elseif ($this->param == "barChartHorizontalData") {
                    $response = $this->statsService->getBarChartHorizontalData();
                } elseif ($this->param == "HTML") {
                    $response = $this->statsService->getHTMLData();
                } elseif ($this->param == "PDF") {
                    $response = $this->statsService->getPDFData();
                } elseif ($this->param == "CSV") {
                    $response = $this->statsService->getCSVData();
                } else {
                    $response = [
                        'status_code_header' => 'HTTP/1.1 404 Not Found',
                        'body' => null
                    ];
                }
                break;
            default:
                $response = [
                    'status_code_header' => 'HTTP/1.1 405 Method Not Allowed',
                    'body' => null
                ];
                break;
        }

        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }
}
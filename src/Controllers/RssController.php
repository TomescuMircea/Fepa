<?php

namespace Src\Controllers;

use Src\Services\ReportAnimalService;
use Src\Services\RssService;

/**
 * Processes the request.
 *
 * This method determines the request method and calls the appropriate service method.
 * It then sets the response header and, if there is a response body, echoes it.
 */
class RssController
{
    private $rssService;

    /**
     * RssController constructor.
     *
     * Initializes the RssController object and sets the request method and parameter.
     *
     * @param string $requestMethod The HTTP request method.
     * @param string $param The parameter for the request.
     */
    public function __construct($requestMethod, $param)
    {
        $this->requestMethod = $requestMethod;
        $this->param = $param;
        $this->rssService = new RssService();
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
                    $response = $this->rssService->getRssFeed();
                    header('Content-Type: application/rss+xml');
                    echo $response;
                    exit();
                } elseif ($this->param == "lasts") {
                    $response = $this->rssService->getRssFeedLasts();
                    header('Content-Type: application/rss+xml');
                    echo $response;
                    exit();
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
<?php

namespace Src\Services;

use Src\Models\ModelCoordinates;
use Src\Repositories\CoordinatesRepository;
use Src\Repositories\ReportRepository;

class CoordinatesService
{
    private $coordinateRepository;
    private $reportRepository;

    public function __construct()
    {
        $this->coordinateRepository = new CoordinatesRepository();
        $this->reportRepository = new ReportRepository();
    }

    public function getCoordinates($userId): string
    {
        if ($userId)
        {
            $markers = array_map(function ($coord) {
                return [
                    'latitude' => $coord->getLatitude(),
                    'longitude' => $coord->getLongitude(),
                    'reportId' => $coord->getReportId(),
                ];
            }, $this->coordinateRepository->getCoordinatesOfReportsByStatusByUserId('active', $userId));
        }
        else {
            $markers = array_map(function ($coord) {
                return [
                    'latitude' => $coord->getLatitude(),
                    'longitude' => $coord->getLongitude(),
                    'reportId' => $coord->getReportId(),
                ];
            }, $this->coordinateRepository->getCoordinatesOfReportsByStatus('active'));
        }

        return json_encode($markers);
    }

}
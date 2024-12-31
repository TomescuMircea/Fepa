<?php

namespace Src\Services;

use Src\Repositories\AnimalTypeRepository;
use Src\Repositories\ReportRepository;
use Src\Repositories\ReportHasTagRepository;
use Src\Repositories\TagRepository;

use Dompdf\Dompdf;

/**
 * Class StatsService
 *
 * This class provides methods to generate various statistics related to reports.
 */
class StatsService
{
    private $reportRepository;
    private $animalTypeRepository;
    private $reportHasTagRepository;
    private $tagRepository;

    /**
     * StatsService constructor.
     *
     * Initializes the repository objects.
     */
    public function __construct()
    {
        $this->reportRepository = new ReportRepository();
        $this->animalTypeRepository = new AnimalTypeRepository();
        $this->reportHasTagRepository = new ReportHasTagRepository();
        $this->tagRepository = new TagRepository();
    }

    /**
     * Returns the statistics page.
     *
     * @return array The HTTP response status and body content.
     */
    public function getStatsPage()
    {
        ob_start(); // Start output buffering
        include(__DIR__ . '/../Pages/statistics.php'); // Include the file using a file path
        $pageContent = ob_get_clean(); // Get the contents of the output buffer and clean (erase) the output buffer

        return [
            'status_code_header' => 'HTTP/1.1 200 OK',
            'body' => $pageContent
        ];
    }

    /**
     * Returns the pie chart data.
     *
     * @return array The HTTP response status and body content.
     */
    public function getPieChartData()
    {
        $typesObject = $this->animalTypeRepository->getAnimalTypes();

        $types = [];
        foreach ($typesObject as $type) {
            $types[] = $type->getType();
        }

        $noOfReportsArray = [];

        foreach ($types as $type) {
            $id = $this->animalTypeRepository->getAnimalTypeByType($type)->getId();
            $noOfReports = count($this->reportRepository->getReportsOfType($id));
            $noOfReportsArray[] = $noOfReports;
        }

        $data = [
            'labels' => $types,
            'values' => $noOfReportsArray,
            'colors' => [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)',
                'rgb(75, 192, 192)',
                'rgb(153, 102, 255)',
                'rgb(255, 159, 64)',
                'rgb(100, 200, 200)',
                'rgb(205, 100, 5)',
            ],
        ];

        return [
            'status_code_header' => 'HTTP/1.1 200 OK',
            'body' => json_encode($data),
        ];
    }

    /**
     * Returns the bar chart data.
     *
     * @return array The HTTP response status and body content.
     */
    public function getBarChartData()
    {
        $allReports = $this->reportRepository->getReports();

        $location = [];

        foreach ($allReports as $report) {
            $location = $report->getLocation();
            if (!isset($locations[$location])) {
                $locations[$location] = 1;
            }
            $locations[$location]++;
        }

        $data = [
            'labels' => array_keys($locations),
            'values' => array_values($locations),
            'colors' => [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)',
                'rgb(75, 192, 192)',
                'rgb(153, 102, 255)',
                'rgb(255, 159, 64)',
                'rgb(100, 200, 200)',
                'rgb(205, 100, 5)',
            ],
        ];

        return [
            'status_code_header' => 'HTTP/1.1 200 OK',
            'body' => json_encode($data),
        ];
    }

    /**
     * Returns the polar area chart data.
     *
     * @return array The HTTP response status and body content.
     */
    public function getPolarAreaChartData()
    {
        $tagsArray = $this->tagRepository->getTags();

        $tags = [];
        foreach ($tagsArray as $tag) {
            $tags[] = $tag->getText();
        }

        $noOfReportsArray = [];

        foreach ($tagsArray as $tag) {
            $noOfReports = count($this->reportHasTagRepository->getReportsWithTag($tag->getId()));
            $noOfReportsArray[] = $noOfReports;
        }

        $data = [
            'labels' => $tags,
            'values' => $noOfReportsArray,
            'colors' => [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)',
                'rgb(75, 192, 192)',
                'rgb(153, 102, 255)',
                'rgb(255, 159, 64)',
                'rgb(100, 200, 200)',
                'rgb(65, 10, 5)',
                'rgb(105, 20, 255)',
                'rgb(35, 150, 5)',
                'rgb(10, 250, 20)',
            ],
        ];

        return [
            'status_code_header' => 'HTTP/1.1 200 OK',
            'body' => json_encode($data),
        ];
    }

    /**
     * Returns the horizontal bar chart data.
     *
     * @return array The HTTP response status and body content.
     */
    public function getBarChartHorizontalData()
    {
        $dogType = $this->animalTypeRepository->getAnimalTypeByType('dog');

        $allReports = $this->reportRepository->getReportsOfType($dogType->getId());

        $locations = [];

        foreach ($allReports as $report) {
            $location = $report->getLocation();
            if (!isset($locations[$location])) {
                $locations[$location] = 0;
            }
            $locations[$location]++;
        }

        $data = [
            'labels' => array_keys($locations),
            'values' => array_values($locations),
            'colors' => [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)',
                'rgb(75, 192, 192)',
                'rgb(153, 102, 255)',
                'rgb(255, 159, 64)',
                'rgb(100, 200, 200)',
                'rgb(205, 100, 5)',
            ],
        ];

        return [
            'status_code_header' => 'HTTP/1.1 200 OK',
            'body' => json_encode($data),
        ];
    }

    /**
     * Returns the HTML data.
     *
     * @return array The HTTP response status and body content.
     */
    public function getHTMLData()
    {
        ob_start(); // Start output buffering
        include(__DIR__ . '/../Pages/tableHtml.php'); // Include the file using a file path
        $pageContent = ob_get_clean(); // Get the contents of the output buffer and clean (erase) the output buffer

        return [
            'status_code_header' => 'HTTP/1.1 200 OK',
            'body' => $pageContent
        ];
    }

    /**
     * Returns the PDF data.
     *
     * @return array The HTTP response status and body content.
     */
    public function getPDFData()
    {
        ob_start(); // Start output buffering

        include(__DIR__ . '/../Pages/tableHtml.php'); // Include the file using a file path
        $htmlContent = ob_get_clean(); // Get the contents of the output buffer and clean (erase) the output buffer

        $dompdf = new Dompdf();
        $dompdf->loadHtml($htmlContent);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        echo $dompdf->output();

        return [
            'status_code_header' => 'HTTP/1.1 200 OK',
            'body' => $dompdf->output()
        ];
    }

    /**
     * Returns the CSV data.
     *
     * @return array The HTTP response status and body content.
     */
    public function getCSVData()
    {
        ob_clean(); // Clean the output buffer

        // Open the output stream in memory
        $output = fopen('php://output', 'w');

        // Start output buffering
        ob_start();
        $tags = $this->tagRepository->getTags();
        $tagsArray = [];
        foreach ($tags as $tag) {
            $tagsArray[] = $tag->getText();
        }

        $labels = ['Type', 'No of Reports', 'Percentage of total number of reports'];
        foreach ($tagsArray as $tag) {
            $labels[] = $tag;
        }
        // Set the column headers
        fputcsv($output, $labels);

        $types = $this->animalTypeRepository->getAnimalTypes();
        $totalNoOfReports = count($this->reportRepository->getReports());


        foreach ($types as $type) {
            $noOfReports = count($this->reportRepository->getReportsOfType($type->getId()));
            $typeText = $type->getType();

            if ($totalNoOfReports == 0) {
                $percentage = 0;
            } else {
                $percentage = number_format(($noOfReports / $totalNoOfReports) * 100.00, 2);
            }
            $noOfReportsOfEachType = [];
            foreach ($tags as $tag) {
                $noOfReportsOfEachType[] = $this->reportRepository->getNumberOfTypeAnimalWithTag($type->getId(), $tag->getId());
            }
            $data = [$typeText, $noOfReports, $percentage];
            foreach ($noOfReportsOfEachType as $noOfReports) {
                $data[] = $noOfReports;
            }
            fputcsv($output, $data);
        }

        // Get the CSV content from the output buffer
        $csvContent = ob_get_clean();

        // Close the output stream
        fclose($output);

        return [
            'status_code_header' => 'HTTP/1.1 200 OK',
            'body' => $csvContent,
            'headers' => [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="data.csv"',
                'Content-Length' => strlen($csvContent),
            ]
        ];
    }

}
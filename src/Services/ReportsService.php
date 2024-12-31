<?php

namespace Src\Services;

use Src\Models\ModelImage;
use Src\Models\ModelReportHasTag;
use Src\Repositories\AnimalTypeRepository;
use Src\Repositories\ReportHasTagRepository;
use Src\Repositories\ReportRepository;
use Src\Repositories\TagRepository;
use Src\Repositories\CoordinatesRepository;
use Src\Repositories\ImageRepository;

class ReportsService
{
    private $reportRepository;
    private $animalTypeRepository;

    private $tagRepository;
    private $coordinatesRepository;
    private $imageRepository;
    private $reportHasTagsRepository;

    public function __construct()
    {
        $this->reportRepository = new ReportRepository();
        $this->animalTypeRepository = new AnimalTypeRepository();
        $this->tagRepository = new TagRepository();
        $this->coordinatesRepository = new CoordinatesRepository();
        $this->imageRepository = new ImageRepository();
        $this->reportHasTagsRepository = new ReportHasTagRepository();
    }

    /**
     * Returns the "Report Animal" page.
     *
     * This method retrieves the content of the "Report Animal" page and returns it along with a HTTP status code.
     *
     * @param string $ordering The ordering of the reports.
     *
     * @return array The HTTP response status and body content.
     */
    public function getReportsPage(string $ordering): array
    {
        if ($ordering == 'newest') {
            $reports = $this->reportRepository->getAllReportsFilterByStatusReverseByTimestamp('active');
        } else {
            $reports = $this->reportRepository->getAllReportsFilterByStatus('active');
        }
        ob_start(); // Start output buffering
        include(__DIR__ . '/../Pages/reports.php'); // Include the file using a file path
        $pageContent = ob_get_clean(); // Get the contents of the output buffer and clean (erase) the output buffer

        return [
            'status_code_header' => 'HTTP/1.1 200 OK',
            'body' => $pageContent
        ];
    }

    /**
     * Returns the "Report Animal" page.
     *
     * This method retrieves the content of the "Report Animal" page and returns it along with a HTTP status code.
     *
     * @return array The HTTP response status and body content.
     */
    public function getReportPage(mixed $reportId): array
    {

        $report = $this->reportRepository->getReport($reportId);
        $repository = $this->reportRepository;

        if (!$report) {
            return [
                'status_code_header' => 'HTTP/1.1 404 Not Found',
                'body' => 'Report not found.'
            ];
        }

        $images = $this->imageRepository->getImagesByReportId($report->getId());

        ob_start();
        include(__DIR__ . '/../Pages/report.php');
        $pageContent = ob_get_clean();

        return [
            'status_code_header' => 'HTTP/1.1 200 OK',
            'body' => $pageContent
        ];
    }

    /**
     * Returns the "My Reports" page.
     *
     * This method retrieves the content of the "My Reports" page and returns it along with a HTTP status code.
     *
     * @param mixed $id The user ID.
     * @return array The HTTP response status and body content.
     */
    public function getMyReportsPage(mixed $id): array
    {
        $userId = $id;
        $reports = $this->reportRepository->getReportsByUserId($id);
        ob_start();
        include(__DIR__ . '/../Pages/my-reports.php');
        $pageContent = ob_get_clean();

        return [
            'status_code_header' => 'HTTP/1.1 200 OK',
            'body' => $pageContent
        ];
    }

    public function getNotAuthenticatedPage(): array
    {
        ob_start();
        include(__DIR__ . '/../Pages/not-authenticated.php');
        $pageContent = ob_get_clean();

        return [
            'status_code_header' => 'HTTP/1.1 401 Unauthorized',
            'body' => $pageContent
        ];
    }

    public function deleteReport(mixed $reportId)
    {
        $report = $this->reportRepository->getReport($reportId);

        if (!$report) {
            return [
                'status_code_header' => 'HTTP/1.1 404 Not Found',
                'body' => 'Report not found.'
            ];
        }

        $this->reportRepository->delete($reportId);

        return [
            'status_code_header' => 'HTTP/1.1 204 No Content',
            'body' => null
        ];
    }

    public function updateReportStatus(mixed $reportId, mixed $data)
    {
        $report = $this->reportRepository->getReport($reportId);

        if (!$report) {
            return [
                'status_code_header' => 'HTTP/1.1 404 Not Found',
                'body' => 'Report not found.'
            ];
        }

        $this->reportRepository->updateStatus($reportId, $data['status']);

        return [
            'status_code_header' => 'HTTP/1.1 204 No Content',
            'body' => null
        ];
    }

    public function getEditReportPage(mixed $reportId)
    {
        $report = $this->reportRepository->getReport($reportId);

        if (!$report) {
            return [
                'status_code_header' => 'HTTP/1.1 404 Not Found',
                'body' => 'Report not found.'
            ];
        }

        $animalTypes = $this->animalTypeRepository->getAnimalTypes();
        $tags = $this->tagRepository->getAllTags();
        $coordinates = $this->coordinatesRepository->getCoordinatesByReportId($reportId);
        $reportRepository = $this->reportRepository;

        ob_start();
        include(__DIR__ . '/../Pages/edit-report.php');
        $pageContent = ob_get_clean();

        return [
            'status_code_header' => 'HTTP/1.1 200 OK',
            'body' => $pageContent
        ];
    }

    /**
     * Submits the "Report Animal" form.
     *
     * This method processes the form data submitted by the user and saves the report to the database.
     *
     * @param int $reportId The ID of the report to update.
     * @param array $data The form data submitted by the user.
     * @param array $images The images uploaded by the user.
     *
     * @return array The HTTP response status and body content.
     */
    public function updateForm(int $reportId, array $data, array $images): array
    {
        $name = $data['name'];
        $animalName = $data['animalName'];
        $location = $data['location'];
        $animal_type = $data['animalType'];
        $description = $data['description'];
        $phoneNumber = $data['phoneNumber'];
        $tags = $data['tags'];
        $latitude = $data['latitude'];
        $longitude = $data['longitude'];

        $animalTypeId = $this->animalTypeRepository->getAnimalTypeByType($animal_type)->getId();

        // Update the report
        $modelReport = $this->reportRepository->getReport($reportId);
        $modelReport->setName($name);
        $modelReport->setAnimalName($animalName);
        $modelReport->setLocation($location);
        $modelReport->setDescription($description);
        $modelReport->setPhoneNumber($phoneNumber);
        $modelReport->setAnimalTypeID($animalTypeId);
        $modelReport->setTags($tags);

        $this->reportRepository->updateReport($modelReport);

        // Update coordinates
        $modelCoordinates = $this->coordinatesRepository->getCoordinatesByReportId($reportId);
        $modelCoordinates->setLatitude($latitude);
        $modelCoordinates->setLongitude($longitude);

        $this->coordinatesRepository->updateCoordinates($modelCoordinates);

        $uploadDirectory = 'user-image-uploads/';
        $defaultImage = 'images/default.jpg';

        if (!empty($images['name'])) {
            $totalFiles = count($images['name']);
            for ($i = 0; $i < $totalFiles; ++$i) {
                $fileExtension = pathinfo($images['name'][$i], PATHINFO_EXTENSION);
                do {
                    $uniqueFilename = uniqid() . '_' . bin2hex(random_bytes(8)) . '.' . $fileExtension;
                    $fileDestination = $uploadDirectory . $uniqueFilename;
                } while (file_exists($fileDestination)); // Check if file already exists

                if (move_uploaded_file($images['tmp_name'][$i], $fileDestination)) {
                    // Insert image file name into database
                    $modelImage = new ModelImage();
                    $modelImage->setFileName($uniqueFilename);
                    $modelImage->setReportId($reportId);
                    $modelImage->setExtension($fileExtension);
                    $this->imageRepository->createImage($modelImage);
                } else {
                    // Error handling if file upload fails
                    echo 'Error uploading file: ' . $images['error'][$i];
                }
            }
        }

        // Update tags
        if ($tags != '') {
            $tags = str_replace(', ', ',', $tags);
            $tagsArray = explode(',', $tags);

            // Remove existing tags for the report
            $this->reportHasTagsRepository->deleteTagsByReportId($reportId);

            // Add new tags
            foreach ($tagsArray as $tag) {
                $tagId = $this->tagRepository->getTag($tag)->getId();

                $modelReportHasTags = new ModelReportHasTag();
                $modelReportHasTags->setReportId($reportId);
                $modelReportHasTags->setTagId($tagId);
                $this->reportHasTagsRepository->createReportHasTags($modelReportHasTags);
            }
        }

        return [
            'status_code_header' => 'HTTP/1.1 200 OK',
            'body' => json_encode(['success' => true, 'message' => 'Report updated successfully'])
        ];
    }


}
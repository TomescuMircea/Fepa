<?php

namespace Src\Services;

use Src\Models\ModelImage;
use Src\Models\ModelReportHasTag;
use Src\Repositories\AnimalTypeRepository;
use Src\Repositories\ReportHasTagRepository;
use Src\Repositories\ReportRepository;
use Src\Repositories\CoordinatesRepository;
use Src\Repositories\ImageRepository;

use Src\Models\ModelReport;
use Src\Models\ModelCoordinates;
use Src\Repositories\TagRepository;

/**
 * Class ReportAnimalService
 *
 * This class provides methods related to reporting an animal sighting.
 */
class ReportAnimalService
{
    private $reportRepository;
    private $animalTypeRepository;
    private $coordinatesRepository;
    private $imageRepository;
    private $reportHasTagsRepository;
    private $tagRepository;

    /**
     * ReportAnimalService constructor.
     *
     * Initializes the ReportRepository, AnimalTypeRepository, CoordinatesRepository, ImageRepository, ReportHasTagRepository, and TagRepository objects.
     */
    public function __construct()
    {
        $this->reportRepository = new ReportRepository();
        $this->animalTypeRepository = new AnimalTypeRepository();
        $this->coordinatesRepository = new CoordinatesRepository();
        $this->imageRepository = new ImageRepository();
        $this->reportHasTagsRepository = new ReportHasTagRepository();
        $this->tagRepository = new TagRepository();
    }

    /**
     * Returns the "Report Animal" page.
     *
     * This method retrieves the content of the "Report Animal" page and returns it along with a HTTP status code.
     *
     * @return array The HTTP response status and body content.
     */
    public function getReportPage()
    {
        $tags = $this->tagRepository->getAllTags();
        $animalTypes = $this->animalTypeRepository->getAnimalTypes();

        ob_start(); // Start output buffering
        include(__DIR__ . '/../Pages/report-animal.php'); // Include the file using a file path
        $pageContent = ob_get_clean(); // Get the contents of the output buffer and clean (erase) the output buffer

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
     * @param array $data The form data submitted by the user.
     * @param array $images The images uploaded by the user.
     * @param int $userId The ID of the user submitting the report.
     *
     * @return array The HTTP response status and body content.
     */
    public function submitForm(array $data, array $images, int $userId): array
    {
        $name = $data['name'];
        $animalName = $data['animal_name'];
        $location = $data['location'];
        $animal_type = $data['animal_type'];
        $description = $data['description'];
        $phoneNumber = $data['phone_number'];
        $tags = $data['tags'];
        $latitude = $data['latitude'];
        $longitude = $data['longitude'];

        $animalTypeId = $this->animalTypeRepository->getAnimalTypeByType($animal_type)->getId();

        $modelReport = new ModelReport();
        $modelReport->setName($name);
        $modelReport->setAnimalName($animalName);
        $modelReport->setLocation($location);
        $modelReport->setDescription($description);
        $modelReport->setPhoneNumber($phoneNumber);
        $modelReport->setAnimalTypeID($animalTypeId);
        $modelReport->setTags($tags);
        $modelReport->setUserId($userId);

        $reportId = $this->reportRepository->createReport($modelReport);

        $modelCoordinates = new ModelCoordinates();
        $modelCoordinates->setReportId($reportId);
        $modelCoordinates->setLatitude($latitude);
        $modelCoordinates->setLongitude($longitude);

        $this->coordinatesRepository->createCoordinates($modelCoordinates);

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
//                echo '<br>'.$images['name'][$i].'<br>';
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
        } else {
            // Handle default image
            $fileExtension = pathinfo($defaultImage, PATHINFO_EXTENSION);
            $uniqueFilename = uniqid() . '_' . bin2hex(random_bytes(8)) . '.' . $fileExtension;
            $fileDestination = $uploadDirectory . $uniqueFilename;

            // Copy the default image to the destination
            if (copy($defaultImage, $fileDestination)) {
                // Insert default image file name into database
                $modelImage = new ModelImage();
                $modelImage->setFileName($uniqueFilename);
                $modelImage->setReportId($reportId);
                $modelImage->setExtension($fileExtension);
                $this->imageRepository->createImage($modelImage);
            } else {
                // Error handling if file copy fails
                echo 'Error copying default image';
            }
        }

        if ($tags != '') {
            $tags = str_replace(', ', ',', $tags);
            $tagsArray = explode(',', $tags);

            foreach ($tagsArray as $tag) {
                $tagId = $this->tagRepository->getTag($tag)->getId();

                // echo $tagId;
                $modelReportHasTags = new ModelReportHasTag();
                $modelReportHasTags->setReportId($reportId);
                $modelReportHasTags->setTagId($tagId);
                $this->reportHasTagsRepository->createReportHasTags($modelReportHasTags);
            }
        }


        return $this->getReportPage();
    }

    /**
     * Returns the "Not Authenticated" page.
     *
     * This method retrieves the content of the "Not Authenticated" page and returns it along with a HTTP status code.
     *
     * @return array The HTTP response status and body content.
     */
    public function getNotAuthenticatedPage(): array
    {
        ob_start(); // Start output buffering
        include(__DIR__ . '/../Pages/not-authenticated.php'); // Include the file using a file path
        $pageContent = ob_get_clean(); // Get the contents of the output buffer and clean (erase) the output buffer

        return [
            'status_code_header' => 'HTTP/1.1 200 OK',
            'body' => $pageContent
        ];
    }
}
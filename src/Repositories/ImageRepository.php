<?php

namespace Src\Repositories;

use Exception;
use Src\Models\ModelImage;

/**
 * Class ImageRepository
 *
 * This class provides methods for interacting with the 'images' table in the database.
 */
class ImageRepository extends Repository
{
    /**
     * ImageRepository constructor.
     *
     * Initializes the ImageRepository object.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Retrieves all images.
     *
     * This method queries the 'images' table for all records and returns an array of ModelImage objects.
     *
     * @return array The array of image objects.
     */
    public function getImages(): array
    {
        $images = [];

        try {
            $statement = $this->db->prepare('select * from images ');
            $statement->execute();
        } catch (Exception $e) {
            echo 'Error in ' . __METHOD__ . ': ' . $e->getMessage();
        }

        foreach ($statement->get_result() as $row) {
            $image = new ModelImage();
            $image->setFileName($row['file_name']);
            $image->setExtension($row['Extension']);
            $image->setReportId($row['Reports_id']);

            $images[] = $image;
        }

        return $images;
    }

    /**
     * Creates a new image.
     *
     * This method takes a ModelImage object, extracts the file name, extension, and report ID, and attempts to create a new record in the 'images' table.
     * If the record is created successfully, it returns the ID of the new image. If not, it returns -1.
     *
     * @param ModelImage $image The image to create.
     * @return int The ID of the new image, or -1 if the creation failed.
     */
    public function createImage(ModelImage $image) : int
    {
        try {
            $statement = $this->db->prepare('insert into images (file_name, Extension,Reports_id) VALUES(?, ?,?)');
            $fileName = $image->getFileName();
            $extension = $image->getExtension();
            $reportId = $image->getReportId();
            $statement->bind_param('ssi', $fileName, $extension, $reportId);
            $statement->execute();

            return $statement->insert_id;
        } catch (Exception $e) {
            echo 'Error in ' . __METHOD__ . ': ' . $e->getMessage();
        }

        return -1;
    }

    /**
     * Retrieves images by report ID.
     *
     * This method takes a report ID, queries the 'images' table for matching records, and returns an array of ModelImage objects.
     *
     * @param int $id The ID of the report.
     * @return array The array of image objects.
     */
    public function getImagesByReportId(int $id) : array
    {
        $images = [];

        try {
            $statement = $this->db->prepare('select * from images where Reports_id = ? ');
            $statement->bind_param('i', $id);
            $statement->execute();
        } catch (Exception $e) {
            echo 'Error in ' . __METHOD__ . ': ' . $e->getMessage();
        }

        foreach ($statement->get_result() as $row) {
            $image = new ModelImage();
            $image->setFileName($row['file_name']);
            $image->setExtension($row['Extension']);
            $image->setReportId($row['Reports_id']);

            $images[] = $image;
        }

        return $images;
    }
}
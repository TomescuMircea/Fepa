<?php

namespace Src\Repositories;

use Exception;
use Src\Models\ModelCoordinates;

/**
 * Class CoordinatesRepository
 *
 * This class provides methods for interacting with the 'coordinates' table in the database.
 */
class CoordinatesRepository extends Repository
{
    /**
     * CoordinatesRepository constructor.
     *
     * Initializes the CoordinatesRepository object.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Creates a new coordinates record.
     *
     * This method takes a ModelCoordinates object, extracts the latitude, longitude, and report ID, and attempts to create a new record in the 'coordinates' table.
     * If the record is created successfully, it returns the ID of the new coordinates. If not, it returns -1.
     *
     * @param ModelCoordinates $coordinates The coordinates to create.
     * @return int The ID of the new coordinates, or -1 if the creation failed.
     */
    public function createCoordinates(ModelCoordinates $coordinates) : int
    {
        try {
            $statement = $this->db->prepare('insert into coordinates (latitude,longitude,Reports_id) VALUES(?, ?,?)');
            $latitude = $coordinates->getLatitude();
            $longitude = $coordinates->getLongitude();
            $reportId = $coordinates->getReportId();
            $statement->bind_param('ssi', $latitude, $longitude, $reportId);
            $statement->execute();

            return $statement->insert_id;
        } catch (Exception $e) {
            echo 'Error in ' . __METHOD__ . ': ' . $e->getMessage();
        }

        return -1;
    }

    /**
     * Retrieves all coordinates.
     *
     * This method queries the 'coordinates' table for all records and returns an array of ModelCoordinates objects.
     *
     * @return array The array of coordinates objects.
     */
    public function getCoordinates(): array
    {
        $coordinates = [];

        try {
            $stmt = $this->db->prepare('select * from coordinates ');
            $stmt->execute();
        } catch (Exception $e) {
            echo 'Error in ' . __METHOD__ . ': ' . $e->getMessage();
        }

        foreach ($stmt->get_result() as $row) {
            $coordinate = new ModelCoordinates();
            $coordinate->setLatitude($row['latitude']);
            $coordinate->setLongitude($row['longitude']);
            $coordinate->setReportId($row['Reports_id']);

            $coordinates[] = $coordinate;
        }

        return $coordinates;
    }

    public function getCoordinatesOfReportsByStatus($status) : array {
        $coordinates = [];

        try {
            $stmt = $this->db->prepare('select * from coordinates c left join reports r on c.Reports_id = r.id where r.status = ?');
            $stmt->bind_param('s', $status);
            $stmt->execute();
        } catch (Exception $e) {
            echo 'Error in ' . __METHOD__ . ': ' . $e->getMessage();
        }

        foreach ($stmt->get_result() as $row) {
            $coordinate = new ModelCoordinates();
            $coordinate->setLatitude($row['latitude']);
            $coordinate->setLongitude($row['longitude']);
            $coordinate->setReportId($row['Reports_id']);

            $coordinates[] = $coordinate;
        }

        return $coordinates;
    }
    /**
     * Retrieves coordinates by report ID.
     *
     * This method takes a report ID, queries the 'coordinates' table for matching records, and returns a ModelCoordinates object.
     *
     * returns the array of coordinates objects.
     */
    public function getAllCoordinates() : array
    {
        $coordinates = [];

        try {
            $stmt = $this->db->prepare('select * from coordinates ');
            $stmt->execute();
        } catch (Exception $e) {
            echo 'Error in ' . __METHOD__ . ': ' . $e->getMessage();
        }

        foreach ($stmt->get_result() as $row) {
            $coordinate = new ModelCoordinates();
            $coordinate->setLatitude($row['latitude']);
            $coordinate->setLongitude($row['longitude']);
            $coordinate->setReportId($row['Reports_id']);

            $coordinates[] = $coordinate;
        }

        return $coordinates;
    }

    public function getCoordinatesByReportId(mixed $reportId)
    {
        try {
            $statement = $this->db->prepare('select * from coordinates where Reports_id = ?');
            $statement->bind_param('i', $reportId);
            $statement->execute();
        } catch (Exception $e) {
            echo 'Error in ' . __METHOD__ . ': ' . $e->getMessage();
        }

        $coordinates = new ModelCoordinates();
        foreach ($statement->get_result() as $row) {
            $coordinates->setLatitude($row['latitude']);
            $coordinates->setLongitude($row['longitude']);
            $coordinates->setReportId($row['Reports_id']);
        }

        return $coordinates;
    }

    public function updateCoordinates($coordinates)
    {
        try {
            $statement = $this->db->prepare('update coordinates set latitude = ?, longitude = ? where Reports_id = ?');
            $latitude = $coordinates->getLatitude();
            $longitude = $coordinates->getLongitude();
            $reportId = $coordinates->getReportId();
            $statement->bind_param('ssi', $latitude, $longitude, $reportId);
            $statement->execute();
        } catch (Exception $e) {
            echo 'Error in ' . __METHOD__ . ': ' . $e->getMessage();
        }
    }

    public function getCoordinatesOfReportsByStatusByUserId($status, $userId) : array {
        $coordinates = [];

        try {
            $stmt = $this->db->prepare('select * from coordinates c left join reports r on c.Reports_id = r.id where r.status = ? and r.user_id = ?');
            $stmt->bind_param('si', $status, $userId);
            $stmt->execute();
        } catch (Exception $e) {
            echo 'Error in ' . __METHOD__ . ': ' . $e->getMessage();
        }

        foreach ($stmt->get_result() as $row) {
            $coordinate = new ModelCoordinates();
            $coordinate->setLatitude($row['latitude']);
            $coordinate->setLongitude($row['longitude']);
            $coordinate->setReportId($row['Reports_id']);

            $coordinates[] = $coordinate;
        }

        return $coordinates;
    }
}
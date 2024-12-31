<?php

namespace Src\Repositories;

include_once __DIR__ . '/Repository.php';

use Exception;
use Src\Models\ModelReport;

/**
 * Class ReportRepository
 *
 * This class provides methods for interacting with the 'reports' table in the database.
 */
class ReportRepository extends Repository
{
    /**
     * ReportRepository constructor.
     *
     * Initializes the ReportRepository object.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Retrieves all reports.
     *
     * This method queries the 'reports' table for all records and returns an array of ModelReport objects.
     *
     * @return array The array of report objects.
     */
    public function getReports()
    {
        $reports = [];

        try {
            $statement = $this->db->prepare('select * from reports ');
            $statement->execute();
        } catch (Exception $e) {
            echo 'Error in ' . __METHOD__ . ': ' . $e->getMessage();
        }

        foreach ($statement->get_result() as $row) {
            $report = new ModelReport();
            $report->setId($row['id']);
            $report->setName($row['Name']);
            $report->setAnimalName($row['Animal_name']);
            $report->setLocation($row['Location']);
            $report->setDescription($row['Description']);
            $report->setPhoneNumber($row['Phone_Number']);
            $report->setAnimalTypeId($row['AnimalType_id']);
            $report->setTimestamp($row['timestamp']);
            $report->setUserId($row['user_id']);

            $reports[] = $report;
        }

        return $reports;
    }

    /**
     * Retrieves all reports ordered by timestamp in reverse order.
     *
     * This method queries the 'reports' table for all records, orders them by timestamp in descending order, and returns an array of ModelReport objects.
     *
     * @return array The array of report objects.
     */
    public function getReportsOrderByTimestampReverse()
    {
        $reports = [];

        try {
            $statement = $this->db->prepare('select * from reports order by timestamp desc');
            $statement->execute();
        } catch (Exception $e) {
            echo 'Error in ' . __METHOD__ . ': ' . $e->getMessage();
        }

        foreach ($statement->get_result() as $row) {
            $report = new ModelReport();
            $report->setId($row['id']);
            $report->setName($row['Name']);
            $report->setAnimalName($row['Animal_name']);
            $report->setLocation($row['Location']);
            $report->setDescription($row['Description']);
            $report->setPhoneNumber($row['Phone_Number']);
            $report->setAnimalTypeId($row['AnimalType_id']);
            $report->setTimestamp($row['timestamp']);

            $reports[] = $report;
        }

        return $reports;
    }

    /**
     * Retrieves the last N reports ordered by timestamp in reverse order.
     *
     * This method retrieves all reports, orders them by timestamp in descending order, and returns the last N reports as an array of ModelReport objects.
     *
     * @param int $lastN The number of reports to retrieve.
     * @return array The array of report objects.
     */
    public function getReportsOrderByTimestampReverseN($lastN)
    {
        $reports = $this->getReports();

        $reportsReverse = [];

        for ($i = count($reports) - 1; $i >= 0 && $lastN > 0; $i--) {
            $reportsReverse[] = $reports[$i];
            $lastN--;
        }
        return $reportsReverse;

    }

    /**
     * Retrieves all reports of a specific type.
     *
     * This method queries the 'reports' table for all records with a specific animal type ID and returns an array of ModelReport objects.
     *
     * @param int $typeId The ID of the animal type.
     * @return array The array of report objects.
     */
    public function getReportsOfType($typeId)
    {
        $reports = [];

        try {
            $statement = $this->db->prepare('select * from reports where AnimalType_id = ? ');
            $statement->bind_param('i', $typeId);
            $statement->execute();
        } catch (Exception $e) {
            echo 'Error in ' . __METHOD__ . ': ' . $e->getMessage();        }

        foreach ($statement->get_result() as $row) {
            $report = new ModelReport();
            $report->setId($row['id']);
            $report->setName($row['Name']);
            $report->setAnimalName($row['Animal_name']);
            $report->setLocation($row['Location']);
            $report->setDescription($row['Description']);
            $report->setPhoneNumber($row['Phone_Number']);
            $report->setAnimalTypeId($row['AnimalType_id']);
            $report->setStatus($row['status']);
            $reports[] = $report;
        }

        return $reports;
    }

    /**
     * Retrieves a report by its ID.
     *
     * This method queries the 'reports' table for a record with a specific ID and returns a ModelReport object.
     *
     * @param int $reportId The ID of the report.
     * @return ModelReport The report object.
     */
    public function getReport($reportId)
    {
        try {
            $statement = $this->db->prepare('select * from reports where id = ? ');
            $statement->bind_param('i', $reportId);
            $statement->execute();
        } catch (Exception $e) {
            echo 'Error in ' . __METHOD__ . ': ' . $e->getMessage();        }

        $report = new ModelReport();
        foreach ($statement->get_result() as $row) {
            $report->setId($row['id']);
            $report->setName($row['Name']);
            $report->setAnimalName($row['Animal_name']);
            $report->setLocation($row['Location']);
            $report->setDescription($row['Description']);
            $report->setPhoneNumber($row['Phone_Number']);
            $report->setAnimalTypeId($row['AnimalType_id']);
            $report->setTags($row['tags']);
            $report->setUserId($row['user_id']);
            $report->setStatus($row['status']);
            $report->setTimestamp($row['timestamp']);
        }

        return $report;
    }

    /**
     * Creates a new report.
     *
     * This method takes a ModelReport object, extracts the name, animal name, location, description, phone number, and animal type ID, and attempts to create a new record in the 'reports' table.
     * If the record is created successfully, it returns the ID of the new report. If not, it returns -1.
     *
     * @param ModelReport $report The report to create.
     * @return int The ID of the new report, or -1 if the creation failed.
     */
    public function createReport($report)
    {
        try {
            $statement = $this->db->prepare('insert into reports (Name,Animal_name,Location,Description,Phone_Number, tags, AnimalType_id, user_id) VALUES(?,?,?,?,?,?,?,?)');
            $name = $report->getName();
            $animalName = $report->getAnimalName();
            $location = $report->getLocation();
            $description = $report->getDescription();
            $phoneNumber = $report->getPhoneNumber();
            $animalTypeId = $report->getAnimalTypeId();
            $userId = $report->getUserId();
            $tags = $report->getTags();

            $statement->bind_param('ssssssii', $name, $animalName, $location, $description, $phoneNumber, $tags, $animalTypeId, $userId);

            $statement->execute();

            return $statement->insert_id;
        } catch (Exception $e) {
            echo 'Error in ' . __METHOD__ . ': ' . $e->getMessage();        }

        return -1;
    }

    /**
     * Creates multiple new reports.
     *
     * This method takes an array of ModelReport objects and attempts to create a new record in the 'reports' table for each one.
     *
     * @param array $reports The array of reports to create.
     */
    public function createReports($reports)
    {
        try {
            foreach ($reports as $report) {
                $this->createReport($report);
            }
        } catch (Exception $e) {
            echo 'Error in ' . __METHOD__ . ': ' . $e->getMessage();        }
    }

    /**
     * Updates a report.
     *
     * This method takes a ModelReport object and attempts to update the corresponding record in the 'reports' table.
     *
     * @param ModelReport $report The report to update.
     */
    public function updateReport($report)
    {
        try {
            $statement = $this->db->prepare('update reports set Name = ?, Animal_name = ?, Location = ?, Description = ?, Phone_Number = ?, tags = ?, AnimalType_id = ? where id = ?');
            $name = $report->getName();
            $animalName = $report->getAnimalName();
            $location = $report->getLocation();
            $description = $report->getDescription();
            $phoneNumber = $report->getPhoneNumber();
            $animalTypeId = $report->getAnimalTypeId();
            $tags = $report->getTags();
            $id = $report->getId();

            $statement->bind_param('ssssssii', $name, $animalName, $location, $description, $phoneNumber, $tags, $animalTypeId, $id);
            $statement->execute();
        } catch (Exception $e) {
            echo 'Error in ' . __METHOD__ . ': ' . $e->getMessage();        }
    }

    /**
     * Updates multiple reports.
     *
     * This method takes an array of ModelReport objects and attempts to update the corresponding record in the 'reports' table for each one.
     *
     * @param array $reports The array of reports to update.
     */
    public function updateReports($reports)
    {
        try {
            foreach ($reports as $report) {
                $this->updateReport($report);
            }
        } catch (Exception $e) {
            echo 'Error in ' . __METHOD__ . ': ' . $e->getMessage();        }
    }

    /**
     * Deletes a report.
     *
     * This method takes a report ID and attempts to delete the corresponding record from the 'reports' table.
     *
     * @param int $reportId The ID of the report to delete.
     */
    public function deleteReport($reportId)
    {
        try {
            // Complete the missing code
        } catch (Exception $e) {
            echo 'Error in ' . __METHOD__ . ': ' . $e->getMessage();        }
    }

    /**
     * Deletes all reports.
     *
     * This method attempts to delete all records from the 'reports' table.
     */
    public function deleteReports()
    {
        try {
            // Complete the missing code
        } catch (Exception $e) {
            echo 'Error in ' . __METHOD__ . ': ' . $e->getMessage();        }
    }

    /**
     * Retrieves the number of reports of a specific animal type with a specific tag.
     *
     * This method queries the 'reports' and 'reports_has_tags' tables for all records with a specific animal type ID and tag ID and returns the count of such reports.
     *
     * @param int $typeId The ID of the animal type.
     * @param int $tagId The ID of the tag.
     * @return int The count of reports.
     */
    public function getNumberOfTypeAnimalWithTag($typeId, $tagId)
    {
        $reports = [];

        try {
            $statement = $this->db->prepare('select * from reports join reports_has_tags on reports.id = reports_has_tags.Reports_id where AnimalType_id = ? and Tags_id = ?');
            $statement->bind_param('ii', $typeId, $tagId);
            $statement->execute();
        } catch (Exception $e) {
            echo 'Error in ' . __METHOD__ . ': ' . $e->getMessage();        }

        foreach ($statement->get_result() as $row) {
            $report = new ModelReport();
            $report->setId($row['id']);
            $report->setName($row['Name']);
            $report->setAnimalName($row['Animal_name']);
            $report->setLocation($row['Location']);
            $report->setDescription($row['Description']);
            $report->setPhoneNumber($row['Phone_Number']);
            $report->setAnimalTypeId($row['AnimalType_id']);
            $reports[] = $report;
        }

        return count($reports);
    }

    /**
     * Retrieves the animal type of a report.
     *
     * This method takes a report ID, queries the 'reports' table for a record with a specific ID, and returns the animal type of the report.
     *
     * @param int $reportId The ID of the report.
     * @return string The animal type.
     */
    public function getAnimalType($reportId)
    {
        try {
            $statement = $this->db->prepare('select * from reports where id = ? ');
            $statement->bind_param('i', $reportId);
            $statement->execute();
            $animalTypeId = $statement->get_result()->fetch_assoc()['AnimalType_id'];
            $statement->close();

            $statement = $this->db->prepare('select * from animaltype where id = ? ');
            $statement->bind_param('i', $animalTypeId);
            $statement->execute();

            return $statement->get_result()->fetch_assoc()['Type'];
            // Return the 'type' from
        } catch (Exception $e) {
            echo 'Error in ' . __METHOD__ . ': ' . $e->getMessage();        }

        return 'meow';
    }

    /**
     * Retrieves the tags of a report.
     *
     * This method takes a report ID, queries the 'reports' table for a record with a specific ID, and returns the tags of the report.
     *
     * @param int $getId The ID of the report.
     * @return string The tags.
     */
    public function getTags(int $getId) : string
    {
        $tags = [];

        try {
            $statement = $this->db->prepare('select tags from reports where id = ? ');
            $statement->bind_param('i', $getId);
            $statement->execute();
        } catch (Exception $e) {
            echo 'Error in ' . __METHOD__ . ': ' . $e->getMessage();        }

        return $statement->get_result()->fetch_assoc()['tags'];
    }

    /**
     * Retrieves the status of a report.
     *
     * This method takes a report ID, queries the 'reports' table for a record with a specific ID, and returns the status of the report.
     *
     * @param int $id The ID of the report.
     * @return array The array of report objects.
     */
    public function getReportsByUserId(int $id) : array
    {
        $reports = [];

        try {
            $statement = $this->db->prepare('select * from reports where user_id = ? ');
            $statement->bind_param('i', $id);
            $statement->execute();
        } catch (Exception $e) {
            echo 'Error in ' . __METHOD__ . ': ' . $e->getMessage();        }

        foreach ($statement->get_result() as $row) {
            $report = new ModelReport();
            $report->setId($row['id']);
            $report->setName($row['Name']);
            $report->setAnimalName($row['Animal_name']);
            $report->setLocation($row['Location']);
            $report->setDescription($row['Description']);
            $report->setPhoneNumber($row['Phone_Number']);
            $report->setAnimalTypeId($row['AnimalType_id']);
            $report->setTags($row['tags']);
            $report->setStatus($row['status']);
            $report->setUserId($row['user_id']);
            $reports[] = $report;
        }

        return $reports;
    }

    /**
     * Deletes a report.
     *
     * This method takes a report ID and deletes the corresponding record from the 'reports' table.
     *
     * @param int $reportId The ID of the report to delete.
     * @return void
     */
    public function delete(mixed $reportId) : void
    {
        try {
            $statement = $this->db->prepare('delete from reports_has_tags where Reports_id = ?');
            $statement->bind_param('i', $reportId);
            $statement->execute();
            $statement->close();

            $statement = $this->db->prepare('delete from reports where id = ?');
            $statement->bind_param('i', $reportId);
            $statement->execute();
        } catch (Exception $e) {
            echo 'Error in ' . __METHOD__ . ': ' . $e->getMessage();        }
    }

    /**
     * Updates the status of a report.
     *
     * This method takes a report ID and status, and updates the corresponding record in the 'reports' table.
     *
     * @param mixed $reportId The ID of the report.
     * @param mixed $status The status of the report.
     * @return void
     */
    public function updateStatus(mixed $reportId, mixed $status) : void
    {
        try {
            $statement = $this->db->prepare('update reports set status = ? where id = ?');
            $statement->bind_param('si', $status, $reportId);
            $statement->execute();
        } catch (Exception $e) {
            echo 'Error in ' . __METHOD__ . ': ' . $e->getMessage();        }
    }


    /**
     * Retrieves all reports filtered by status.
     *
     * This method takes a status, queries the 'reports' table for matching records, and returns an array of ModelReport objects.
     *
     * @param string $string The status to filter by.
     * @return array The array of report objects.
     */
    public function getAllReportsFilterByStatus(string $string) : array
    {
        $reports = [];

        try {
            $statement = $this->db->prepare('select * from reports where status = ?');
            $statement->bind_param('s', $string);
            $statement->execute();
        } catch (Exception $e) {
            echo 'Error in ' . __METHOD__ . ': ' . $e->getMessage();        }

        foreach ($statement->get_result() as $row) {
            $report = new ModelReport();
            $report->setId($row['id']);
            $report->setName($row['Name']);
            $report->setAnimalName($row['Animal_name']);
            $report->setLocation($row['Location']);
            $report->setDescription($row['Description']);
            $report->setPhoneNumber($row['Phone_Number']);
            $report->setAnimalTypeId($row['AnimalType_id']);
            $report->setTags($row['tags']);
            $report->setStatus($row['status']);
            $report->setUserId($row['user_id']);
            $reports[] = $report;
        }

        return $reports;
    }

    public function getAllReportsFilterByStatusReverseByTimestamp(string $string)
    {
        $reports = [];

        try {
            $statement = $this->db->prepare('select * from reports where status = ? order by timestamp desc');
            $statement->bind_param('s', $string);
            $statement->execute();
        } catch (Exception $e) {
            triggerError('Error in ' . __METHOD__ . ': ' . $e->getMessage(), E_USER_ERROR);
        }

        foreach ($statement->get_result() as $row) {
            $report = new ModelReport();
            $report->setId($row['id']);
            $report->setName($row['Name']);
            $report->setAnimalName($row['Animal_name']);
            $report->setLocation($row['Location']);
            $report->setDescription($row['Description']);
            $report->setPhoneNumber($row['Phone_Number']);
            $report->setAnimalTypeId($row['AnimalType_id']);
            $report->setTags($row['tags']);
            $report->setStatus($row['status']);
            $report->setUserId($row['user_id']);
            $reports[] = $report;
        }

        return $reports;
    }
}